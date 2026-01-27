@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $news->meta_title)

@section('description', $news->meta_description)


@section("canonical",url('news/'.$news->slug))

@section("og_graph") @stop

@section("content")
@php
  $pageURL = url()->current();
  // Fetch latest 5 posts
  $latestNews = App\News::latest()->take(5)->get();
@endphp
<main class="main container-lg">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb pt-sm-1">
                    <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                    </a></li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">News</li>
                </ol>
            </div>
        </nav>

        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h1 class="text-center fw-normal bg-light fs-2">{{ $news->meta_title }}</h1>
              <picture>
                  <source srcset="{{ url('thumbnail/'.$news->thumbnail.'.webp') }}" type="image/webp">
                  <img class="img-fluid" src="{{ url('thumbnail/'.$news->thumbnail.'.jpg') }}" alt="{{ $news->slug }}" width="1080px" height="480px">
              </picture>

              <p class="mt-2">Updated on <strong>{{ date('d-M-Y',strtotime($news->updated_at)) }}</strong> </p>
              <div class="col-12 my-2">
                  {!! $news->body !!}
              </div>
              <div class="col-12 socialLinks mt-2 mb-3">
                  <div class="col-12">
                      <h4>Share</h4>
                  </div>
                  <!-- Facebook -->
                  <a href="https://www.facebook.com/sharer/sharer.php?u={{$pageURL}}" class="text-decoration-none" target="_blank">
                      <img src="{{URL::to('/')}}/images/icons/facebook.png" class="img-fluid" alt="facebook-logo" style="max-height: 25px;">
                  </a>


                  <!-- Twitter -->
                  <a href="https://x.com/intent/tweet?url={{rawurlencode($pageURL)}}" class="text-decoration-none" target="_blank">
                      <img src="{{URL::to('/')}}/images/icons/twitter.png" class="img-fluid" alt="twitter-logo" style="max-height: 25px;">
                  </a>

                  <!-- WhatsApp -->
                  <a href="https://wa.me/?text={{$pageURL}}" target="_blank" class="text-decoration-none">
                      <img src="{{URL::to('/')}}/images/icons/whatsapp.png" style="max-height: 25px;" class="img-fluid" alt="whatsapp-logo">
                  </a>

              </div>
              <div class="author-box p-2 p-md-3 my-3">
                  <div class="row">
                      <h3 class="text-secondary">About the Author</h3>
                      <div class="col-12 col-md-3 text-center">
                          <img src="https://secure.gravatar.com/avatar/5f2e9928dfa7de7f99f1e658de85bcf6?s=90&d=mm&r=g" alt="Author Image" class="img-fluid mb-3" itemprop="image">
                      </div>
                      <div class="col-12 col-md-9">
                          <h3 class="mb-2" itemprop="name">{{$news->user->name}}</h3>
                          <p class="author-social-icons text-center text-md-start">
                              <!-- Social icons can be linked to the person, but not directly part of the Person schema -->
                              <a href="{{$news->user->facebook ?? '#'}}" target="_blank"><img src="{{URL::to('/')}}/images/icons/facebook.png" class="img-fluid" alt="facebook-logo"></a>
                              <a href="{{$news->user->instagram ?? '#'}}" target="_blank"><img src="{{URL::to('/')}}/images/icons/instagram.png" class="img-fluid" alt="instagram-logo"></a>
                              <a href="{{$news->user->twitter ?? '#'}}" target="_blank"><img src="{{URL::to('/')}}/images/icons/twitter.png" class="img-fluid" alt="twitter-logo"></a>
                          </p>
                          <p class="author-bio" itemprop="description">{{$news->user->biography}}</p>
                      </div>
                  </div>
              </div>
            </div> 
            <div class="col-12 col-md-4">
              <div class="latest-news-sidebar">
                  <h4 class="text-center">Latest News</h4>
                  <ul class="list-unstyled">
                      @foreach($latestNews as $post)
                          <li class="mb-3">
                              <a href="{{ url('news/'.$post->slug) }}" class="text-decoration-none text-dark">
                                  <h5>{{ $post->meta_title }}</h5>
                              </a>
                              <p class="text-muted">{{ \Carbon\Carbon::parse($post->created_at)->format('d-M-Y') }}</p>
                          </li>
                      @endforeach
                  </ul>
              </div>
            </div>
          </div>
        </div>
    </main><!-- End .main -->
@stop


@section('script')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ $news->name }}",
  "author": {
    "@type": "Person",
    "name": "{{ $news->user->name }}"
  },
  "datePublished": "{{ $news->created_at->toIso8601String()}}",
  "dateModified": "{{ $news->updated_at->toIso8601String() }}",
  "publisher": {
    "@type": "Organization",
    "name": "Mobilekishop",
    "logo": {
      "@type": "ImageObject",
      "url": "https://mobilekishop.net/images/logo.png"
    }
  },
  "image": "{{ public_path('thumbnail/' . $news->thumbnail)}}",
  "articleBody": "{{ $news->meta_description }}",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ request()->fullUrl() }}"
  }
}
</script>
@endsection




