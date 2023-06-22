<?php

namespace App\Repositories\CommunityGuideline;

use Illuminate\Http\Request;

interface CommunityGuidelineInterface
{
    public function index();

    public function store(Request $request);

    public function show($id);

    public function update(Request $request, $id);

    public function destroy($id);

    public function getCommunityGuidelines();
}
