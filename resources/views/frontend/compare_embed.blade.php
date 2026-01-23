<link rel="stylesheet" href="{{URL::to('/')}}/css/bootstrap.min.css">
    <main class="main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-single-container product-single-default mb-2">
                        <div class="row">
                            <div class="col-6 col-sm-6 col-md-6 text-center">
                                <h2 class="mr-auto section-title ls-n-20 m-b-1 line-height-1 appear-animate fadeIn animated appear-animation-visible text-white bg-dark py-2 text-center" style="font-size:20px;margin-bottom: .5rem;">{{Str::title($mobile->name)}}</h2>
                                <img src="{{$mobile->thumbnail}}" class="img-fluid w-auto mx-auto" style="max-height: 160px;" alt="{{$mobile->slug}}">
                            </div>
                            <div class="col-6 col-sm-6 col-md-6 text-center">
                                <h2 class="section-title ls-n-20 m-b-1 line-height-1 appear-animate fadeIn animated appear-animation-visible text-white bg-dark py-2 text-center" style="font-size:20px;margin-bottom: .5rem;">{{isset($mobile1->name) ? Str::title($mobile1->name) : "Compare With"}}</h2>
                                @if(isset($mobile1))
                                <img src="{{$mobile1->thumbnail}}" class="img-fluid w-auto mx-auto" style="max-height: 160px;" alt="{{$mobile1->slug}}">
                                @endif
                            </div>
                            @if($mobile2)
                            <div class="col-6 col-sm-6 col-md-4 d-none d-md-block">
                                <h2 class="section-title ls-n-20 m-b-1 line-height-1 appear-animate fadeIn animated appear-animation-visible text-white bg-dark py-2 text-center" style="font-size:20px;margin-bottom: .5rem;">{{isset($mobile2->name) ? Str::title($mobile2->name) : "Compare With"}}</h2>
                                @if(isset($mobile2))
                                <img src="{{$mobile2->thumbnail}}" class="img-fluid w-auto mx-auto" style="max-height: 160px;" alt="{{$mobile2->slug}}">
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="table-resposive">
                        <table border="0" class="table mobileTable table-bordered table-sm w-100 bg-light text-dark" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr class="border-bottom-eee">
                                <th>Date</th>
                                <td colspan="2" {{ date("M Y", strtotime($mobile->release_date)) }}</td>
                                @if(isset($mobile1->release_date))
                                <td colspan="2">{{ date("M Y", strtotime($mobile1->release_date)) }}</td>
                                @endif
                                @if(isset($mobile2->release_date))
                                <td colspan="2" class="thirdMobile">{{ date("M Y", strtotime($mobile2->release_date)) }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="2" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Price</td>
                                <th>Price In Rs</th>
                                <td colspan="2">{{ "PKR ".number_format($mobile->price_in_pkr) }}</td>
                                @if(isset($mobile1->price_in_pkr))
                                <td colspan="2">{{ "PKR ".number_format($mobile1->price_in_pkr) }}</td>
                                @endif
                                @if(isset($mobile2->price_in_pkr))
                                <td colspan="2" class="thirdMobile">{{ "PKR ".number_format($mobile2->price_in_pkr) }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Price In $</th>
                                <td colspan="2">{{ "$".$mobile->price_in_dollar }}</td>
                                @if(isset($mobile1->price_in_dollar))
                                <td colspan="2">{{ "$".$mobile1->price_in_dollar }}</td>
                                @endif
                                @if(isset($mobile2->price_in_dollar))
                                <td colspan="2" class="thirdMobile">{{ "$".$mobile2->price_in_dollar }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee" rowspan="6">Build</td>
                                <th>OS</th>
                                <td colspan="2">{{ $mobile->os }}</td>
                                @if(isset($mobile1->os))
                                <td colspan="2">{{ $mobile1->os }}</td>
                                @endif
                                @if(isset($mobile2->os))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->os }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>UI</th>
                                <td colspan="2">{{ $mobile->ui }}</td>
                                @if(isset($mobile1->ui))
                                <td colspan="2">{{ $mobile1->ui }}</td>
                                @endif
                                @if(isset($mobile2->ui))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->ui }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Dimensions</th>
                                <td colspan="2">{{ $mobile->dimensions }}</td>
                                @if(isset($mobile1->dimensions))
                                <td colspan="2">{{ $mobile1->dimensions }}</td>
                                @endif
                                @if(isset($mobile2->dimensions))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->dimensions }}</td>
                                @endif

                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td colspan="2">{{ $mobile->weight }}</td>
                                @if(isset($mobile1->weight))
                                <td colspan="2">{{ $mobile1->weight }}</td>
                                @endif
                                @if(isset($mobile2->weight))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->weight }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>SIM</th>
                                <td colspan="2">{{ $mobile->sim }}</td>
                                @if(isset($mobile1->sim))
                                <td colspan="2">{{ $mobile1->sim }}</td>
                                @endif
                                @if(isset($mobile2->sim))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->sim }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Colors</th>
                                <td colspan="2">{{ $mobile->colors }}</td>
                                @if(isset($mobile1->colors))
                                <td colspan="2">{{ $mobile1->colors }}</td>
                                @endif
                                @if(isset($mobile2->colors))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->colors }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="4" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Frequency</td>
                                <th>2G Band</th>
                                <td colspan="2">{{ $mobile['2g_band'] }}</td>
                                @if(isset($mobile1['2g_band']))
                                <td colspan="2">{{ $mobile1['2g_band'] }}</td>
                                @endif
                                @if(isset($mobile2['2g_band']))
                                <td colspan="2" class="thirdMobile">{{ $mobile2['2g_band'] }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>3G Band</th>
                                <td colspan="2">{{ $mobile['3g_band'] }}</td>
                                @if(isset($mobile1['3g_band']))
                                <td colspan="2">{{ $mobile1['3g_band'] }}</td>
                                @endif
                                @if(isset($mobile2['3g_band']))
                                <td colspan="2" class="thirdMobile">{{ $mobile2['3g_band'] }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>4G Band</th>
                                <td colspan="2">{{ $mobile['4g_band'] }}</td>
                                @if(isset($mobile1['4g_band']))
                                <td colspan="2">{{ $mobile1['4g_band'] }}</td>
                                @endif
                                @if(isset($mobile2['4g_band']))
                                <td colspan="2" class="thirdMobile">{{ $mobile2['4g_band'] }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>5G Band</th>
                                <td colspan="2">{{ $mobile['5g_band'] }}</td>
                                @if(isset($mobile1['5g_band']))
                                <td colspan="2">{{ $mobile1['5g_band'] }}</td>
                                @endif
                                @if(isset($mobile2['5g_band']))
                                <td colspan="2" class="thirdMobile">{{ $mobile2['5g_band'] }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="3" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Processor</td>
                                <th>CPU</th>
                                <td colspan="2">{{ $mobile->cpu }}</td>
                                @if(isset($mobile1->cpu))
                                <td colspan="2">{{ $mobile1->cpu }}</td>
                                @endif
                                @if(isset($mobile2->cpu))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->cpu }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Chipset</th>
                                <td colspan="2">{{ $mobile->chipset }}</td>
                                @if(isset($mobile1->chipset))
                                <td colspan="2">{{ $mobile1->chipset }}</td>
                                @endif
                                @if(isset($mobile2->chipset))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->chipset }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>GPU</th>
                                <td colspan="2">{{ $mobile->gpu }}</td>
                                @if(isset($mobile1->gpu))
                                <td colspan="2">{{ $mobile1->gpu }}</td>
                                @endif
                                @if(isset($mobile2->chipset))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->chipset }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="5" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Display</td>
                                <th>Technology</th>
                                <td colspan="2">{{ $mobile->technology }}</td>
                                @if(isset($mobile1->technology))
                                <td colspan="2">{{ $mobile1->technology }}</td>
                                @endif
                                @if(isset($mobile2->technology))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->technology }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Size</th>
                                <td colspan="2">{{ $mobile->size }}</td>
                                @if(isset($mobile1->size))
                                <td colspan="2">{{ $mobile1->size }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Resolution</th>
                                <td colspan="2">{{ $mobile->resolution }}</td>
                                @if(isset($mobile1->resolution))
                                <td colspan="2">{{ $mobile1->resolution }}</td>
                                @endif
                                @if(isset($mobile2->resolution))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->resolution }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Protection</th>
                                <td colspan="2">{{ $mobile->protection }}</td>
                                @if(isset($mobile1->protection))
                                <td colspan="2">{{ $mobile1->protection }}</td>
                                @endif
                                @if(isset($mobile2->protection))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->protection }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra Features</th>
                                <td colspan="2">{{ $mobile->extra_features }}</td>
                                @if(isset($mobile1->extra_features))
                                <td colspan="2">{{ $mobile1->extra_features }}</td>
                                @endif
                                @if(isset($mobile2->extra_features))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->extra_features }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="2" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Memory</td>
                                <th>Built-in</th>
                                <td colspan="2">{{ $mobile->built_in }}</td>
                                @if(isset($mobile1->built_in))
                                <td colspan="2">{{ $mobile1->built_in }}</td>
                                @endif
                                @if(isset($mobile2->built_in))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->built_in }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Card</th>
                                <td colspan="2">{{ $mobile->card }}</td>
                                @if(isset($mobile1->card))
                                <td colspan="2">{{ $mobile1->card }}</td>
                                @endif
                                @if(isset($mobile2->card))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->card }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="3" class="text-danger text-uppercase font-weight-bold v-align-middle">Camera</td>
                                <th>Main</th>
                                <td colspan="2">{{ $mobile->main }}</td>
                                @if(isset($mobile1->main))
                                <td colspan="2">{{ $mobile1->main }}</td>
                                @endif
                                @if(isset($mobile2->main))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->main }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Features</th>
                                <td colspan="2">{{ $mobile->features }}</td>
                                @if(isset($mobile1->features))
                                <td colspan="2">{{ $mobile1->features }}</td>
                                @endif
                                @if(isset($mobile2->features))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->features }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Front</th>
                                <td colspan="2">{{ $mobile->front }}</td>
                                @if(isset($mobile1->front))
                                <td colspan="2">{{ $mobile1->front }}</td>
                                @endif
                                @if(isset($mobile2->front))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->front }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="7" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Connectivity</td>
                                <th>WLAN</th>
                                <td colspan="2">{{ $mobile->wlan }}</td>
                                @if(isset($mobile1->wlan))
                                <td colspan="2">{{ $mobile1->wlan }}</td>
                                @endif
                                @if(isset($mobile2->wlan))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->wlan }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Bluetooth</th>
                                <td colspan="2">{{ $mobile->bluetooth }}</td>
                                @if(isset($mobile1->bluetooth))
                                <td colspan="2">{{ $mobile1->bluetooth }}</td>
                                @endif
                                @if(isset($mobile2->bluetooth))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->bluetooth }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>GPS</th>
                                <td colspan="2">{{ $mobile->gps }}</td>
                                @if(isset($mobile1->gps))
                                <td colspan="2">{{ $mobile1->gps }}</td>
                                @endif
                                @if(isset($mobile2->gps))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->gps }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Radio</th>
                                <td colspan="2">{{ $mobile->radio }}</td>
                                @if(isset($mobile1->radio))
                                <td colspan="2">{{ $mobile1->radio }}</td>
                                @endif
                                @if(isset($mobile2->radio))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->radio }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>USB</th>
                                <td colspan="2">{{ $mobile->usb }}</td>
                                @if(isset($mobile1->usb))
                                <td colspan="2">{{ $mobile1->usb }}</td>
                                @endif
                                @if(isset($mobile2->usb))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->usb }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>NFC</th>
                                <td colspan="2">{{ $mobile->nfc }}</td>
                                @if(isset($mobile1->nfc))
                                <td colspan="2">{{ $mobile1->nfc }}</td>
                                @endif
                                @if(isset($mobile2->nfc))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->nfc }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Data</th>
                                <td colspan="2">{{ $mobile->data }}</td>
                                @if(isset($mobile1->data))
                                <td colspan="2">{{ $mobile1->data }}</td>
                                @endif
                                @if(isset($mobile2->data))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->data }}</td>
                                @endif
                            </tr>
                            <tr>
                                <td rowspan="7" class="text-danger text-uppercase font-weight-bold v-align-middle border-bottom-eee">Features</td>
                                <th>Sensors</th>
                                <td colspan="2">{{ $mobile->sensors }}</td>
                                @if(isset($mobile1->sensors))
                                <td colspan="2">{{ $mobile1->sensors }}</td>
                                @endif
                                @if(isset($mobile2->sensors))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->sensors }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Audio</th>
                                <td colspan="2">{{ $mobile->audio }}</td>
                                @if(isset($mobile1->audio))
                                <td colspan="2">{{ $mobile1->audio }}</td>
                                @endif
                                @if(isset($mobile2->audio))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->audio }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Browser</th>
                                <td colspan="2">{{ $mobile->browser }}</td>
                                @if(isset($mobile1->browser))
                                <td colspan="2">{{ $mobile1->browser }}</td>
                                @endif
                                @if(isset($mobile2->browser))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->browser }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Messaging</th>
                                <td colspan="2">{{ $mobile->messaging }}</td>
                                @if(isset($mobile1->messaging))
                                <td colspan="2">{{ $mobile1->messaging }}</td>
                                @endif
                                @if(isset($mobile2->messaging))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->messaging }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Games</th>
                                <td colspan="2">{{ $mobile->games }}</td>
                                @if(isset($mobile1->games))
                                <td colspan="2">{{ $mobile1->games }}</td>
                                @endif
                                @if(isset($mobile2->games))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->games }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Torch</th>
                                <td colspan="2">{{ $mobile->torch }}</td>
                                @if(isset($mobile1->torch))
                                <td colspan="2">{{ $mobile1->torch }}</td>
                                @endif
                                @if(isset($mobile2->torch))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->torch }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <th>Extra</th>
                                <td colspan="2">{{ $mobile->extra }}</td>
                                @if(isset($mobile1->extra))
                                <td colspan="2">{{ $mobile1->extra }}</td>
                                @endif
                                @if(isset($mobile2->extra))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->extra }}</td>
                                @endif
                            </tr>
                            <tr class="border-bottom-eee">
                                <td class="text-danger text-uppercase font-weight-bold v-align-middle">Battery</td>
                                <th>Capacity</th>
                                <td colspan="2">{{ $mobile->capacity }}</td>
                                @if(isset($mobile1->capacity))
                                <td colspan="2">{{ $mobile1->capacity }}</td>
                                @endif
                                @if(isset($mobile2->capacity))
                                <td colspan="2" class="thirdMobile">{{ $mobile2->capacity }}</td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            
        </div><!-- End .container -->
    </main><!-- End .main -->


<style type="text/css">
    .twitter-typeahead{
        width: 100% !important;
    }
    .tt-menu{
        width: inherit !important;
    }
    .searchImage{
        height: 80px;
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
</style>
