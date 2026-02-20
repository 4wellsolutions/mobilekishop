<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCategoryApiController extends Controller
{
    /**
     * GET /api/blog-categories
     */
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->orderBy('name')->get();

        return response()->json(['data' => $categories]);
    }

    /**
     * POST /api/blog-categories
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (empty($data['slug'])) {
            $data['slug'] = BlogCategory::generateUniqueSlug($data['name']);
        }

        $category = BlogCategory::create($data);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    /**
     * GET /api/blog-categories/{slug}
     */
    public function show(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->withCount('blogs')->first();

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        return response()->json(['data' => $category]);
    }

    /**
     * PUT /api/blog-categories/{slug}
     */
    public function update(Request $request, string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->first();

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (isset($data['name']) && !isset($data['slug'])) {
            $data['slug'] = BlogCategory::generateUniqueSlug($data['name'], $category->id);
        }

        $category->update($data);

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => $category->fresh(),
        ]);
    }

    /**
     * DELETE /api/blog-categories/{slug}
     */
    public function destroy(string $slug)
    {
        $category = BlogCategory::where('slug', $slug)->first();

        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
