<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\StatisticsService;

class StatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function getCoursesStats(): JsonResponse
    {
        return response()->json($this->statisticsService->getCoursesStats());
    }

    public function getCategoriesStats(): JsonResponse
    {
        return response()->json($this->statisticsService->getCategoriesStats());
    }

    public function getTagsStats(): JsonResponse
    {
        return response()->json($this->statisticsService->getTagsStats());
    }
}
