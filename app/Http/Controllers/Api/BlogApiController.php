<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BlogApiController extends Controller
{
    /**
     * GET /api/blogs
     * List all blogs (paginated), with optional filters.
     */
    public function index(Request $request)
    {
        $query = Blog::query()->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->paginate($request->get('per_page', 15));

        return response()->json($blogs);
    }

    /**
     * GET /api/blogs/{slug}
     * Get a single blog by slug.
     */
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }

        return response()->json(['data' => $blog]);
    }

    /**
     * POST /api/blogs
     * Create a new blog.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateUniqueSlug($data['title']);
        }

        // Auto-set published_at when status is published
        if (($data['status'] ?? 'draft') === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Associate with authenticated user
        $data['user_id'] = auth()->id();

        $blog = Blog::create($data);

        return response()->json([
            'message' => 'Blog created successfully.',
            'data' => $blog,
        ], 201);
    }

    /**
     * PUT /api/blogs/{slug}
     * Update an existing blog.
     */
    public function update(Request $request, string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'featured_image' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Re-generate slug if title changed and slug not explicitly provided
        if (isset($data['title']) && !isset($data['slug'])) {
            $data['slug'] = Blog::generateUniqueSlug($data['title'], $blog->id);
        }

        // Auto-set published_at when publishing for the first time
        if (isset($data['status']) && $data['status'] === 'published' && !$blog->published_at && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return response()->json([
            'message' => 'Blog updated successfully.',
            'data' => $blog->fresh(),
        ]);
    }

    /**
     * DELETE /api/blogs/{slug}
     * Delete a blog.
     */
    public function destroy(string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['error' => 'Blog not found.'], 404);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.']);
    }

    /**
     * POST /api/blogs/media
     * Upload an image and return its public URL.
     */
    public function uploadMedia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('image');
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('blogs', $filename, 'public');

        return response()->json([
            'message' => 'Image uploaded successfully.',
            'url' => Storage::url($path),
            'path' => $path,
        ], 201);
    }
}
