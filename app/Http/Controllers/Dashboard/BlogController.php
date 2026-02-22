<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->latest()->paginate(20);
        return view('dashboard.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('dashboard.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'featured_image_url' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
        ]);

        $data = $request->except(['featured_image_url']);

        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateUniqueSlug($data['title']);
        }

        // Featured image from media library
        if ($request->filled('featured_image_url')) {
            $data['featured_image'] = $request->input('featured_image_url');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $data['user_id'] = auth()->id();

        $blog = Blog::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog post created successfully.',
                'redirect' => route('dashboard.blogs.edit', $blog),
            ]);
        }

        return redirect()->route('dashboard.blogs.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::orderBy('name')->get();
        return view('dashboard.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'featured_image_url' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
        ]);

        $data = $request->except(['featured_image_url']);

        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateUniqueSlug($data['title'], $blog->id);
        }

        // Featured image from media library
        if ($request->filled('featured_image_url')) {
            $data['featured_image'] = $request->input('featured_image_url');
        } elseif ($request->input('featured_image_url') === '') {
            $data['featured_image'] = null; // removed
        }

        if ($data['status'] === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Blog post updated successfully.',
                'redirect' => route('dashboard.blogs.edit', $blog),
            ]);
        }

        return redirect()->route('dashboard.blogs.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('dashboard.blogs.index')->with('success', 'Blog post deleted successfully.');
    }
}
