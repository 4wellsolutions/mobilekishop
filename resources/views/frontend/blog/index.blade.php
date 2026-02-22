@extends('layouts.frontend')

@section('title', isset($category) ? $category->name . ' - Blog | MobileKiShop' : 'Blog - Tech News & Guides | MobileKiShop')

@section('description', isset($category) ? 'Read our latest articles about ' . $category->name : 'Read our latest articles, guides, and news about mobile phones and technology.')

@section('content')
    {{-- Hero Section --}}
    <div class="relative -mx-4 md:-mx-6 lg:-mx-8 -mt-8 mb-10 px-4 md:px-6 lg:px-8 pt-12 pb-10 overflow-hidden">
        {{-- Gradient Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-blue-50 to-indigo-50"></div>
        <div
            class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-primary/10 to-transparent rounded-full blur-3xl -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-indigo-200/20 to-transparent rounded-full blur-3xl translate-y-1/2 -translate-x-1/4">
        </div>

        <div class="relative z-10">
            {{-- Breadcrumb --}}
            <div class="flex flex-wrap items-center gap-2 text-sm mb-6">
                <a href="{{ url('/') }}" class="text-primary font-medium hover:underline">Home</a>
                <span class="text-text-muted">/</span>
                @if(isset($category))
                    <a href="{{ url('/blogs') }}"
                        class="text-text-muted hover:text-primary font-medium hover:underline">Blog</a>
                    <span class="text-text-muted">/</span>
                    <span class="text-text-main font-semibold">{{ $category->name }}</span>
                @else
                    <span class="text-text-main font-semibold">Blog</span>
                @endif
            </div>

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-primary text-3xl">edit_note</span>
                        <span
                            class="text-xs font-bold uppercase tracking-widest text-primary bg-primary/10 px-3 py-1 rounded-full">Blog</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-text-main tracking-tight">
                        {{ isset($category) ? $category->name : 'Latest Articles' }}
                    </h1>
                    <p class="text-text-muted mt-3 text-lg max-w-xl">Insights, guides & news from the world of mobile
                        technology.</p>
                </div>
                {{-- Post Count --}}
                <div
                    class="flex items-center gap-2 text-sm text-text-muted bg-white/80 backdrop-blur px-4 py-2 rounded-xl border border-border-light shadow-sm">
                    <span class="material-symbols-outlined text-[18px] text-primary">library_books</span>
                    <span class="font-medium">{{ $blogs->total() }} {{ Str::plural('article', $blogs->total()) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Filters --}}
    @if(isset($categories) && $categories->count())
        <div class="flex flex-wrap gap-2 mb-10">
            <a href="{{ url('/blogs') }}"
                class="group flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl border-2 transition-all duration-300
                       {{ !isset($category) ? 'bg-primary text-white border-primary shadow-md shadow-primary/20' : 'bg-white text-text-muted border-border-light hover:border-primary hover:text-primary hover:shadow-sm' }}">
                <span class="material-symbols-outlined text-[16px]">apps</span>
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ url('/blogs/category/' . $cat->slug) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-xl border-2 transition-all duration-300
                               {{ isset($category) && $category->id === $cat->id ? 'bg-primary text-white border-primary shadow-md shadow-primary/20' : 'bg-white text-text-muted border-border-light hover:border-primary hover:text-primary hover:shadow-sm' }}">
                    {{ $cat->name }}
                    <span class="ml-1 text-xs opacity-60">({{ $cat->blogs_count }})</span>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Blog Grid --}}
    @if($blogs->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($blogs as $index => $blog)
                @if($index === 0 && !isset($category))
                    {{-- Featured (First) Post - Full Width --}}
                    <a href="{{ url('/blogs/' . $blog->slug) }}"
                        class="md:col-span-2 lg:col-span-3 group relative bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-xl border border-border-light transition-all duration-500">
                        <div class="grid grid-cols-1 lg:grid-cols-2">
                            <div class="aspect-[16/9] lg:aspect-auto overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                                @if($blog->featured_image)
                                    <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div
                                        class="w-full h-full min-h-[280px] flex items-center justify-center bg-gradient-to-br from-primary/5 to-indigo-50">
                                        <span class="material-symbols-outlined text-7xl text-primary/20">article</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-8 lg:p-10 flex flex-col justify-center">
                                <div class="flex items-center gap-3 mb-4">
                                    @if($blog->category)
                                        <span
                                            class="text-xs font-bold text-primary uppercase tracking-widest bg-primary/8 px-3 py-1 rounded-full">{{ $blog->category->name }}</span>
                                    @endif
                                    <span
                                        class="text-xs font-bold text-amber-600 uppercase tracking-widest bg-amber-50 px-3 py-1 rounded-full flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">star</span> Featured
                                    </span>
                                </div>
                                <h2
                                    class="text-2xl lg:text-3xl font-bold text-text-main group-hover:text-primary transition-colors duration-300 leading-tight mb-4">
                                    {{ $blog->title }}
                                </h2>
                                @if($blog->excerpt)
                                    <p class="text-text-muted leading-relaxed mb-6 line-clamp-3">{{ $blog->excerpt }}</p>
                                @endif
                                <div class="flex items-center gap-4 text-sm text-text-muted mt-auto">
                                    <span class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                        {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="mt-5">
                                    <span
                                        class="inline-flex items-center gap-1.5 text-sm font-bold text-primary group-hover:gap-2.5 transition-all">
                                        Read Article
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @else
                    {{-- Regular Post Card --}}
                    <a href="{{ url('/blogs/' . $blog->slug) }}"
                        class="group bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-xl border border-border-light hover:border-primary/20 transition-all duration-500 flex flex-col">
                        {{-- Image --}}
                        <div class="aspect-[16/9] overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200 relative">
                            @if($blog->featured_image)
                                <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-indigo-50">
                                    <span class="material-symbols-outlined text-5xl text-primary/20">article</span>
                                </div>
                            @endif
                            {{-- Category Badge on Image --}}
                            @if($blog->category)
                                <div class="absolute top-3 left-3">
                                    <span
                                        class="text-[10px] font-bold text-white uppercase tracking-wider bg-primary/90 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-sm">{{ $blog->category->name }}</span>
                                </div>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="p-5 flex flex-col flex-1">
                            <h2
                                class="text-lg font-bold text-text-main group-hover:text-primary transition-colors duration-300 line-clamp-2 mb-2 leading-snug">
                                {{ $blog->title }}
                            </h2>
                            @if($blog->excerpt)
                                <p class="text-sm text-text-muted line-clamp-2 mb-4 flex-1 leading-relaxed">{{ $blog->excerpt }}</p>
                            @endif
                            <div
                                class="flex items-center justify-between text-xs text-text-muted mt-auto pt-4 border-t border-border-light">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                                </span>
                                <span
                                    class="flex items-center gap-1 font-semibold text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    Read <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-12 flex justify-center">
            {{ $blogs->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="bg-white border border-border-light rounded-2xl p-16 text-center shadow-card">
            <div
                class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-primary/10 to-indigo-50 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl text-primary/60">article</span>
            </div>
            <h2 class="text-2xl font-bold text-text-main mb-3">No posts yet</h2>
            <p class="text-text-muted max-w-sm mx-auto">We're working on some great content. Check back soon for new articles!
            </p>
        </div>
    @endif
@endsection