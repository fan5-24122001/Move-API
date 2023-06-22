<?php

namespace App\Repositories\Category;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Support\Facades\File;

class CategoryRepository implements CategoryInterface
{
    private Category $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        return $this->category->paginate(5);
    }

    public function show($id)
    {
        return $this->category->findOrFail($id);
    }

    public function store(CategoryRequest $request){
        $path = url('');
        $imageName = $path.'/image_categories/'.time().'.'.$request->img->extension();  
        $request->img->move(public_path('image_categories'), $imageName);
        return $this->category->create([
            'name' => $request->name,
            'img' => $imageName,
            'show' => $request->show,
        ]);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $path = url('');
        $category = $this->category->find($id);

        if($request->has('img')){
            $imgUpdate = $category->img;
            $explodePathImage = explode($path.'/',$imgUpdate);
            File::delete(public_path("$explodePathImage[1]"));

            $imageName = $path.'/image_categories/'.time().'.'.$request->img->extension();  
            $request->img->move(public_path('image_categories'), $imageName);

            return $category->update([
                'name' => $request->name,
                'img' => $imageName,
                'show' => $request->show,
            ]);
        }
        else {
            return $category->update([
                'name' => $request->name,
                'show' => $request->show,
            ]);
        }
    }

    public function destroy($id)
    {
        $this->category->destroy($id);
    }
}
