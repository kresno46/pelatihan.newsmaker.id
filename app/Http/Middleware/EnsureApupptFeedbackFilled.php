<?php

namespace App\Http\Middleware;

use App\Models\ApupptCertificateAward;
use App\Models\ApupptFeedbackForm;
use App\Models\ApupptFeedbackResponse;
use Closure;
use Illuminate\Http\Request;

class EnsureApupptFeedbackFilled
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (! $user || in_array($user->role, ['Admin', 'Admin APUPPT'], true)) {
            return $next($request);
        }

        if ($request->routeIs('apuppt.feedbackUser.*')) {
            return $next($request);
        }

        $form = ApupptFeedbackForm::where('apuppt_pt_scope', $user->role)
            ->where('is_active', true)
            ->first();

        if (! $form) {
            return $next($request);
        }

        $alreadyFilled = ApupptFeedbackResponse::where('form_id', $form->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyFilled) {
            return $next($request);
        }

        $hasCertificate = ApupptCertificateAward::where('user_id', $user->id)
            ->whereHas('postTestResult.session', function ($q) use ($user) {
                $q->where('apuppt_pt_scope', $user->role)->where('tipe', 'APUPPT');
            })
            ->exists();

        if (! $hasCertificate) {
            return $next($request);
        }

        return redirect()->route('apuppt.feedbackUser.create')
            ->with('error', 'Anda sudah mengunduh sertifikat APUPPT. Silakan isi kuesioner feedback terlebih dahulu.');
    }
}
