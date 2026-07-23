<?php

namespace App\Http\Controllers;

use App\Models\ApupptFeedbackForm;
use App\Models\ApupptFeedbackResponse;
use Illuminate\Http\Request;

class ApupptFeedbackUserController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        $form = ApupptFeedbackForm::where('apuppt_pt_scope', $user->role)
            ->where('is_active', true)
            ->first();

        if (! $form) {
            return redirect()->route('dashboard')->with('info', 'Belum ada kuesioner feedback untuk PT Anda.');
        }

        $alreadyFilled = ApupptFeedbackResponse::where('form_id', $form->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyFilled) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah mengisi kuesioner feedback ini. Terima kasih!');
        }

        $form->load('questions');

        return view('apuppt.feedback-user.create', compact('form'));
    }

    public function store(Request $request, ApupptFeedbackForm $form)
    {
        $user = auth()->user();

        if ($form->apuppt_pt_scope !== $user->role || ! $form->is_active) {
            abort(403);
        }

        $alreadyFilled = ApupptFeedbackResponse::where('form_id', $form->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyFilled) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah mengisi kuesioner feedback ini.');
        }

        $form->load('questions');

        $rules = [];
        foreach ($form->questions as $question) {
            $key = "answers.{$question->id}";
            if ($question->type === 'rating') {
                $rules[$key] = ($question->is_required ? 'required' : 'nullable') . '|integer|min:1|max:5';
            } else {
                $rules[$key] = ($question->is_required ? 'required' : 'nullable') . '|string|max:2000';
            }
        }

        $validated = $request->validate($rules);

        $response = ApupptFeedbackResponse::create([
            'form_id' => $form->id,
            'user_id' => $user->id,
            'submitted_at' => now(),
        ]);

        foreach ($form->questions as $question) {
            $value = $validated['answers'][$question->id] ?? null;

            $response->answers()->create([
                'question_id' => $question->id,
                'rating_value' => $question->type === 'rating' ? $value : null,
                'answer_text' => $question->type === 'essay' ? $value : null,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Terima kasih telah mengisi kuesioner feedback APUPPT!');
    }
}
