<?php

namespace App\Providers;

use App\Repositories\Address\AddressInterface;
use App\Repositories\Address\AddressRepository;
use App\Repositories\BrowseVideo\BrowseVideoInterface;
use App\Repositories\BrowseVideo\BrowseVideoRepository;
use App\Repositories\Tag\TagInterface;
use App\Repositories\Tag\TagRepository;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\CommunityGuideline\CommunityGuidelineInterface;
use App\Repositories\CommunityGuideline\CommunityGuidelineRepository;
use App\Repositories\FAQ\FAQInterface;
use App\Repositories\FAQ\FAQRepository;
use App\Repositories\Featured\FeaturedInterface;
use App\Repositories\Featured\FeaturedRepository;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\UserProfile\UserProfileInterface;
use App\Repositories\UserProfile\UserProfileRepository;
use App\Repositories\Video\VideoInterface;
use App\Repositories\Video\VideoRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(TagInterface::class, TagRepository::class);
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(FeaturedInterface::class, FeaturedRepository::class);
        $this->app->bind(UserProfileInterface::class, UserProfileRepository::class);
        $this->app->bind(AddressInterface::class, AddressRepository::class);
        $this->app->bind(FAQInterface::class, FAQRepository::class);
        $this->app->bind(BrowseVideoInterface::class, BrowseVideoRepository::class);
        $this->app->bind(CommunityGuidelineInterface::class, CommunityGuidelineRepository::class);
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(VideoInterface::class, VideoRepository::class);
    }
}
