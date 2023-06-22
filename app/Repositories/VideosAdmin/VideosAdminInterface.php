<?php

namespace App\Repositories\VideosAdmin;

use App\Http\Requests\Videos\UpdateVideoRequest;


interface VideosAdminInterface
{
    public function index($key);
    public function show($id);
    public function edit($id);
    public function update(UpdateVideoRequest $request, $id);
    public function destroy($id);
}
