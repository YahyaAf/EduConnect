<?php

namespace App\Services;

use App\Repositories\StatisticsRepository;

class StatisticsService
{
    protected $statisticsRepository;

    public function __construct(StatisticsRepository $statisticsRepository)
    {
        $this->statisticsRepository = $statisticsRepository;
    }

    public function getCoursesStats(): array
    {
        return $this->statisticsRepository->getCoursesStats();
    }

    public function getCategoriesStats(): array
    {
        return $this->statisticsRepository->getCategoriesStats();
    }

    public function getTagsStats(): array
    {
        return $this->statisticsRepository->getTagsStats();
    }
}
