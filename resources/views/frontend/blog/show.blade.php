@extends('layouts.frontend')

@section('title', $blog->meta_title ?: $blog->title . ' | MobileKiShop')

@section('description', $blog->meta_description ?: Str::limit(strip_tags($blog->excerpt ?: $blog->body), 160))

@section('og_type', 'article')

@if($blog->featured_image)
    @section('og_image', $blog->featured_image)
@endif

@section('style')
    <style>
        /* Article body typography */
        .article-body { font-size: 1.1rem; line-height: 1.85; color: var(--text-muted, #475569); }
        .article-body h1, .article-body h2, .article-body h3, .article-body h4 {
            color: var(--text-main, #0f172a); font-weight: 700; margin-top: 2em; margin-bottom: 0.75em; line-height: 1.35;
        }
        .article-body h2 { font-size: 1.6rem; padding-bottom: 0.4em; border-bottom: 2px solid #e2e8f0; }
        .article-body h3 { font-size: 1.3rem; }
        .article-body p { margin-bottom: 1.25em; }
        .article-body a { color: var(--primary, #3b82f6); font-weight: 500; text-decoration: underline; text-underline-offset: 3px; }
        .article-body a:hover { color: var(--primary-hover, #2563eb); }
        .article-body img { border-radius: 12px; margin: 1.5em 0; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .article-body blockquote {
            border-left: 4px solid var(--primary, #3b82f6); margin: 1.5em 0; padding: 1em 1.5em;
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%); border-radius: 0 12px 12px 0; font-style: italic;
        }
        .article-body ul, .article-body ol { margin-bottom: 1.25em; padding-left: 1.5em; }
        .article-body li { margin-bottom: 0.5em; }
        .article-body code {
            background: #f1f5f9; color: var(--primary, #3b82f6); padding: 0.15em 0.4em; border-radius: 6px; font-size: 0.9em;
        }
        .article-body pre {
            background: #1e293b; color: #e2e8f0; padding: 1.2em 1.5em; border-radius: 12px; overflow-x: auto; margin: 1.5em 0;
        }
        .article-body pre code { background: transparent; color: inherit; padding: 0; }
        .article-body { overflow-x: hidden; }
        .article-body table {
            width: 100%; border-collapse: collapse; margin: 1.5em 0; border-radius: 12px; overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06); border: 1px solid #e2e8f0; display: block; overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        @media (min-width: 768px) { .article-body table { display: table; overflow-x: visible; } }
        .article-body th { background: #f8fafc; font-weight: 700; color: var(--text-main, #0f172a); text-align: left; }
        .article-body th, .article-body td { padding: 0.75em 1em; border-bottom: 1px solid #e2e8f0; font-size: 0.95rem; }
        .article-body tr:last-child td { border-bottom: none; }
        .article-body tr:hover td { background: #fafbfd; }
        .article-body strong { color: var(--text-main, #0f172a); }
        .article-body hr { border: none; height: 2px; background: #e2e8f0; margin: 2em 0; }
    </style>
@endsection

@section('content')
    {{-- Breadcrumbs --}}
    <div class="flex flex-wrap items-center gap-2 text-sm mb-8">
        <a href="{{ url('/') }}" class="text-primary font-medium hover:underline">Home</a>
        <span class="text-text-muted">/</span>
        <a href="{{ url('/blog') }}" class="text-text-muted hover:text-primary font-medium hover:underline">Blog</a>
        @if($blog->category)
            <span class="text-text-muted">/</span>
            <a href="{{ url('/blog/category/' . $blog->category->slug) }}" class="text-text-muted hover:text-primary font-medium hover:underline">{{ $blog->category->name }}</a>
        @endif
        <span class="text-text-muted">/</span>
        <span class="text-text-main font-semibold line-clamp-1">{{ Str::limit($blog->title, 40) }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        {{-- Main Content --}}
        <article class="lg:col-span-8">
            {{-- Article Header --}}
            <header class="mb-8">
                @if($blog->category)
                    <a href="{{ url('/blog/category/' . $blog->category->slug) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold text-primary uppercase tracking-widest mb-4 bg-primary/8 px-3 py-1.5 rounded-full hover:bg-primary/15 transition-colors">
                        <span class="material-symbols-outlined text-[12px]">folder</span>
                        {{ $blog->category->name }}
                    </a>
                @endif
                <h1 class="text-3xl md:text-4xl lg:text-[2.5rem] font-bold text-text-main tracking-tight leading-[1.2] mb-5">
                    {{ $blog->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-5 text-sm text-text-muted">
                    <span class="flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-indigo-500 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-white text-[16px]">person</span>
                        </span>
                        <div class="flex flex-col">
                            <span class="text-text-main font-semibold text-sm">{{ $blog->user->name ?? 'MKS Team' }}</span>
                            <span class="text-xs text-text-muted">{{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
                        </div>
                    </span>
                </div>
            </header>

            {{-- Featured Image --}}
            @if($blog->featured_image)
                <div class="rounded-2xl overflow-hidden mb-8 border border-border-light shadow-lg relative group">
                    <img src="{{ $blog->featured_image }}" alt="{{ $blog->title }}"
                         class="w-full h-auto group-hover:scale-[1.02] transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
            @endif

            {{-- Excerpt / TL;DR --}}
            @if($blog->excerpt)
                <div class="relative bg-gradient-to-r from-primary/5 via-blue-50 to-indigo-50 rounded-2xl p-6 mb-8 border border-primary/10">
                    <div class="absolute -top-3 left-5">
                        <span class="text-[10px] font-bold text-primary uppercase tracking-widest bg-white px-3 py-1 rounded-full border border-primary/20 shadow-sm">TL;DR</span>
                    </div>
                    <p class="text-text-muted text-base leading-relaxed mt-1">{{ $blog->excerpt }}</p>
                </div>
            @endif

            {{-- Article Body --}}
            <div class="bg-white border border-border-light rounded-2xl p-6 md:p-10 shadow-card">
                <div class="article-body">
                    {!! $blog->body !!}
                </div>
            </div>

            {{-- Share / Back --}}
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-border-light">
                <a href="{{ url('/blog') }}"
                   class="flex items-center gap-2 text-sm font-semibold text-text-muted hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Blog
                </a>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-text-muted font-medium">Share:</span>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/blog/' . $blog->slug)) }}&text={{ urlencode($blog->title) }}"
                       target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-primary hover:text-white flex items-center justify-center text-text-muted transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/blog/' . $blog->slug)) }}"
                       target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-primary hover:text-white flex items-center justify-center text-text-muted transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url('/blog/' . $blog->slug)) }}"
                       target="_blank" rel="noopener"
                       class="w-9 h-9 rounded-lg bg-slate-100 hover:bg-green-500 hover:text-white flex items-center justify-center text-text-muted transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4">
            <div class="flex flex-col gap-6 lg:sticky lg:top-[80px]">

                {{-- Related Posts --}}
                @if(isset($relatedBlogs) && $relatedBlogs->count())
                    <div class="bg-white border border-border-light rounded-2xl overflow-hidden shadow-card">
                        <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-border-light">
                            <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">recommend</span>
                                Related Posts
                            </h3>
                        </div>
                        <div class="p-4 space-y-1">
                            @foreach($relatedBlogs as $related)
                                <a href="{{ url('/blog/' . $related->slug) }}"
                                   class="flex gap-3 group p-3 rounded-xl hover:bg-slate-50 transition-all duration-200">
                                    @if($related->featured_image)
                                        <img src="{{ $related->featured_image }}" alt="{{ $related->title }}"
                                             class="w-20 h-16 object-cover rounded-lg flex-shrink-0 border border-border-light shadow-sm group-hover:shadow transition-shadow">
                                    @else
                                        <div class="w-20 h-16 rounded-lg bg-gradient-to-br from-slate-100 to-slate-50 flex items-center justify-center flex-shrink-0 border border-border-light">
                                            <span class="material-symbols-outlined text-slate-300 text-2xl">article</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-text-main group-hover:text-primary transition-colors line-clamp-2 leading-snug mb-1">
                                            {{ $related->title }}
                                        </h4>
                                        <span class="text-[11px] text-text-muted flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                            {{ $related->published_at ? $related->published_at->format('M d, Y') : $related->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Categories --}}
                @if(isset($categories) && $categories->count())
                    <div class="bg-white border border-border-light rounded-2xl overflow-hidden shadow-card">
                        <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-border-light">
                            <h3 class="text-base font-bold text-text-main flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">category</span>
                                Categories
                            </h3>
                        </div>
                        <div class="p-3">
                            @foreach($categories as $cat)
                                <a href="{{ url('/blog/category/' . $cat->slug) }}"
                                   class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                                   {{ isset($blog->category) && $blog->category->id === $cat->id ? 'text-primary bg-primary/5 font-bold' : 'text-text-muted hover:text-primary hover:bg-slate-50' }}">
                                    <span class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                        {{ $cat->name }}
                                    </span>
                                    <span class="text-xs bg-slate-100 text-text-muted px-2.5 py-0.5 rounded-full font-semibold min-w-[26px] text-center">{{ $cat->blogs_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Back to Blog CTA --}}
                <a href="{{ url('/blog') }}"
                   class="flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-primary to-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 transition-all duration-300 group">
                    <span class="material-symbols-outlined text-[18px]">library_books</span>
                    Browse All Articles
                    <span class="material-symbols-outlined text-[18px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                </a>
            </div>
        </aside>
    </div>
@endsection

@section('structured_data')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "BlogPosting",
        "headline": "{{ $blog->title }}",
        "description": "{{ $blog->meta_description ?: Str::limit(strip_tags($blog->excerpt ?: $blog->body), 160) }}",
        @if($blog->featured_image)
            "image": "{{ $blog->featured_image }}",
        @endif
        "datePublished": "{{ ($blog->published_at ?: $blog->created_at)->toIso8601String() }}",
        "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
        @if($blog->user)
            "author": {
                "@type": "Person",
                "name": "{{ $blog->user->name }}"
            },
        @endif
        "publisher": {
            "@type": "Organization",
            "name": "MobileKiShop",
            "logo": {
                "@type": "ImageObject",
                "url": "{{ asset('images/logo.png') }}"
            }
        },
        "url": "{{ url('/blog/' . $blog->slug) }}"
    }
    </script>
@endsection