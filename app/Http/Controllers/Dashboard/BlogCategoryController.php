<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->orderBy('id')->paginate(20);
        return view('dashboard.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.blog-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'slug', 'description']);

        if (empty($data['slug'])) {
            $data['slug'] = BlogCategory::generateUniqueSlug($data['name']);
        }

        BlogCategory::create($data);

        return redirect()->route('dashboard.blog-categories.index')->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blog_category)
    {
        return view('dashboard.blog-categories.edit', compact('blog_category'));
    }

    public function update(Request $request, BlogCategory $blog_category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_categories,slug,' . $blog_category->id,
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'slug', 'description']);

        if (empty($data['slug'])) {
            $data['slug'] = BlogCategory::generateUniqueSlug($data['name'], $blog_category->id);
        }

        $blog_category->update($data);

        return redirect()->route('dashboard.blog-categories.index')->with('success', 'Blog category updated successfully.');
    }

    public function destroy(BlogCategory $blog_category)
    {
        // Optionally set blogs in this category to null
        $blog_category->blogs()->update(['blog_category_id' => null]);
        $blog_category->delete();

        return redirect()->route('dashboard.blog-categories.index')->with('success', 'Blog category deleted successfully.');
    }
}
