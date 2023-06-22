<?php

namespace App\Repositories\UserProfile;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\AddressResource;
use App\Http\Resources\InformationByIdResource;
use App\Http\Resources\UserInfoResource;
use App\Models\Country;
use App\Models\Follower;
use App\Models\State;
use App\Models\User;
use App\Models\UserAddress;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserProfileRepository implements UserProfileInterface
{
    use JsonResponseTrait;

    private Country $country;
    private State $state;
    private User $user;
    private UserAddress $userAddress;
    private Follower $follower;

    public function __construct(Country $country, State $state, User $user, UserAddress $userAddress, Follower $follower)
    {
        $this->country = $country;
        $this->state = $state;
        $this->user = $user;
        $this->userAddress = $userAddress;
        $this->follower = $follower;
    }

    public function updataProfile(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = $this->user->findOrFail(auth()->user()->id);

                $userAddress = $user->user_address()->first();

                $user->update($request->only([
                    'img',
                    'username',
                    'fullname',
                    'gender',
                    'birthday',
                    'address',
                ]));

                if ($userAddress) {
                    $userAddress->update([
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                    ]);
                } else {
                    $this->userAddress->create([
                        'user_id' => auth()->user()->id,
                        'country_id' => $request->country_id,
                        'state_id' => $request->state_id,
                    ]);
                }
            });
            return new JsonResponse([
                'success' => true,
                'message' => 'Update profile Successful',
                'status_code' => 201,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Fail: ' . $e->getMessage()]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $this->user->findOrFail(auth()->user()->id);

        if (!Hash::check($request->current_password, $user->password)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Current password Invalid!',
                'status_code' => 400,
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return new JsonResponse([
            'success' => true,
            'message' => 'Change password Successful',
            'status_code' => 201,
        ], 201);
    }

    public function settingNotification(Request $request)
    {
        $user = $this->user->findOrFail(auth()->user()->id);

        $user->status_all_notification = $request->input('status_all_notification', 1);

        if ($user->status_all_notification) {
            $user->status_comment_notification = $request->status_comment_notification;
            $user->status_follow_notification = $request->status_follow_notification;
        } else {
            $user->status_comment_notification = 0;
            $user->status_follow_notification = 0;
        }

        $user->save();
        
        return new JsonResponse([
            'success' => true,
            'message' => 'Update Notification Successful',
            'status' => 201,
        ], 201);
    }

    public function getInformation()
    {
        $user = $this->user->where('id', auth()->user()->id)->first();
        $userAddress = $user->user_address()->first();

        $userInfoResource = new UserInfoResource($user);
        $userAddressResource = new AddressResource($userAddress);

        if ($userAddress) {
            $infoUser = array_merge($userInfoResource->toArray(request()), $userAddressResource->toArray(request()));
            return $this->result($infoUser, 200, true);
        }

        return $this->result($userInfoResource, 200, true);
    }

    public function getInformationById(Request $request, $id)
    {
        $header = $request->header('Authorization');
        $isFollowing = 0;

        if ($header) {
            $followed = $this->follower->where('user_id', Auth::guard('sanctum')->user()->id)->where('followed_user_id', $id)->get();
            if (sizeof($followed) >= 1) {
                $isFollowing = 1;
            }
        }

        $totalFollowed = $this->follower->where('followed_user_id', $id)->count();
        $user = $this->user->where('id', $id)->first();

        $userInfoArray = (new InformationByIdResource($user))->toArray($request);
        $userInfoArray['is_following'] = $isFollowing;
        $userInfoArray['followed_num'] = $totalFollowed;

        return $this->result($userInfoArray, 200, true);
    }


    public function createUsername(Request $request)
    {
        $user = $this->user->findOrFail(auth()->user()->id);

        if($request->has('username')){
            $user->update([
                'username' => $request->username,
            ]);

            return new JsonResponse([
                'success' => true,
                'message' => 'You create username successfull! ',
                'username' => $user->username,
                'status_code' => 201,
            ], 201);
        }
    }

    public function getStatusNotification()
    {
        $status =  $this->user->select('status_all_notification', 'status_follow_notification', 'status_comment_notification')
                            ->where('id', auth()->user()->id)->get();
        
        return $this->result($status, 200, true);
    }
}
