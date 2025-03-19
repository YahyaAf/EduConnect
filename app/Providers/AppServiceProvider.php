<?php

namespace App\Providers;

use App\Services\TagService;
use App\Services\MentorService;
use App\Services\StudentService;
use App\Services\CategoryService;
use App\Repositories\TagRepository;
use App\Services\EnrollmentService;
use App\Services\PermissionService;
use App\Services\StatisticsService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Route;
use App\Repositories\CourseRepository;
use App\Repositories\MentorRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\StudentRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\StatisticsRepository;
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

        $this->app->bind(EnrollmentRepository::class, function ($app) {
            return new EnrollmentRepository();
        });

        $this->app->bind(EnrollmentService::class, function ($app) {
            return new EnrollmentService($app->make(EnrollmentRepository::class));
        });

        $this->app->bind(ProfileRepository::class, ProfileRepository::class);

        $this->app->bind(StatisticsRepository::class, function ($app) {
            return new StatisticsRepository();
        });
    
        $this->app->bind(StatisticsService::class, function ($app) {
            return new StatisticsService($app->make(StatisticsRepository::class));
        });

        $this->app->bind(StudentRepository::class, StudentRepository::class);
        
        $this->app->bind(StudentService::class, StudentService::class);

        $this->app->bind(MentorRepository::class, function ($app) {
            return new MentorRepository();
        });
    
        $this->app->bind(MentorService::class, function ($app) {
            return new MentorService($app->make(MentorRepository::class));
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
