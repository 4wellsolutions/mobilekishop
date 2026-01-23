<div class="pageBody">
  @if($page = App\Page::whereSlug(\Request::fullUrl())->first())
    {!! $page->body !!}
  @endif
</div>
