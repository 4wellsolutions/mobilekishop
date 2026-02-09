<div class="prose prose-sm max-w-none text-text-muted mt-4">
  @if($page = App\Models\Page::whereSlug(\Request::fullUrl())->first())
    {!! $page->body !!}
  @endif
</div>