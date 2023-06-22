<?php

namespace App\Console\Commands;

use App\Models\Video;
use Illuminate\Console\Command;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\DB;

class VimeoProcessCommand extends Command
{
    protected $signature = 'video:process';

    protected $description = 'Process videos uploaded to Vimeo';

    public function handle()
    {
        $videos = Video::where('conversion', 'in_progress')->get();

        foreach ($videos as $video) {
            $response = Vimeo::request($video->url_video);

            if (isset($response['body']['status']) && $response['body']['status'] === 'available') {
                $file_duration = $response['body']['duration'];

                Video::where('url_video', $video->url_video)
                    ->update(['conversion' => 'completed', 'file_duration' => $file_duration]);
            }
        }
    }
}
