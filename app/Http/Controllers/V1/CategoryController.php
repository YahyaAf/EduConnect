<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->whereNull('parent_id')->get();
        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    public function show($id)
    {
        $category = Category::with('subcategories')->findOrFail($id);
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function destroy($id): JsonResponse
    {
        $category = Category::findOrFail($id);

        if ($category->subcategories()->exists()) {
            return response()->json(['message' => 'Cannot delete a category with subcategories'], 400);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 204);
    }
}
