@extends('layouts.frontend')

@section('title', $metas->title)
@section('description', $metas->description)
@section("canonical", $metas->canonical)

@section("content")
    <main class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
        <div class="bg-surface-card rounded-2xl shadow-sm p-6 md:p-10 text-center">
            <h1 class="mb-6 text-2xl md:text-3xl font-bold text-primary">{{ $metas->h1 }}</h1>
            <div class="text-text-muted leading-relaxed mx-auto max-w-[800px] space-y-4">
                <p class="text-lg text-text-main mb-4">Empowering Mobile Shoppers with Transparency and Accuracy.</p>

                <p>At <strong>MobileKiShop (MKS)</strong>, our mission is simple: to be the most trusted and comprehensive
                    source for mobile phone information in {{ $country->country_name }} and beyond. We understand that
                    choosing a new smartphone is a significant decision, and we are here to make it easier, faster, and more
                    informed.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 text-left">
                    <div>
                        <h4 class="font-bold text-text-main flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-primary text-xl">sell</span>
                            Real-Time Pricing
                        </h4>
                        <p>We aggregate and update mobile prices from multiple retailers to ensure you get the best deal
                            available in the market.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-text-main flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-primary text-xl">memory</span>
                            Expert Specs
                        </h4>
                        <p>From processor clock speeds to camera sensor details, we provide in-depth technical
                            specifications for every device.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-text-main flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-primary text-xl">compare_arrows</span>
                            Dynamic Comparison
                        </h4>
                        <p>Our powerful comparison engine lets you pit up to three devices against each other to see exactly
                            how they stack up.</p>
                    </div>
                    <div>
                        <h4 class="font-bold text-text-main flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-primary text-xl">verified_user</span>
                            User Insights
                        </h4>
                        <p>Read authentic reviews and ratings from real users to understand the daily performance and
                            reliability of your next phone.</p>
                    </div>
                </div>

                <p class="mt-8">Since our inception, MKS has grown from a local price-tracking tool to a global mobile
                    encyclopedia. We are driven by a passion for technology and a commitment to our community of mobile
                    enthusiasts.</p>

                <p class="font-bold text-text-main mt-4">Thank you for choosing MobileKiShop as your trusted mobile guide.
                </p>
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
                "name": "{{$metas->name}}"
              }]
            }
            </script>
@endsection