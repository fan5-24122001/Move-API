<?php

namespace App\Repositories\VideosUser;

use Illuminate\Support\Facades\Request;
use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Http\Requests\Videos\CreateVideosRequest;

interface VideosUserRepositoryInterface
{
    public function index();
    public function store(CreateVideosRequest $request);
    public function show($id);
    public function update(UpdateVideoRequest $request, $id);
}
