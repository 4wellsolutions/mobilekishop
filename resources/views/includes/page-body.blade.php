<div class="pageBody">
  @if($page = App\Models\Page::whereSlug(\Request::fullUrl())->first())
    {!! $page->body !!}
  @endif
</div>