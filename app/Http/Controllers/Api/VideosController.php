<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Videos\UpdateVideoRequest;
use App\Http\Requests\Videos\CreateVideosRequest;
use App\Models\Tag;
use App\Repositories\Video\VideoRepository;
use App\Repositories\VideosUser\VideosUserRepositoryInterface;
use App\Traits\CheckExistsTrait;

class VideosController extends Controller
{
    use JsonResponseTrait;
    use CheckExistsTrait;

    protected $videosUserRepository;
    private VideoRepository $videoRepository;

    public function __construct(VideosUserRepositoryInterface $videosUserRepository, VideoRepository $videoRepository)
    {
        $this->videosUserRepository = $videosUserRepository;
        $this->videoRepository = $videoRepository;
        $this->middleware('findVideo', ['only' => ['update', 'getVideosWatchAlso']]);
    }

    public function index(Request $request)
    {
        $videos = $this->videosUserRepository->index();
        $videoList = [];
        foreach ($videos as $video) {
            $videoStats = DB::table('videos')
                ->leftJoin('view_videos', 'videos.id', '=', 'view_videos.video_id')
                ->leftJoin('comments', 'videos.id', '=', 'comments.video_id')
                ->leftJoin('rates', 'videos.id', '=', 'rates.video_id')
                ->leftJoin('categories', 'videos.category_id', '=', 'categories.id')
                ->select(
                    'videos.id',
                       'categories.name',
                    DB::raw('COUNT(view_videos.id) as view_count'),
                    DB::raw('SUM(view_videos.time) as total_view_time'),
                    DB::raw('COUNT(comments.id) as comment_count'),
                    DB::raw('AVG(rates.rate) as average_rating')
                )
                ->groupBy('videos.id', 'categories.name')
                ->where('videos.id', $video->id)
                ->first();
            $viewCount = $videoStats->view_count ?? 0;
            $commentCount = $videoStats->comment_count ?? 0;
            $averageRating = $videoStats->average_rating ?? 0;
            $categoryName = $videoStats->name ?? null;
            $roundedRatingCount = round($averageRating, 1);
            $videoDuration = $video->file_duration;
            $totalViewTime = DB::table('view_videos')->where('video_id', $video->id)->get();
            $limitedViewTime = 0;
            $totalRecords = count($totalViewTime);

            foreach ($totalViewTime as $viewTime) {
                $limitedViewTime += min($viewTime->time, $videoDuration);
            }
            $avgViewTime = $totalRecords != 0 ? $limitedViewTime / $totalRecords : 0;
            $avgViewTime = round($avgViewTime);
            if ($videoDuration != 0 && $limitedViewTime != 0) {
                $averagePercentageWatched = ($avgViewTime / $videoDuration) * 100;
            } else {
                $averagePercentageWatched = 0;
            }
            $roundedPercentageWatched = round($averagePercentageWatched, 1);

            $videoList[] = [
                'video' => $video,
                'categoryName' => $categoryName,
                'created_at' => $video->created_at,
                'ratings' => $roundedRatingCount,
                'views' => $viewCount,
                'comment' => $commentCount,
                'avg_view_time' => $avgViewTime,
                'percent_view_time' => $roundedPercentageWatched,
            ];

        }

        $sort = $request->input('sort');
        $sortDirection = $request->input('sortDirection');

        if ($sortDirection && in_array($sortDirection, ['asc', 'desc'])) {
            $sortedVideoList = collect($videoList)->sortBy($sort, SORT_REGULAR, $sortDirection === 'desc')->values()->all();
            $videoList = $sortedVideoList;
        }

        $filterDuration = $request->input('filterDuration');

        if ($filterDuration && in_array($filterDuration, ['7', '30', '90', '365'])) {
            $now = Carbon::now();
            $startDate = $now->subDays($filterDuration)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $videoList = array_filter($videoList, function ($item) use ($startDate, $endDate) {
                $createdAt = Carbon::parse($item['created_at']);
                return $createdAt->between($startDate, $endDate);
            });
            $videoList = array_values($videoList);
        }
        return $this->result($videoList, 200, true);
    }

    public function store(CreateVideosRequest $request)
    {
        $video = $this->videosUserRepository->store($request);
        $status = $video ? 200 : 400;
        $message = $video ? 'Successful Create' : 'Fail Create';
        return $this->result($video, $status, $message);
    }

