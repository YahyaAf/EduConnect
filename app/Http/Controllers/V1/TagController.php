<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\TagRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Services\TagService;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        return TagResource::collection($this->tagService->getAllTags());
    }

    public function store(TagRequest $request)
    {
        return new TagResource($this->tagService->createTag($request->validated()));
    }

    public function show($id)
    {
        return new TagResource($this->tagService->getTagById($id));
    }

    public function update(TagRequest $request, $id)
    {
        return new TagResource($this->tagService->updateTag($id, $request->validated()));
    }

    public function destroy($id): JsonResponse
    {
        return $this->tagService->deleteTag($id);
    }
}
