@php
    $layout = ($country->country_code == 'pk') ? 'layouts.frontend' : 'layouts.frontend_country';
@endphp

@extends($layout)

@section('title', $metas->title)

@section('description', $metas->description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features")

@section("canonical",$metas->canonical)

@section("og_graph") @stop

@section("noindex",)
@if(str_contains(URL::full(), '?page='))
<meta name="robots" content="noindex">
@endif
@stop
@section("content")

<style type="text/css">
    .mobileImage{
        height:150px !important;
    }
    .offcanvas-backdrop{
        background: #FFF!important;
    }
    .widget a{
        text-decoration: none !important;
        color: #777;
    }
    .widget-title a{
        text-decoration: none !important;
        font-family: Poppins,sans-serif;
        color: #343a40;
        font-size: 18px;
    }
    .widget{
        border-bottom: 1px solid #e7e7e7;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        border-bottom-color: rgb(231, 231, 231);
        border: 1px solid #dee2e6!important;
        margin-top: 5px;
        margin-right: 5px;
        margin-left: 5px;
    }
    .widget-body li a{
        font-size: 14px;
    }
    .nav-tabs.nav-item a{
        text-decoration: none !important;
        color: #343a40;
    }
    .nav-tabs.nav-tabs .nav-link{
        color: #31343a;
        border: none;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: black !important;
        border-bottom: 2px solid #000000 !important;
    }
    .nav-tabs.nav-link:hover{
        border-bottom: 2px solid #000000 !important;
    }
    h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins,sans-serif;
    }
    body{
        font-family: "Open Sans",sans-serif;
    }
    @media(max-width: 576px){
        .cameraBlock{
            border: none !important;
            border-bottom: 1px solid #dee2e6!important;
        }
        .screenBlock{
            border-bottom: 1px solid #dee2e6!important;
        }
        .mobileTable tr td:first-child{ 
            display: none; 
        }
        .mobileTable tr th{
            color: #dc3545!important
        }
        .table{
            font-size: .8rem;
        }
        .imgDiv{
            height: 120px;
        }
        .detailDiv{
            height: 120px;
        }

        .product-title{
            font-size: 14px;
            font-weight: normal;
        }
        .product-price{
            font-size: 16px;
        }
    }
    @media(min-width: 577px){
        .imgDiv{
            height: 130px;
        }
        .detailDiv{
            height: 130px;
        }
        .product-title{
            font-size: 18px;
            font-weight: normal;
        }
        .product-price{
            font-size: 20px;
        }
    }
    .nav-tabs .nav-link{
        font-size: .9rem;
        padding-right: 7px;
        padding-left: 7px;
    }
    .mobile_image{
        max-height: 160px;
        width: auto;
    }
    .product-title > a{
        text-decoration: none;
    }
    .category > a{
        text-decoration: none;
    }
    .product-label{
        animation: label-groups 2s infinite;
        padding: 3px 6px;
        background-color: #fe5858;
        font-size: 11px;
        color: white;
        border-radius: 20px;
    }
    .label-groups{
        position: absolute;
        top: -0.3rem;
        right: 1.0rem;
    }
    @keyframes label-groups{
        0%      { background-color: #ed6161}
        /*25%     { background-color: #1056c0;}*/
        50%     { background-color: #ed6161;}
        /*75%     { background-color: #254878;}*/
        100%    { background-color: #7661ed;}
    }
    .product-default .group-new .product-label{
        font-size: 8px;
        animation: group-new 2s infinite;
    }
    @keyframes group-new{
        0%      { background-color: #8abf6f}
        /*25%     { background-color: #1056c0;}*/
        /*50%     { background-color: #ed6161;}*/
        /*75%     { background-color: #254878;}*/
        100%    { background-color: #3e8f15;}
    }
    .page-link {
        color: #000000 !important;
    }
</style>
<main class="main container-lg">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb pt-sm-1">
                    <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="16">
                    </a></li>
                    <li class="breadcrumb-item active text-secondary" aria-current="page">{{isset($brand->name) ? Str::title($brand->name) : $metas->name}}</li>
                </ol>
            </div>
        </nav>

        <div class="container">
        <div class="row">
            <div class="col-12 col-md-3 pe-1">
                @php 
                    $category = \App\Category::find(1);
                @endphp
                @include("includes.sidebar_".$category->slug, ['category' => $category])
            </div>
            <div class="col-12 col-md-9">
                <div class="row">
            		<h1 class="text-center">About Us</h1>
                    <p>Welcome to <strong>Mobile Ki Shop (MKS)</strong>, Pakistan's leading platform for all things mobile. Previously known as Mobile Ki Site (MobileKiSite), MKS has become the go-to destination for broad and up-to-date information on  
                        @if($categories = App\Category::where("is_active", 1)->get())
                            @foreach ($categories as $key => $category)
                                @if ($key > 0)
                                    ,
                                @endif
                                <a href="{{ url('/category/' . $category->slug) }}">{{ $category->category_name }}</a>
                            @endforeach
                        @endif.
                    </p>

                    <p>Launched in <strong>January 2022</strong> by the premier digital marketing agency <strong>Marketers.pk</strong>, MKS aims to empower users by providing detailed specifications, features, reviews, and price comparisons to make well-informed purchasing decisions. Thanks to the dedication of our passionate team, MKS has earned widespread positive feedback from users, further inspiring us to continuously improve and expand our offerings.</p>
                    <h2>Why Choose MKS?</h2>
                    <p>Our dedicated team works tirelessly to:</p>
                    <ul>
                    <li aria-level="1"><strong>Keep You Updated</strong>: Regularly update prices, specifications, and information on upcoming products, including smartphones, smartwatches, and tablets.</li>
                    <li aria-level="1"><strong>Assist Users</strong>: Offering personalized support to help users make decisions or find the necessary information. We always prioritize our users&rsquo; convenience and satisfaction.</li>
                    <li aria-level="1"><strong>Collaborate with Renowned Brands</strong>: Esteemed companies like <strong>Tecno</strong> and <strong>Xiaomi</strong> have utilized our platform to feature their products prominently on our pages, showcasing our credibility and influence in the industry.</li>
                    <li aria-level="1"><strong>Expand Industry Connections</strong>: Continuously approach emerging and established tech brands to feature their products and build strategic alliances, strengthening our position as a trusted platform in the mobile technology space.</li>
                    </ul>
                    <h2>Comprehensive Blogs and Guides</h2>
                    <p>Our platform features an extensive blog section covering the latest news and updates in mobile technology. From in-depth reviews of new devices to guides on various tech-related topics, we ensure our content is engaging and informative. Additionally, we provide insights into mobile apps, featuring app suggestions tailored to specific user needs, how-to guides, and other valuable information.</p>
                    <h2>Expanding Our Reach</h2>
                    <p>We&rsquo;re not just a website &ndash; MKS is a complete digital ecosystem:</p>
                    <ul>
                    <li aria-level="1"><strong>Social Media</strong>: Our social channels actively share the latest updates, news, and other relevant material, keeping you informed on the go.</li>
                    <li aria-level="1"><strong>YouTube</strong>: On our YouTube platform, we bring mobile technology to life through unboxing videos, reviews, and tips &amp; tricks. We also cover mobile apps, offering detailed guides and insights to enhance your tech experience.</li>
                    </ul>
                    <h2>Our Global Reach</h2>
                    <p>We proudly serve a global audience, diligently gathering up-to-date information and accurate data for countries such as the 
                    @if($countries = App\Country::where("is_menu",1)->get())
                    @foreach ($countries as $key => $country)
                        @if ($key > 0)
                            , 
                        @endif
                        <a href="{{ $country->domain }}">{{ $country->country_name }}</a>
                    @endforeach
                    @endif. 
                    By expanding our reach, we aim to provide a reliable resource that helps users worldwide stay informed about the latest developments in mobile technology. Our ultimate goal is to assist people in making confident and well-informed decisions, no matter where they are.</p>
                    <h2>Our Mission</h2>
                    <p>At <strong>Mobile Ki Shop</strong>, our mission is simple: to be your one-stop resource for everything you need to know about mobile technology. With your support and our team&rsquo;s unwavering dedication, we strive to become a household name synonymous with reliable and accessible information in the world of smartphones, tablets, smartwatches, and accessories.</p>
                    <p>For inquiries or support, feel free to contact us via our website's contact form. Experience the MKS difference today!</p>
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
    "name": "{{$metas->name}}",
    "item": "{{$metas->canonical}}"  
  }]
}
</script>
@stop

@section("style")
<style type="text/css">
    .filter-select{
        height: 4rem !important;
    }
    .icon-angle-right{
        background: #928989ad;
        margin-left: 10px;
        padding-left: 15px !important;
        padding-right: 12px !important;
        padding-bottom: 3px !important;
    }
    .icon-angle-left{
        background: #928989ad;
        margin-left: 10px;
        padding-left: 12px !important;
        padding-right: 15px !important;
        padding-bottom: 3px !important;   
    }
    
    .select-filter:after {
        margin-top: 8px !important;
    }
    #sort_filter:after {
        margin-top: -1px !important;
    }
    
    .fs-12{
        font-size: 12px !important;
    }
    .fs-14{
        font-size: 14px !important;
    }
    .fs-15{
        font-size: 15px !important;
    }
    .fs-16{
        font-size: 16px !important;
    }
</style>
@stop