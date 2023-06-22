<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Repositories\Tag\TagInterface;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private TagInterface $tagRepository;

    public function __construct(TagInterface $tagRepository) 
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = $this->tagRepository->index();
        return view('tags.index', compact('tags'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->tagRepository->store($request);
        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);
        return view('tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->tagRepository->update($request, $id);
        return redirect()->route('tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->tagRepository->destroy($id);
        return redirect()->route('tags.index')
        ->with('success', 'Tag deleted successfully.');
    }
}
