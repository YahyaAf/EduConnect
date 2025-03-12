<?php

namespace App\Http\Controllers\V1;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use OpenAPI\Annotations as OA;
use App\Http\Requests\TagRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

    /**
     * @OA\Post(
     *     path="/api/v1/tags/multiple",
     *     summary="Create multiple tags",
     *     description="Create multiple tags by passing an array of names.",
     *     tags={"Tags"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"names"},
     *             @OA\Property(
     *                 property="names",
     *                 type="array",
     *                 items=@OA\Items(type="string"),
     *                 example={"Laravel", "PHP", "VueJS"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tags created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tags créés avec succès"),
     *             @OA\Property(
     *                 property="tags",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Laravel")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erreur lors de la création des tags"),
     *             @OA\Property(property="message", type="string", example="The names field is required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erreur lors de la création des tags"),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */
    public function storeMultiple(Request $request)
    {
        try {
            $request->validate([
                'names' => 'required|array|min:1',
                'names.*' => 'string|max:255',
            ]);
            $tags = $this->tagService->createMultipleTags($request->input('names'));
            return response()->json([
                'message' => 'Tags créés avec succès',
                'tags' => $tags,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création des tags',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

}
