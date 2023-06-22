<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Repositories\VideosAdmin\VideosAdminInterface;

class AdminVideosController extends Controller
{
    private VideosAdminInterface $videosAdminRepository;

    public function __construct(VideosAdminInterface $videosAdminRepository)
    {
        $this->videosAdminRepository = $videosAdminRepository;
    }

    public function index(Request $request)
    {
        $key = $request->input('key');

        return $this->videosAdminRepository->index($key);
    }
    
    public function show(string $id)
    {
        $categories = Category::all();
        $video = $this->videosAdminRepository->show($id);
        $url = $video->url_video;
        $urlvideo = urlVideo($url);
        return view('videos.show', compact('video', 'categories', 'urlvideo'));
    }

    public function edit(string $id)
    {
        $categories = Category::where('show', '=', 1)->get();
        $video = $this->videosAdminRepository->edit($id);
        $url = $video->url_video;
        $urlvideo = urlVideo($url);
        return view('videos.edit', compact('video', 'categories', 'urlvideo'));
    }

    public function update(UpdateVideoRequest $request, string $id)
    {
        $video = $this->videosAdminRepository->update($request, $id);
        return redirect()->route('videos-manager.index')->with('success', 'Updated successfully.');
    }

    public function destroy(string $id)
    {
        $video = $this->videosAdminRepository->destroy($id);
        return redirect()->route('videos-manager.index')->with('success', 'Delete successfully.');
    }
}
