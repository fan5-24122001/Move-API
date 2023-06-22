<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserInfoResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('email.exists', ['only' => 'login']);
        $this->middleware('checkVerified', ['only' => 'login']);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user || !Hash::check($request->password, $user->password)) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Incorrect password, please try again!',
                    'status_code' => 400,
                ],
                400
            );
        }
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $country_id = null;
        $state_id = null;

        $userAddress = $user->user_address()->first();

        if($userAddress){
            $country_id = $userAddress->country_id;
            $state_id = $userAddress->state_id;
        }

        $userInfoResource = new UserInfoResource($user);

        $temp['country_id'] = $country_id;
        $temp['state_id'] = $state_id;
        
        $result = array_merge($userInfoResource->toArray(request()), $temp);
        
        return new JsonResponse(
            [
                'success' => true,
                'message' => 'Login successfully!',
                'token' => $token,
                'data' => $result,
                'status_code' => 200,
            ],
            200
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return new JsonResponse(
            [
                'success' => true,
                'message' => 'Logged out successfully'
            ],
            200
        );
    }
}
