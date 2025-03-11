<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return CategoryResource::collection($this->categoryService->getAllCategories());
    }

    public function store(CategoryRequest $request)
    {
        return new CategoryResource($this->categoryService->createCategory($request->validated()));
    }

    public function show($id)
    {
        return new CategoryResource($this->categoryService->getCategoryById($id));
    }

    public function update(CategoryRequest $request, $id)
    {
        return new CategoryResource($this->categoryService->updateCategory($id, $request->validated()));
    }

    public function destroy($id): JsonResponse
    {
        return $this->categoryService->deleteCategory($id);
    }
}
