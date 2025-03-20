<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\StatisticsService;
use Illuminate\Routing\Controller as BaseController;

class StatisticsController extends BaseController
{
    protected $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;

        $this->middleware('can:getCoursesStats')->only(['getCoursesStats']);
        $this->middleware('can:getCategoriesStats')->only(['getCategoriesStats']);
        $this->middleware('can:getTagsStats')->only(['getTagsStats']);
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
