<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Repositories\UserProfile\UserProfileRepository;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    use JsonResponseTrait;

    private UserProfileRepository $userProfileRepository;

    public function __construct(UserProfileRepository $userProfileRepository)
    {
        $this->userProfileRepository = $userProfileRepository;
        $this->middleware('checkUsername')->only('updateProfile', 'createUsername');
        $this->middleware('findUser')->only('getInformationById');
    }

    public function updateProfile(Request $request)
    {
        return $this->userProfileRepository->updataProfile($request);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->userProfileRepository->changePassword($request);
    }

    public function settingNotification(Request $request)
    {
        return $this->userProfileRepository->settingNotification($request);
    }

    public function getInformation()
    {
        return $this->userProfileRepository->getInformation();
    }

    public function getInformationById(Request $request, $id){
        return $this->userProfileRepository->getInformationById($request, $id);
    }

    public function createUsername(Request $request)
    {
        return $this->userProfileRepository->createUsername($request);
    }

    public function getStatusNotification()
    {
        return $this->userProfileRepository->getStatusNotification();
    }
}
