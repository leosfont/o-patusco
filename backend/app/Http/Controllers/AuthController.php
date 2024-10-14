<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessLinkRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoginTokenMail;

class AuthController extends Controller
{
    public function requestAccessLink(AccessLinkRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;

            Mail::to($user->email)->send(new LoginTokenMail($user, $token));

            return response()->json([
                'message' => 'Access link sent to your email.'
            ], 200);
        }

        return response()->json([
            'message' => 'User with this email not found.'
        ], 404);
    }
}
