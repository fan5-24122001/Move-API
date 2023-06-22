<?php 

namespace App\Repositories\UserProfile;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;

interface UserProfileInterface
{
    public function updataProfile(Request $request);

    public function changePassword(ChangePasswordRequest $request);
    
    public function settingNotification(Request $request);

    public function getInformation();

    public function getInformationById(Request $request, $id);
    
    public function createUsername(Request $request);

    public function getStatusNotification();
}
