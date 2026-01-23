@extends("layouts.frontend")

@section('title',$metas->title)

@section('description',$metas->description)

@section("keywords","Mobiles prices, mobile specification, mobile phone features ")

@section("canonical",\URL::full())

@section("og_graph") @stop

@section("content")
@if(!$product1)
    <meta name="robots" content="noindex" />
@endif

@php
$price_in_pkr    = $product->price_in_pkr;
$price_in_dollar = $product->price_in_dollar;

$attributes = $product->attributes()->get()->keyBy('attribute_id');

$os              = optional($attributes->get(183))->value;
$ui              = optional($attributes->get(184))->value;
$dimensions      = optional($attributes->get(185))->value;
$weight          = optional($attributes->get(189))->value;
$sim             = optional($attributes->get(190))->value;
$colors          = optional($attributes->get(191))->value;
$g2_band         = optional($attributes->get(192))->value;
$g3_band         = optional($attributes->get(193))->value;
$g4_band         = optional($attributes->get(194))->value;
$g5_band         = optional($attributes->get(195))->value;
$cpu             = optional($attributes->get(196))->value;
$chipset         = optional($attributes->get(197))->value;
$gpu             = optional($attributes->get(198))->value;
$technology      = optional($attributes->get(199))->value;
$size            = optional($attributes->get(200))->value;
$resolution      = optional($attributes->get(201))->value;
$protection      = optional($attributes->get(202))->value;
$extra_features  = optional($attributes->get(203))->value;
$built_in        = optional($attributes->get(204))->value;
$buildd          = optional($attributes->get(206))->value; 
$card            = optional($attributes->get(207))->value;
$main            = optional($attributes->get(208))->value;
$sms             = optional($attributes->get(209))->value;
$features        = optional($attributes->get(210))->value;
$front           = optional($attributes->get(211))->value;
$wlan            = optional($attributes->get(212))->value;
$bluetooth       = optional($attributes->get(213))->value;
$gps             = optional($attributes->get(214))->value;
$radio           = optional($attributes->get(215))->value;
$usb             = optional($attributes->get(216))->value;
$nfc             = optional($attributes->get(217))->value;
$infrared        = optional($attributes->get(218))->value;
$data            = optional($attributes->get(219))->value;
$sensors         = optional($attributes->get(220))->value;
$audio           = optional($attributes->get(221))->value;
$browser         = optional($attributes->get(222))->value;
$messaging       = optional($attributes->get(223))->value;
$games           = optional($attributes->get(226))->value;
$torch           = optional($attributes->get(227))->value;
$extra           = optional($attributes->get(228))->value;
$capacity        = optional($attributes->get(229))->value;
$body            = optional($attributes->get(230))->value;
$battery         = optional($attributes->get(231))->value;
$pixels          = optional($attributes->get(236))->value;
$screen_size     = optional($attributes->get(238))->value;
$ram_in_gb       = optional($attributes->get(239))->value;
$rom_in_gb       = optional($attributes->get(240))->value;
$release_date    = optional($attributes->get(243))->value;
$reviewer        = optional($attributes->get(249))->value;
$rating          = optional($attributes->get(250))->value;
$review_count    = optional($attributes->get(251))->value;

