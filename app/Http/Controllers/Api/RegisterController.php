<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('email.exists', ['except' => 'register']);
        $this->middleware('email.verify', ['except' => 'register']);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
        ]);

        if ($user) {
            $pin = rand(100000, 999999);
            DB::table('password_reset_tokens')
                ->insert(
                    [
                        'email' => $request->email,
                        'token' => $pin
                    ]
                );
        }

        Mail::to($request->email)->send(new VerifyEmail($pin));

        $token = $user->createToken('myapptoken')->plainTextToken;

        return new JsonResponse(
            [
                'success' => true,
                'message' => 'Successful created user. Please check your email for a 6-digit pin to verify your email.',
                'status_code' => 201,
            ],
            201
        );
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['message' => $validator->errors()]);
        }

        $user = User::where('email', $request->email)->get();
        if (sizeof($user) > 0) {
            $email_verified = User::where('email', $request->email)->where('email_verified_at', NULL)->get();
            if (sizeof($email_verified) > 0) {
                $select = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token);

                if ($select->get()->isEmpty()) {
                    return new JsonResponse([
                        'success' => false,
                        'message' => "Invalid PIN",
                        'status_code' => 400,
                    ], 400);
                }

                $select = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->delete();

                $user = User::where('email', $request->email)->first();
                
                $code = random_int(100000, 999999);

                $user->update([
                    'email_verified_at' => Carbon::now()->getTimestamp(),
                    'username' => 'user'.$code,
                ]);

                return new JsonResponse([
                    'success' => true,
                    'message' => "Your email is verified successfully. Hope that you'll have interesting experiences on MOVE",
                    'status_code' => 200,
                ], 200);
            } else {
                return new JsonResponse([
                    'success' => false,
                    'message' => "The email has been verified before, please use another email to create a new account",
                    'status_code' => 400,
                ], 400);
            }
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => "We can't find your email $request->email",
                'status_code' => 404,
            ], 404);
        }
    }

    public function resendPin(Request $request)
    {
        $verify =  DB::table('password_reset_tokens')->where('email', $request->email);

        if ($verify->exists()) {
            $verify->delete();
        }

        $token = random_int(100000, 999999);
        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' =>  $token,
            'created_at' => Carbon::now()
        ]);

        if ($password_reset) {
            Mail::to($request->email)->send(new VerifyEmail($token));

            return new JsonResponse(
                [
                    'success' => true,
                    'message' => "We ve resent an email to $request->email, please check your mail.",
                    'status' => 200,
                ],
                200
            );
        }
    }
}
