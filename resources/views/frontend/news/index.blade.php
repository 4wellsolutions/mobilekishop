@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', "News Index")

@section('description', "News Description")

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",url('news'))

@section("og_graph") @stop

@section("content")

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
            <div class="col-12 col-md-12">
                <div class="container mt-4">
                    <h1 class="text-center">Latest News</h1>
                    <!-- News Articles -->
                    <div class="row">
                        @foreach($news as $article)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card border-0 shadow">
                                    <div class="card-body p-2">
                                        <a href="{{ url('news/'.$article->slug)}}" class="text-decoration-none text-dark">
                                        <picture>
                                            <source srcset="{{ url('thumbnail/'.$article->thumbnail.'.webp') }}" type="image/webp">
                                            <img class="img-fluid" src="{{ url('thumbnail/'.$article->thumbnail.'.jpg') }}" alt="{{ $article->slug }}" width="1080px" height="480px">
                                        </picture>
                                        <h3 class="card-title fs-5 mt-1">{{ $article->name }}</h3>
                                        <p class="card-text">{{ $article->created_at->diffForHumans() }}</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center">
                        {{ $news->links() }}
                    </div>
                </div>

            </div>
            
        </div><!-- End .container -->
        </div>
    </main><!-- End .main -->
@stop




@section("script")
<script type="text/javascript">
	var base_url = "{{Request::url()}}";
    $(".select-filter").change(function(){
        console.log("change");
        $(".formFilter").submit();
    });
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org/", 
  "@type": "BreadcrumbList", 
  "itemListElement": [{
    "@type": "ListItem", 
    "position": 1, 
    "name": "Home",
    "item": "{{url('/')}}"  
  },{
    "@type": "ListItem", 
    "position": 2, 
    "name": "news",
    "item": "{{url('/news')}}"  
  }]
}
</script>
@stop

@section("style")

@stop