if($product1){
    $price_in_pkr1    = $product1->price_in_pkr;
$price_in_dollar1 = $product1->price_in_dollar;

$attributes1 = $product1->attributes()->get()->keyBy('attribute_id');

$os1              = optional($attributes1->get(183))->value;
$ui1              = optional($attributes1->get(184))->value;
$dimensions1      = optional($attributes1->get(185))->value;
$weight1          = optional($attributes1->get(189))->value;
$sim1             = optional($attributes1->get(190))->value;
$colors1          = optional($attributes1->get(191))->value;
$g2_band1         = optional($attributes1->get(192))->value;
$g3_band1         = optional($attributes1->get(193))->value;
$g4_band1         = optional($attributes1->get(194))->value;
$g5_band1         = optional($attributes1->get(195))->value;
$cpu1             = optional($attributes1->get(196))->value;
$chipset1         = optional($attributes1->get(197))->value;
$gpu1             = optional($attributes1->get(198))->value;
$technology1      = optional($attributes1->get(199))->value;
$size1            = optional($attributes1->get(200))->value;
$resolution1      = optional($attributes1->get(201))->value;
$protection1      = optional($attributes1->get(202))->value;
$extra_features1  = optional($attributes1->get(203))->value;
$built_in1        = optional($attributes1->get(204))->value;
$buildd1          = optional($attributes1->get(206))->value; 
$card1            = optional($attributes1->get(207))->value;
$main1            = optional($attributes1->get(208))->value;
$sms1             = optional($attributes1->get(209))->value;
$features1        = optional($attributes1->get(210))->value;
$front1           = optional($attributes1->get(211))->value;
$wlan1            = optional($attributes1->get(212))->value;
$bluetooth1       = optional($attributes1->get(213))->value;
$gps1             = optional($attributes1->get(214))->value;
$radio1           = optional($attributes1->get(215))->value;
$usb1             = optional($attributes1->get(216))->value;
$nfc1             = optional($attributes1->get(217))->value;
$infrared1        = optional($attributes1->get(218))->value;
$data1            = optional($attributes1->get(219))->value;
$sensors1         = optional($attributes1->get(220))->value;
$audio1           = optional($attributes1->get(221))->value;
$browser1         = optional($attributes1->get(222))->value;
$messaging1       = optional($attributes1->get(223))->value;
$games1           = optional($attributes1->get(226))->value;
$torch1           = optional($attributes1->get(227))->value;
$extra1           = optional($attributes1->get(228))->value;
$capacity1        = optional($attributes1->get(229))->value;
$body1            = optional($attributes1->get(230))->value;
$battery1         = optional($attributes1->get(231))->value;
$pixels1          = optional($attributes1->get(236))->value;
$screen_size1     = optional($attributes1->get(238))->value;
$ram_in_gb1       = optional($attributes1->get(239))->value;
$rom_in_gb1       = optional($attributes1->get(240))->value;
$release_date1    = optional($attributes1->get(243))->value;
$reviewer1        = optional($attributes1->get(249))->value;
$rating1          = optional($attributes1->get(250))->value;
$review_count1    = optional($attributes1->get(251))->value;

}
if($product2){
    $price_in_pkr2    = $product2->price_in_pkr;
$price_in_dollar2 = $product2->price_in_dollar;

$attributes2 = $product2->attributes()->get()->keyBy('attribute_id');

$os2              = optional($attributes2->get(183))->value;
$ui2              = optional($attributes2->get(184))->value;
$dimensions2      = optional($attributes2->get(185))->value;
$weight2          = optional($attributes2->get(189))->value;
$sim2             = optional($attributes2->get(190))->value;
$colors2          = optional($attributes2->get(191))->value;
$g2_band2         = optional($attributes2->get(192))->value;
$g3_band2         = optional($attributes2->get(193))->value;
$g4_band2         = optional($attributes2->get(194))->value;
$g5_band2         = optional($attributes2->get(195))->value;
$cpu2             = optional($attributes2->get(196))->value;
$chipset2         = optional($attributes2->get(197))->value;
$gpu2             = optional($attributes2->get(198))->value;
$technology2      = optional($attributes2->get(199))->value;
$size2            = optional($attributes2->get(200))->value;
$resolution2      = optional($attributes2->get(201))->value;
$protection2      = optional($attributes2->get(202))->value;
$extra_features2  = optional($attributes2->get(203))->value;
$built_in2        = optional($attributes2->get(204))->value;
$buildd2          = optional($attributes2->get(206))->value; 
$card2            = optional($attributes2->get(207))->value;
$main2            = optional($attributes2->get(208))->value;
$sms2             = optional($attributes2->get(209))->value;
$features2        = optional($attributes2->get(210))->value;
$front2           = optional($attributes2->get(211))->value;
$wlan2            = optional($attributes2->get(212))->value;
$bluetooth2       = optional($attributes2->get(213))->value;
$gps2             = optional($attributes2->get(214))->value;
$radio2           = optional($attributes2->get(215))->value;
$usb2             = optional($attributes2->get(216))->value;
$nfc2             = optional($attributes2->get(217))->value;
$infrared2        = optional($attributes2->get(218))->value;
$data2            = optional($attributes2->get(219))->value;
$sensors2         = optional($attributes2->get(220))->value;
$audio2           = optional($attributes2->get(221))->value;
$browser2         = optional($attributes2->get(222))->value;
$messaging2       = optional($attributes2->get(223))->value;
$games2           = optional($attributes2->get(226))->value;
$torch2           = optional($attributes2->get(227))->value;
$extra2           = optional($attributes2->get(228))->value;
$capacity2        = optional($attributes2->get(229))->value;
$body2            = optional($attributes2->get(230))->value;
$battery2         = optional($attributes2->get(231))->value;
$pixels2          = optional($attributes2->get(236))->value;
$screen_size2     = optional($attributes2->get(238))->value;
$ram_in_gb2       = optional($attributes2->get(239))->value;
$rom_in_gb2       = optional($attributes2->get(240))->value;
$release_date2    = optional($attributes2->get(243))->value;
$reviewer2        = optional($attributes2->get(249))->value;
$rating2          = optional($attributes2->get(250))->value;
$review_count2    = optional($attributes2->get(251))->value;

}
@endphp

