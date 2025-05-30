<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return 2312;
        $this->authorize('viewAny', Category::class);

        $categories = $this->categoryService->getAllCategories();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // return 2312;

        $this->authorize('create', Category::class);

        $data = $request->validated();

        $category = $this->categoryService->createCategory($data);

        if (!$category->wasRecentlyCreated) {
            return response()->json(['error' => 'This category already exists.'], 409);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'The category has been created successfully.',
            'data' => $category
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // return 2312;

        $this->authorize('update', $category);

        $data = $request->validated();

        $updatedCategory = $this->categoryService->update($category, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'The category has been updated successfully.',
            'data' => $updatedCategory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // return 2312;

        $this->authorize('delete', $category);

        $this->categoryService->delete($category);

        return response()->json([
            'status' => 'success',
            'message' => 'The category has been deleted successfully.'
        ]);
    }
}
