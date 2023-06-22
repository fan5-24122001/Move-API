<?php

namespace App\Repositories\FeaturedVideos;

interface FeaturedVideosInterface
{
    public function index();
    public function edit($id);
    public function update($request, $id);
    public function destroy($id);
}
