<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\CategoryRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use OpenAPI\Annotations as OA;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="Get a list of categories",
     *     tags={"Category"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        return CategoryResource::collection($this->categoryService->getAllCategories());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     summary="Create a new category",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="New Category"),
     *             @OA\Property(property="description", type="string", example="This is a new category description")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *      
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request data"
     *     )
     * )
     */
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
