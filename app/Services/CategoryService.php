<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCategories()
    {
        return Category::get();
    }

    /**
     * Create a new category or return an existing one.
     *
     * @param  array  $data
     * @return \App\Models\Category
     */
    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);
        return Category::firstOrCreate($data);
    }

    /**
     * Update an existing category with new data.
     *
     * @param  \App\Models\Category  $category
     * @param  array  $data
     * @return \App\Models\Category
     */
    public function update(Category $category, array $data): Category
    {
        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return $category;
    }

    /**
     * Delete the given category from the database.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function delete(Category $category): void
    {
        $category->delete();
    }
}
