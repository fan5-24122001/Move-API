<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\FeaturedVideos\FeaturedVideosInterface;

class FeaturedVideosController extends Controller
{
    private FeaturedVideosInterface $featuredVideosRepository;

    public function __construct(FeaturedVideosInterface $featuredVideosRepository)
    {
        $this->featuredVideosRepository = $featuredVideosRepository;
    }

    public function index()
    {
        return $this->featuredVideosRepository->index();
    }

    public function edit(string $id)
    {
        $categories = Category::where('show', '=', 1)->get();
        $video = $this->featuredVideosRepository->edit($id);
        $url = $video->url_video;
        $urlvideo = urlVideo($url);
        return view('featured_videos.edit', compact('video', 'categories', 'urlvideo'));
    }

    public function update(Request $request, string $id)
    {
        $video = $this->featuredVideosRepository->update($request, $id);
        return redirect()->route('featured-videos.index')->with('success', 'Updated successfully.');
    }

    public function destroy(string $id)
    {
        $video = $this->featuredVideosRepository->destroy($id);
        return redirect()->route('featured-videos.index')->with('success', 'Delete successfully.');
    }
}
