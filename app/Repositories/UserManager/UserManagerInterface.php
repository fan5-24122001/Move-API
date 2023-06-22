<?php

namespace App\Repositories\UserManager;

use Illuminate\Http\Request;

interface UserManagerInterface
{
    public function index($key);

    public function show($id);

    public function update(Request $request, $id);
}
