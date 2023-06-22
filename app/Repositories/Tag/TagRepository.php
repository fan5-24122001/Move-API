<?php

namespace App\Repositories\Tag;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagRepository implements TagInterface
{
    private Tag $tag;
    public function __construct(Tag $tag) 
    {
        $this->tag = $tag;
    }

    public function index(){
        return $this->tag->paginate(10);
    }

    public function store(Request $request)
    {
        return $this->tag->create([
            'keyword' => $request->keyword,
        ]);
    }

    public function show($id)
    {
        return $this->tag->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $tag = $this->tag->find($id);
        if($tag){
            $tag->update([
                'keyword' => $request->keyword,
            ]);
            return $tag;
        }
    }

    public function destroy($id)
    {
        return $this->tag->destroy($id);
    }
}
