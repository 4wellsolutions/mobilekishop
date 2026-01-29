@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)
@section('description', $metas->description)
@section("keywords", "Mobiles prices, mobile specification, mobile phone features")
@section("canonical", $metas->canonical)

@section("og_graph")
@endsection

@section("noindex")
    <meta name="robots" content="noindex">
@endsection

@section("content")
    <main class="main container-lg">
        <div class="container my-3">
            <div class="row">
                <h1 class="heading1 fs-4">{{$metas->h1}}</h1>
            </div>
            @if(!$compares->isEmpty())
                <div class="row" id="compareList" data-next-page="2">
                    @foreach($compares as $compare)
                        @include('includes.compare-details', ['compare' => $compare])
                    @endforeach
                </div>
                <div id="dynamicContentEnd"></div>
                <div id="loadingSpinner" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@section("script")
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org/", 
            "@@type": "BreadcrumbList", 
            "itemListElement": [{
                "@@type": "ListItem", 
                "position": 1, 
                "name": "Home",
                "item": "{{url('/')}}/"  
            },{
                "@@type": "ListItem", 
                "position": 2, 
                "name": "{{$metas->name}}",
                "item": "{{$metas->canonical}}"  
            }]
        }
        </script>
@endsection