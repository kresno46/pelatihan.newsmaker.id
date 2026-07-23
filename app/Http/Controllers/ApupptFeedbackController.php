<?php

namespace App\Http\Controllers;

use App\Models\ApupptFeedbackForm;
use App\Models\ApupptFeedbackQuestion;
use App\Models\User;
use Illuminate\Http\Request;

class ApupptFeedbackController extends Controller
{
    private function resolveForcedRole()
    {
        $authUser = auth()->user();

        return $authUser && $authUser->isApupptAdmin() ? $authUser->apuppt_pt_scope : null;
    }

    public function edit(Request $request)
    {
        $authUser = auth()->user();
        $forcedRole = $this->resolveForcedRole();

        if ($authUser->isApupptAdmin() && ! $forcedRole) {
            abort(403, 'PT scope untuk Admin APUPPT belum diatur.');
        }

        $ptOptions = User::APUPPT_PT_ROLES;
        $selectedRole = $forcedRole;
        if (! $selectedRole) {
            $requested = $request->get('pt');
            $selectedRole = in_array($requested, $ptOptions, true) ? $requested : $ptOptions[0];
        }

        $form = ApupptFeedbackForm::firstOrCreate(
            ['apuppt_pt_scope' => $selectedRole],
            ['title' => 'Kuesioner Feedback APUPPT', 'is_active' => false]
        );
        $form->load('questions');

        return view('apuppt.feedback.edit', [
            'form' => $form,
            'forcedRole' => $forcedRole,
            'ptOptions' => $ptOptions,
            'selectedRole' => $selectedRole,
        ]);
    }

    public function update(Request $request, ApupptFeedbackForm $form)
    {
        $forcedRole = $this->resolveForcedRole();
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'is_active' => 'required|in:0,1',
        ]);

        $form->update($data);

        return back()->with('success', 'Kuesioner berhasil diperbarui.');
    }

    public function questionStore(Request $request, ApupptFeedbackForm $form)
    {
        $forcedRole = $this->resolveForcedRole();
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }

        $data = $request->validate([
            'question_text' => 'required|string|max:1000',
            'type' => 'required|in:rating,essay',
            'is_required' => 'nullable|boolean',
        ]);

        $form->questions()->create([
            'question_text' => $data['question_text'],
            'type' => $data['type'],
            'is_required' => $request->boolean('is_required'),
            'order' => ((int) $form->questions()->max('order')) + 1,
        ]);

        return back()->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function questionUpdate(Request $request, ApupptFeedbackForm $form, ApupptFeedbackQuestion $question)
    {
        $forcedRole = $this->resolveForcedRole();
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }
        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $data = $request->validate([
            'question_text' => 'required|string|max:1000',
            'type' => 'required|in:rating,essay',
            'is_required' => 'nullable|boolean',
        ]);

        $question->update([
            'question_text' => $data['question_text'],
            'type' => $data['type'],
            'is_required' => $request->boolean('is_required'),
        ]);

        return back()->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function questionDestroy(ApupptFeedbackForm $form, ApupptFeedbackQuestion $question)
    {
        $forcedRole = $this->resolveForcedRole();
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }
        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $question->delete();

        return back()->with('success', 'Pertanyaan berhasil dihapus.');
    }

    public function questionMove(Request $request, ApupptFeedbackForm $form, ApupptFeedbackQuestion $question)
    {
        $forcedRole = $this->resolveForcedRole();
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }
        if ($question->form_id !== $form->id) {
            abort(404);
        }

        $direction = $request->get('direction');
        $questions = $form->questions()->get();
        $index = $questions->search(fn ($q) => $q->id === $question->id);

        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;
        if ($swapWith < 0 || $swapWith >= $questions->count()) {
            return back();
        }

        $other = $questions[$swapWith];
        $ownOrder = $question->order;
        $question->update(['order' => $other->order]);
        $other->update(['order' => $ownOrder]);

        return back();
    }

    public function report(Request $request, ApupptFeedbackForm $form)
    {
        $authUser = auth()->user();
        $forcedRole = $this->resolveForcedRole();
        if ($authUser->isApupptAdmin() && ! $forcedRole) {
            abort(403, 'PT scope untuk Admin APUPPT belum diatur.');
        }
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }

        $form->load('questions');

        $responses = $form->responses()
            ->with(['user:id,name,email,cabang', 'answers'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', '%' . $request->q . '%'));
            })
            ->when($request->filled('cabang'), function ($q) use ($request) {
                $q->whereHas('user', fn ($u) => $u->where('cabang', $request->cabang));
            })
            ->latest('submitted_at')
            ->paginate(20)
            ->withQueryString();

        $ratingQuestions = $form->questions->where('type', 'rating');
        $ratingAverages = $ratingQuestions->mapWithKeys(function ($question) {
            $avg = $question->answers()->whereNotNull('rating_value')->avg('rating_value');

            return [$question->id => $avg ? round($avg, 2) : null];
        });

        $totalResponses = $form->responses()->count();

        return view('apuppt.feedback.report', [
            'form' => $form,
            'responses' => $responses,
            'ratingAverages' => $ratingAverages,
            'totalResponses' => $totalResponses,
        ]);
    }

    public function reportExport(Request $request, ApupptFeedbackForm $form)
    {
        $forcedRole = $this->resolveForcedRole();
        if ($forcedRole && $form->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }

        $form->load('questions');
        $responses = $form->responses()->with(['user:id,name,email,cabang', 'answers'])->get();

        $filename = 'apuppt-feedback-' . $form->apuppt_pt_scope . '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($responses, $form) {
            $out = fopen('php://output', 'w');
            $header = ['No', 'Nama', 'Email', 'Cabang'];
            foreach ($form->questions as $question) {
                $header[] = $question->question_text;
            }
            fputcsv($out, $header, ';');

            foreach ($responses as $i => $response) {
                $row = [
                    $i + 1,
                    optional($response->user)->name,
                    optional($response->user)->email,
                    optional($response->user)->cabang,
                ];
                foreach ($form->questions as $question) {
                    $answer = $response->answers->firstWhere('question_id', $question->id);
                    $row[] = $question->type === 'rating'
                        ? optional($answer)->rating_value
                        : optional($answer)->answer_text;
                }
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
