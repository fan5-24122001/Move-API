<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function __construct(){
        $this->middleware('email.exists');
    }
    
    public function fogortPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json(['message' => "We've sent an email to $request->email. Click the link in the email to reset your password."], JsonResponse::HTTP_OK);
        } else {
            return response()->json(['message' => "Email has been sent to your email, please check your email!!!"], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'token' => $request->token,
        ];

        $response = $this->broker()->reset($credentials, function ($user, $password) {
            $this->resetPW($user, $password);
        });

        if ($response == Password::PASSWORD_RESET) {
            DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->delete();
            return response()->json(['message' => 'Password was reset successfully'], JsonResponse::HTTP_OK);
        } else {
            return response()->json(['message' => 'The token is incorrect or has expired. Could not reset password'], JsonResponse::HTTP_BAD_REQUEST);
        }
    }

    public function broker() {
        return Password::broker('users');
    }

    protected function resetPW($user, $password) {
        $user->password = bcrypt($password);
        $user->save();
        event(new PasswordReset($user));
    }

}
