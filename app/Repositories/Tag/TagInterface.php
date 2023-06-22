<?php

namespace App\Repositories\Tag;

use Illuminate\Http\Request;

interface TagInterface
{
    public function index();
    public function store(Request $request);
    public function show($id);
    public function update(Request $request, $id);
    public function destroy($id);
}
