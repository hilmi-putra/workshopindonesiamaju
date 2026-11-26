<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get paginated list of categories with optional search.
     */
    public function getCategories(int $perPage, ?string $search)
    {
        $query = Category::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new category.
     */
    public function createCategory(array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure uniqueness for auto-generated slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        return Category::create($data);
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Category $category, array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Ensure uniqueness for auto-generated slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
            $data['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        $category->update($data);

        return $category;
    }

    /**
     * Delete a category.
     */
    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }
}
