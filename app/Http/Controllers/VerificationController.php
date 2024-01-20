<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VerificationController extends Controller
{


    public function verify(Request $request)
    {
        $user = $request->user();

        if ($request->verification_code == $user->verification_code) {
            $user->email_verified_at = now();
            $user->verification_code = null;
            $user->save();

            return Response::json(['status' => 'Email verified successfully']);
        } else {
            return Response::json(['error' => 'Invalid verification code. Please try again.'], 422);
        }
    }
}
