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

@section("content")
<main class="main container-lg">
    <div class="container my-5 shadow-sm p-4 bg-white rounded-3 text-center">
        <h1 class="mb-4 fw-bold text-primary">{{ $metas->h1 }}</h1>
        <div class="content fs-16 text-muted lh-lg mx-auto" style="max-width: 800px;">
            <p class="lead text-dark mb-4">Empowering Mobile Shoppers with Transparency and Accuracy.</p>
            
            <p>At <strong>MobileKiShop (MKS)</strong>, our mission is simple: to be the most trusted and comprehensive source for mobile phone information in {{ $country->country_name }} and beyond. We understand that choosing a new smartphone is a significant decision, and we are here to make it easier, faster, and more informed.</p>

            <div class="row mt-5 text-start">
                <div class="col-md-6 mb-4">
                    <h4 class="fw-bold text-dark"><i class="bi bi-tag-fill text-primary"></i> Real-Time Pricing</h4>
                    <p>We aggregate and update mobile prices from multiple retailers to ensure you get the best deal available in the market.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h4 class="fw-bold text-dark"><i class="bi bi-cpu-fill text-primary"></i> Expert Specs</h4>
                    <p>From processor clock speeds to camera sensor details, we provide in-depth technical specifications for every device.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h4 class="fw-bold text-dark"><i class="bi bi-arrows-angle-contract text-primary"></i> Dynamic Comparison</h4>
                    <p>Our powerful comparison engine lets you pit up to three devices against each other to see exactly how they stack up.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h4 class="fw-bold text-dark"><i class="bi bi-shield-check-fill text-primary"></i> User Insights</h4>
                    <p>Read authentic reviews and ratings from real users to understand the daily performance and reliability of your next phone.</p>
                </div>
            </div>

            <p class="mt-5">Since our inception, MKS has grown from a local price-tracking tool to a global mobile encyclopedia. We are driven by a passion for technology and a commitment to our community of mobile enthusiasts.</p>
            
            <p class="fw-bold text-dark mt-4">Thank you for choosing MobileKiShop as your trusted mobile guide.</p>
        </div>
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