    public function show($id)
    {
        $video = $this->videosUserRepository->show($id);
        if ($video) {
            $video->url_video = urlVideo($video->url_video);
            return $this->result($video, 200, true);
        } else {
            return $this->result([], 404, 'No data found');
        }
    }

    public function update(UpdateVideoRequest $request, $id)
    {
        $video = $this->videosUserRepository->update($request, $id);
        if ($video->file_status === 'uploading') {
            if (!$request->has('thumbnail')) {
                return $this->result($video, 400, 'Thumbnail is required for uploading status');
            }
            $thumbnail = $request->thumbnail;
            $createImg = imgCreate($request, $thumbnail);
            $videoData = $request->only(['title', 'category_id', 'level', 'duration', 'commentable']);
            $videoData['thumbnail'] = $createImg;
            $videoData['file_status'] = 'done';
            $hashTags = $request->input('tag');

            $existingTags = $video->tags()->pluck('keyword')->toArray();

            if ($hashTags) {
                $tags = explode(',', $hashTags);

                $videoTags = [];
                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);
                    if (!in_array($tagName, $existingTags)) {
                        $tag = Tag::firstOrCreate(['keyword' => $tagName]);
                        $video->tags()->attach($tag->id);
                        $videoTags[] = $tagName;
                    } elseif (!in_array($tagName, $videoTags)) {
                        $videoTags[] = $tagName;
                    }
                }
                $tagsToDelete = array_diff($existingTags, $videoTags);
                $tagsToDeleteIds = Tag::whereIn('keyword', $tagsToDelete)->pluck('id')->toArray();
                $video->tags()->detach($tagsToDeleteIds);

                $video->tag = implode(',', $videoTags);
                $result['tags'] = $videoTags;
            }
            $video->update($videoData);
            $video = $video->fresh(); // Refresh the video model to get the updated data

            return $this->result($video, 200, 'Successful update');
        }  elseif ($video->file_status === 'done') {
            if ($request->has('thumbnail')) {
                imageDelete($video->thumbnail);
                $video->thumbnail = imgCreate($request, $request->thumbnail);
            }
            $hashTags = $request->input('tag');
            $existingTags = $video->tags()->pluck('keyword')->toArray();
        
            if ($hashTags !== null) {
                $tags = explode(',', $hashTags);
                $videoTags = [];
        
                foreach ($tags as $tagName) {
                    $tagName = trim($tagName);
                    if (!in_array($tagName, $existingTags)) {
                        $tag = Tag::firstOrCreate(['keyword' => $tagName]);
                        $video->tags()->attach($tag->id);
                        $videoTags[] = $tagName;
                    } elseif (!in_array($tagName, $videoTags)) {
                        $videoTags[] = $tagName;
                    }
                }
        
                $tagsToDelete = array_diff($existingTags, $videoTags);
                $tagsToDeleteIds = Tag::whereIn('keyword', $tagsToDelete)->pluck('id')->toArray();
                $video->tags()->detach($tagsToDeleteIds);
        
                $video->tag = implode(',', $videoTags);
                $result['tags'] = $videoTags;
            } else {
                $video->tags()->detach();
                $video->tag = null;
                $result['tags'] = [];
            }
        
            $video->update($request->except(['thumbnail', 'tag']));
            $video = $video->fresh(); 
            return $this->result($video, 200, 'Successful update');
        } else {
            return $this->result($video, 404, 'No data found');
        }
    }

    public function deleteVideos(Request $request)
    {
        $id = $request->input('id');
        $user = Auth::guard('sanctum')->user();

        if (empty($id)) {
            return $this->result([], 200, 'No ID');
        }

        $invalidIds = [];
        foreach ($id as $videoId) {
            $record = Video::find($videoId);
            if (!$record || $record->user_id !== $user->id) {
                $invalidIds[] = $videoId;
            }
        }

        if (!empty($invalidIds)) {
            return $this->result([], 400, 'Invalid video IDs');
        }

        foreach ($id as $videoId) {
            $record = Video::find($videoId);
            $imageDelete = imageDelete($record->thumbnail);
            $deleteVideo = deleteVideo($record->url_video);
            $record->delete();
        }

        return $this->result([], 200, 'Data deleted successfully');
    }

    public function getVideosWatchAlso($id)
    {
        return $this->videoRepository->getVideosWatchAlso($id);
    }
}
