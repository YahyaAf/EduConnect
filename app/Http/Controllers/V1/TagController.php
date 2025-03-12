<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;
use OpenAPI\Annotations as OA;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags",
     *     summary="Get a list of tags",
     *     tags={"Tag"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/TagResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request"
     *     )
     * )
     */
    
    public function index()
    {
        return TagResource::collection($this->tagService->getAllTags());
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tags",
     *     summary="Create a new tag",
     *     tags={"Tag"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="New Tag", description="The name of the tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tag created successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function store(TagRequest $request)
    {
        return new TagResource($this->tagService->createTag($request->validated()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tags/{id}",
     *     summary="Get a tag by ID",
     *     tags={"Tag"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tag to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found"
     *     )
     * )
     */
    public function show($id)
    {
        return new TagResource($this->tagService->getTagById($id));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tags/{id}",
     *     summary="Update an existing tag",
     *     tags={"Tag"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tag to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="The name of the tag")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found"
     *     )
     * )
     */
    public function update(TagRequest $request, $id)
    {
        return new TagResource($this->tagService->updateTag($id, $request->validated()));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tags/{id}",
     *     summary="Delete an existing tag",
     *     tags={"Tag"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the tag to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tag not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        return $this->tagService->deleteTag($id);
    }
}
