<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;  
use App\Repositories\VideosUser\VideosUserRepository;
use App\Repositories\DetailVideo\DetailVideoInterface;
use App\Repositories\VideosAdmin\VideosAdminInterface;
use App\Repositories\DetailVideo\DetailVideoRepository;
use App\Repositories\VideosAdmin\VideosAdminRepository;
use App\Repositories\FeaturedVideos\FeaturedVideosInterface;
use App\Repositories\FeaturedVideos\FeaturedVideosRepository;
use App\Repositories\VideosUser\VideosUserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(VideosUserRepositoryInterface::class, VideosUserRepository::class);
        $this->app->bind(VideosAdminInterface::class, VideosAdminRepository::class);
        $this->app->bind(FeaturedVideosInterface::class, FeaturedVideosRepository::class);
        $this->app->bind(DetailVideoInterface::class, DetailVideoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}


