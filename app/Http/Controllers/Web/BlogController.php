<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    /**
     * Blog listing page.
     */
    public function index()
    {
        $blogs = Blog::where('status', 'published')
            ->whereNotNull('published_at')
            ->with('category')
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::withCount([
            'blogs' => function ($q) {
                $q->where('status', 'published');
            }
        ])->orderBy('name')->get();

        return view('frontend.blog.index', compact('blogs', 'categories'));
    }

    /**
     * Single blog post.
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedBlogs = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_category_id, function ($q) use ($blog) {
                $q->where('blog_category_id', $blog->blog_category_id);
            })
            ->latest('published_at')
            ->limit(4)
            ->get();

        $categories = BlogCategory::withCount([
            'blogs' => function ($q) {
                $q->where('status', 'published');
            }
        ])->orderBy('name')->get();

        return view('frontend.blog.show', compact('blog', 'relatedBlogs', 'categories'));
    }

    /**
     * Blog posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $blogs = Blog::where('status', 'published')
            ->where('blog_category_id', $category->id)
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::withCount([
            'blogs' => function ($q) {
                $q->where('status', 'published');
            }
        ])->orderBy('name')->get();

        return view('frontend.blog.index', compact('blogs', 'categories', 'category'));
    }
}
