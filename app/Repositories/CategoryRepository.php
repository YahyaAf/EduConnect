<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getAll()
    {
        return Category::with('subcategories')->whereNull('parent_id')->get();
    }

    public function findById($id)
    {
        return Category::with('subcategories')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        if ($category->subcategories()->exists()) {
            return response()->json(['message' => 'Cannot delete a category with subcategories'], 400);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 204);
    }
}