<style type="text/css">
    .card:hover {
        box-shadow: 0 25px 35px -5px rgb(0 0 0 / 10%) !important;
    }
    .twitter-typeahead{
        width: 100% !important;
    }
    .tt-menu{
        width: inherit !important;
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
    .mobileImage{
        height:150px !important;
    }
    h1{
        margin-bottom: 1.8rem;
        color: #222529;
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins,sans-serif;
    }
    h1,h2,h3,h4{
        font-family: Poppins,sans-serif;
    }
    p i{
        width: 20px;
    }
    @media only screen and (max-width : 576px) {
        .cameraBlock{
            border-right: none !important;
        }
        .thirdMobile{
            display: none;
        }
        .thickness,.storage{
            display: none;
        }
        .mobileTable tr th{
            width: 85px !important;
            font-size: 14px;
        }
        .specTabs .nav-item{
            margin-left: auto !important;
            margin-right: auto !important;
        }
    }
    @media only screen and (max-width: 450px){
        .releaseDate, .androidVersion{
            display: none;
        }
    }
    .specOutline p{
        font-family: Poppins,sans-serif !important;
    }
    @media(max-width: 420px){
        .Price{
            margin-top:5px !important;
        }
    }
    .mobileTable tr td:first-child{ 
            display: none; 
        }
    .table-sm{
         table-layout: fixed ;
    }
    .mobileTable tr th{
        color: #dc3545 !important;
    }
    .dropdown-menu {
        width: 96% !important;
        background-color: #efefef !important;
        font-size: 1.5rem;        
    }
    .mobileTable tr td{
        word-wrap: break-word !important;
    }
    .compareBox{
        min-height: 235px !important;
    }
    .compareBox h2{
        /*min-height: 55px !important;*/
    }
    .compareBox img{
        height: 160px;
        width: 120px !important;
        max-height: 160px !important;
    }
    .searchImage{
        height: 110px !important;
    }
</style>
    <main class="main">
        <div class="container">
            <div class="row">
                
                <div class="col-md-3">
                    @include("includes.sidebar.".$product->category->slug)
                </div>

                <div class="col-md-9">
                    <div class="my-2 my-md-3">
                        <div class="row">
                            <div class="col-12">
                                <h1 class="mr-auto text-white bg-dark py-2 text-center fs-4 mb-2">{{($metas->h1) ? $metas->h1 : "Compare Mobiles"}}
                                </h1>
                            </div>

                            <div class="col-6 col-sm-6 col-md-4 text-center compareBox">
                                <div class="row px-2"> 
                                    <input type="hidden" name="input1" id="input-search1" value="{{$product->slug}}">
                                    <input type="hidden" name="input2" id="input-search2" value="{{isset($product1->slug) ? $product1->slug : ''}}">
                                    <input type="hidden" name="input3" id="input-search3">
                                    <input type="text" name="search" id="search1" class="form-control search" placeholder="Search Mobile" autocomplete="off" value="{{$product->name}}" readonly>
                                </div>
                                <a href="{{route('product.show',[$product->brand->slug,$product->slug])}}">
                                    <h2 class="text-dark fw-bold fs-6 my-2">{{Str::title($product->name)}}</h2>
                                    <img src="{{URL::to('/images/thumbnail.png')}}" data-echo="{{$product->thumbnail}}" class="img-fluid w-auto mx-auto" alt="{{$product->slug}}">
                                </a>
                                
                            </div>
                            <div class="col-6 col-sm-6 col-md-4 text-center compareBox">
                                <div class="row px-2">
                                    <input type="text" name="search1" id="search2" class="form-control search" placeholder="Search Mobile" autocomplete="off">
                                </div>
                                <a href="{{ isset($product1) ? route('product.show',[$product1->brand->slug,$product1->slug]) : '#'}}">
                                    <h2 class="text-dark fw-bold fs-6 my-2">{{isset($product1->name) ? Str::title($product1->name) : "Compare With"}}</h2>
                                
                                    @if(isset($product1))
                                        <img src="{{URL::to('/images/thumbnail.png')}}" data-echo="{{$product1->thumbnail}}" class="img-fluid w-auto mx-auto" alt="{{$product1->slug}}">
                                    @endif
                                </a>
                            </div>
                            <div class="col-6 col-sm-6 col-md-4 d-none d-md-block compareBox text-center">
                                <div class="row px-2">
                                    <input type="text" name="search2" class="form-control search" placeholder="Search Mobile" id="search3">
                                </div>
                                <a href="{{ isset($product2) ? route('product.show',[$product2->brand->slug,$product2->slug]) : '#'}}">
                                    <h2 class="text-dark fw-bold fs-6 my-2">{{isset($product2->name) ? Str::title($product2->name) : ""}}</h2>
                                </a>
                                @if(isset($product2))
                                <img src="{{$product2->thumbnail}}" alt="{{$product2->slug}}" class="img-fluid w-auto mx-auto">
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-resposive">
                        <table border="0" class="table mobileTable table-bordered table-sm w-100 bg-light text-dark" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr class="border-bottom-eee">
                                <th>Date</th>
                                <td colspan="2">{{ date("M Y", strtotime($release_date)) }}</td>
                                <td colspan="2">{{ isset($release_date1) ? date("M Y", strtotime($release_date1)) : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($release_date2) ? date("M Y", strtotime($release_date2)) : '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Price</td>
                                <th>Price In Rs</th>
                                <td colspan="2">{{ "PKR ".number_format($product->price_in_pkr) }}</td>
                                <td colspan="2">{{ isset($product1->price_in_pkr) ? "PKR ".number_format($product1->price_in_pkr) : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($product2->price_in_pkr) ? "PKR ".number_format($product2->price_in_pkr) : '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Price In $</th>
                                <td colspan="2">{{ "$".$product->price_in_dollar }}</td>
                                <td colspan="2">{{ isset($product1->price_in_dollar) ? "$".$product1->price_in_dollar : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($product2->price_in_dollar) ? "$".$product2->price_in_dollar : '' }}</td>
                            </tr>
                            <tr>
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee" rowspan="6">Build</td>
                                <th>OS</th>
                                <td colspan="2">{{ $os }}</td>
                                <td colspan="2">{{ isset($os1) ? $os1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($os2) ? $os2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>UI</th>
                                <td colspan="2">{{ $ui }}</td>
                                <td colspan="2">{{ isset($ui1) ? $ui1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($ui2) ? $ui2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td colspan="2">{{ $dimensions }}</td>
                                <td colspan="2">{{ isset($dimensions1) ? $dimensions1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($dimensions2) ? $dimensions2 : '' }}</td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td colspan="2">{{ $weight }}</td>
                                <td colspan="2">{{ isset($weight1) ? $weight1 : '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ isset($weight2) ? $weight2 : '' }}</td>
                            </tr>

                            <tr>
                                <th>SIM</th>
                                <td colspan="2">{{ $sim }}</td>
                                <td colspan="2">{{ $sim1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $sim2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Colors</th>
                                <td colspan="2">{{ $colors }}</td>
                                <td colspan="2">{{ $colors1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $colors2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="4" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Frequency</td>
                                <th>2G Band</th>
                                <td colspan="2">{{ $g2_band }}</td>
                                <td colspan="2">{{ $g2_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g2_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>3G Band</th>
                                <td colspan="2">{{ $g3_band }}</td>
                                <td colspan="2">{{ $g3_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g3_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>4G Band</th>
                                <td colspan="2">{{ $g4_band }}</td>
                                <td colspan="2">{{ $g4_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g4_band2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>5G Band</th>
                                <td colspan="2">{{ $g5_band }}</td>
                                <td colspan="2">{{ $g5_band1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $g5_band2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Processor</td>
                                <th>CPU</th>
                                <td colspan="2">{{ $cpu }}</td>
                                <td colspan="2">{{ $cpu1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $cpu2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Chipset</th>
                                <td colspan="2">{{ $chipset }}</td>
                                <td colspan="2">{{ $chipset1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $chipset2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>GPU</th>
                                <td colspan="2">{{ $gpu }}</td>
                                <td colspan="2">{{ $gpu1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $gpu2 ?? '' }}</td>
                            </tr>

                            <tr>
                                <td rowspan="5" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Display</td>
                                <th>Technology</th>
                                <td colspan="2">{{ $technology }}</td>
                                <td colspan="2">{{ $technology1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $technology2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td colspan="2">{{ $size }}</td>
                                <td colspan="2">{{ $size1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $size2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Resolution</th>
                                <td colspan="2">{{ $resolution }}</td>
                                <td colspan="2">{{ $resolution1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $resolution2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Protection</th>
                                <td colspan="2">{{ $protection }}</td>
                                <td colspan="2">{{ $protection1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $protection2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra Features</th>
                                <td colspan="2">{{ $extra_features }}</td>
                                <td colspan="2">{{ $extra_features1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $extra_features2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Memory</td>
                                <th>Built-in</th>
                                <td colspan="2">{{ $built_in }}</td>
                                <td colspan="2">{{ $built_in1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $built_in2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Card</th>
                                <td colspan="2">{{ $card }}</td>
                                <td colspan="2">{{ $card1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $card2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="3" class="text-danger text-uppercase font-weight-bold v-align-middle">Camera</td>
                                <th>Main</th>
                                <td colspan="2">{{ $main }}</td>
                                <td colspan="2">{{ $main1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $main2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Features</th>
                                <td colspan="2">{{ $features }}</td>
                                <td colspan="2">{{ $features1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $features2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Front</th>
                                <td colspan="2">{{ $front }}</td>
                                <td colspan="2">{{ $front1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $front2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="7" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Connectivity</td>
                                <th>WLAN</th>
                                <td colspan="2">{{ $wlan }}</td>
                                <td colspan="2">{{ $wlan1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $wlan2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Bluetooth</th>
                                <td colspan="2">{{ $bluetooth }}</td>
                                <td colspan="2">{{ $bluetooth1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $bluetooth2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>GPS</th>
                                <td colspan="2">{{ $gps }}</td>
                                <td colspan="2">{{ $gps1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $gps2 ?? '' }}</td>
                            </tr>

                            <tr>
                                <th>Radio</th>
                                <td colspan="2">{{ $radio }}</td>
                                <td colspan="2">{{ $radio1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $radio2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>USB</th>
                                <td colspan="2">{{ $usb }}</td>
                                <td colspan="2">{{ $usb1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $usb2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>NFC</th>
                                <td colspan="2">{{ $nfc }}</td>
                                <td colspan="2">{{ $nfc1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $nfc2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Data</th>
                                <td colspan="2">{{ $data }}</td>
                                <td colspan="2">{{ $data1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $data2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <td rowspan="7" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Features</td>
                                <th>Sensors</th>
                                <td colspan="2">{{ $sensors }}</td>
                                <td colspan="2">{{ $sensors1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $sensors2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Audio</th>
                                <td colspan="2">{{ $audio }}</td>
                                <td colspan="2">{{ $audio1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $audio2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Browser</th>
                                <td colspan="2">{{ $browser }}</td>
                                <td colspan="2">{{ $browser1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $browser2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Messaging</th>
                                <td colspan="2">{{ $messaging }}</td>
                                <td colspan="2">{{ $messaging1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $messaging2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Games</th>
                                <td colspan="2">{{ $games }}</td>
                                <td colspan="2">{{ $games1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $games2 ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Torch</th>
                                <td colspan="2">{{ $torch }}</td>
                                <td colspan="2">{{ $torch1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $torch2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra</th>
                                <td colspan="2">{{ $extra }}</td>
                                <td colspan="2">{{ $extra1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $extra2 ?? '' }}</td>
                            </tr>
                            <tr class="border-bottom-eee">
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle">Battery</td>
                                <th>Capacity</th>
                                <td colspan="2">{{ $capacity }}</td>
                                <td colspan="2">{{ $capacity1 ?? '' }}</td>
                                <td colspan="2" class="thirdMobile">{{ $capacity2 ?? '' }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            
        </div><!-- End .container -->
    </main><!-- End .main -->
@stop


@section("script")
<script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js" ></script>
    <script type="text/javascript">
        var route = "{{ route('autocomplete.search') }}";
        // Set the Options for "Bloodhound" suggestion engine
        var engine = new Bloodhound({
            remote: {
                url: route+"?query=%QUERY%&category_id={{$product->category->id}}",
                wildcard: '%QUERY%'
            },
            datumTokenizer: Bloodhound.tokenizers.whitespace('query'),
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        $(".search").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: engine.ttAdapter(),
            limit: 10+1,

            // This will be appended to "tt-dataset-" to form the class name of the suggestion menu.
            name: 'usersList',
            displayKey: 'name',
            // the key from the array we want to display (name,id,email,etc...)
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                header: [
                    '<div class="list-group search-results-dropdown">'
                ],
                suggestion: function (data) {
                     return '<div class="row bg-white border-bottom"><div class="col-4"><img src="'+data.thumbnail+'" class="img-fluid searchImage my-1"></div><div class="col-8 text-uppercase" style="font-weight:600;">'+data.name+'</div></div>'
          }
            }
        });

        $('.search').bind('typeahead:select', function(ev, suggestion) {
            console.log('Selection: ' + $(this).attr("id"));
            $("#input-"+$(this).attr("id")).val(suggestion.slug);
            var base_url = "{{URL::to('/compare/')}}/";
            var param1 = "";
            var param2 = "";
            var param3 = ""; 
            var loc = "";
            if($("#input-search1").val() != ""){
                param1 = $("#input-search1").val();
            }
            if($("#input-search2").val() != ""){
                param2 = "-vs-"+$("#input-search2").val();
            }
            if($("#input-search3").val() != ""){
                param3 = "-vs-"+$("#input-search3").val();
            }
            console.log(base_url+param1+param2+param3);
            loc = base_url+param1+param2+param3;
            window.location.replace(loc);
        });
    </script>
@stop

@section("style")

@stop