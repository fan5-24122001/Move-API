<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Video;
use Illuminate\Console\Command;

class DeleteVideosUploading extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:DeleteVideosUploading';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $videos = Video::where('file_status', 'uploading')->whereDate('created_at', '<=', Carbon::now()->subDay())->get();

        foreach ($videos as $video) {
            deleteVideo($video->url_video);
            $video->delete();
        }
    }
}
