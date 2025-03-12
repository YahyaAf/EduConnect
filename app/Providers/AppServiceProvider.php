<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use App\Repositories\TagRepository;
use App\Services\TagService;
use App\Interfaces\CourseRepositoryInterface;
use App\Repositories\CourseRepository;

/**
 * @OA\Info(
 * title="E-Learning",
 * version="1.0.0"
 * )
*/

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepository::class, function ($app) {
            return new CategoryRepository();
        });
    
        $this->app->bind(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CategoryRepository::class));
        });


        $this->app->bind(TagRepository::class, function ($app) {
            return new TagRepository();
        });
    
        $this->app->bind(TagService::class, function ($app) {
            return new TagService($app->make(TagRepository::class));
        });

        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
