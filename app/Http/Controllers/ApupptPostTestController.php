<?php

namespace App\Http\Controllers;

use App\Models\ApupptPostTestQuestion;
use App\Models\ApupptPostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ApupptPostTestController extends Controller
{
    private function authorizeScopedSession(ApupptPostTestSession $session): void
    {
        $authUser = auth()->user();
        $forcedRole = $authUser && $authUser->isApupptAdmin() ? $authUser->apuppt_pt_scope : null;
        if ($forcedRole && $session->apuppt_pt_scope !== $forcedRole) {
            abort(403);
        }
    }

    public function questionStore(Request $request, ApupptPostTestSession $session)
    {
        $this->authorizeScopedSession($session);
        $v = Validator::make($request->all(), [
            'question_text' => ['required', 'string', 'max:10000'],
            'option_a' => ['required', 'string', 'max:1000'],
            'option_b' => ['required', 'string', 'max:1000'],
            'option_c' => ['required', 'string', 'max:1000'],
            'option_d' => ['required', 'string', 'max:1000'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ]);

        $data = $v->validateWithBag('createQuestion');
        $clean = Purifier::clean($data['question_text'], 'default');

        $session->questions()->create([
            'question' => $clean,
            'option_a' => $data['option_a'],
            'option_b' => $data['option_b'],
            'option_c' => $data['option_c'],
            'option_d' => $data['option_d'],
            'correct_option' => $data['correct_option'],
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function questionUpdate(Request $request, ApupptPostTestQuestion $question)
    {
        $this->authorizeScopedSession($question->session);
        $v = Validator::make($request->all(), [
            'question_text' => ['required', 'string', 'max:10000'],
            'option_a' => ['required', 'string', 'max:1000'],
            'option_b' => ['required', 'string', 'max:1000'],
            'option_c' => ['required', 'string', 'max:1000'],
            'option_d' => ['required', 'string', 'max:1000'],
            'correct_option' => ['required', 'in:A,B,C,D'],
        ]);

        $data = $v->validateWithBag('updateQuestion');
        $clean = Purifier::clean($data['question_text'], 'default');

        $question->update([
            'question' => $clean,
            'option_a' => $data['option_a'],
            'option_b' => $data['option_b'],
            'option_c' => $data['option_c'],
            'option_d' => $data['option_d'],
            'correct_option' => $data['correct_option'],
        ]);

        return back()->with('success', 'Soal berhasil diperbarui.');
    }

    public function questionDestroy(ApupptPostTestQuestion $question)
    {
        $this->authorizeScopedSession($question->session);
        $question->delete();

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    public function toggleStatus($slug)
    {
        $postTest = ApupptPostTestSession::where('slug', $slug)->firstOrFail();
        $this->authorizeScopedSession($postTest);
        $postTest->status = ! $postTest->status;
        $postTest->save();

        return redirect()->route('apuppt.posttest.index')->with('alert', 'Status updated successfully!');
    }
}
