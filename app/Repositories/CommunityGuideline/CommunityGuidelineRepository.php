<?php

namespace App\Repositories\CommunityGuideline;

use App\Models\CommunityGuideline;
use App\Models\FAQ;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class CommunityGuidelineRepository implements CommunityGuidelineInterface
{
    use JsonResponseTrait;

    private CommunityGuideline $communityGuideline;

    public function __construct(CommunityGuideline $communityGuideline)
    {
        $this->communityGuideline = $communityGuideline;
    }

    public function index()
    {
        $communityGuidelines = $this->communityGuideline->all();
        return view('community_guidelines.index', compact('communityGuidelines'));
    }

    public function store(Request $request)
    {
        return $this->communityGuideline->create([
            'title' => $request->title,
            'content' => $request->content,
        ]);
    }

    public function show($id)
    {
        return $this->communityGuideline->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $communityGuideline = $this->communityGuideline->find($id);
        if ($communityGuideline) {
            $communityGuideline->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
            return $communityGuideline;
        }
    }

    public function destroy($id)
    {
        return $this->communityGuideline->destroy($id);
    }

    public function getCommunityGuidelines()
    {
        $result = $this->communityGuideline->all();
        return $this->result($result, 200, true);
    }
}
