<?php

namespace App\Repositories\Category;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

interface CategoryInterface
{
    public function index();
    public function show($id);
    public function store(CategoryRequest $request);
    public function update(UpdateCategoryRequest $request, $id);
    public function destroy($id);
}
