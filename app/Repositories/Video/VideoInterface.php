<?php

namespace App\Repositories\Video;

use Illuminate\Http\Request;

interface VideoInterface
{
    public function getLatestVideoOfUser();

    public function getVideoByIdOfUser($id);

    public function getViewVideoByGender(Request $request, $id);

    public function getViewVideoByCountry(Request $request,$id);

    public function getViewVideoByState(Request $request,$id, $countryID);

    public function getViewVideoByAgeRanger(Request $request, $id);

    public function getVideosWatchAlso($id);
}
