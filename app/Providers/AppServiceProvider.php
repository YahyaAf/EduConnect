<?php

namespace App\Providers;

use App\Services\TagService;
use App\Services\CategoryService;
use App\Repositories\TagRepository;
use App\Services\PermissionService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Route;
use App\Repositories\CourseRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\PermissionRepository;
use App\Interfaces\CourseRepositoryInterface;

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

        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        $this->app->bind(PermissionRepository::class, function ($app) {
            return new PermissionRepository();
        });

        $this->app->bind(PermissionService::class, function ($app) {
            return new PermissionService($app->make(PermissionRepository::class));
        });
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
