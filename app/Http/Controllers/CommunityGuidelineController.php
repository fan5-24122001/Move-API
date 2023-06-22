<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommunityGuidelineRequest;
use App\Repositories\CommunityGuideline\CommunityGuidelineRepository;

class CommunityGuidelineController extends Controller
{
    private CommunityGuidelineRepository $communityGuidelineResponsitory;

    public function __construct(CommunityGuidelineRepository $communityGuidelineResponsitory)
    {
        $this->communityGuidelineResponsitory = $communityGuidelineResponsitory;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->communityGuidelineResponsitory->index();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('community_guidelines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCommunityGuidelineRequest $request)
    {
        $this->communityGuidelineResponsitory->store($request);
        return redirect()->route('community-guidelines.index')->with('success', 'Add new CG success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $communityGuideline =  $this->communityGuidelineResponsitory->show($id);
        return view('community_guidelines.show', compact('communityGuideline'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $communityGuideline =  $this->communityGuidelineResponsitory->show($id);
        return view('community_guidelines.edit', compact('communityGuideline'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCommunityGuidelineRequest $request, string $id)
    {
        $this->communityGuidelineResponsitory->update($request, $id);
        return redirect()->route('community-guidelines.index')->with('success', 'Update CG success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->communityGuidelineResponsitory->destroy($id);
        return redirect()->route('community-guidelines.index')->with('success', 'Delete CG success');
    }
}
