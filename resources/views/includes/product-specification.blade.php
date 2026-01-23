<!-- specification div -->
<div class="row" id="specification">
    <div class="col-12">
        <h2>Specification</h2>
        <div class="product-desc-content mt-2">
            <div class="table-resposive">
                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr class="">
                            <td rowspan="2" class="text-danger text-uppercase fw-bold">Price</td>
                            <th>{{$country->currency}}</th>
                            <td colspan="2">{{ number_format($price_in_pkr) }}</td>
                        </tr>
                        <tr class="">
                            <th>Brand</th>
                            <td colspan="2"><a href="{{ url('/brand/'.$product->brand->slug.'/'.$product->category->slug) }}" class="text-decoration-none fw-bold">{{($product->brand->name)}}</a></td>
                        </tr>
                        <tr class="">
                            <td rowspan="1" class="text-danger text-uppercase fw-bold">Release</td>
                            <th>Date</th>
                            <td colspan="2">{{ date("M Y", strtotime($release_date)) }}</td>
                        </tr>
                    </tbody>
                </table>

                <table border="0" class="table custom-table  table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">BUILD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($os)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/the-evolution-of-mobile-operating-systems/" class="text-dark">OS</a></th>
                            <td colspan="2">{{ $os }}</td>
                        </tr>
                        @endif
                        @if($ui)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-ui/" class="text-dark">UI</a></th>
                            <td colspan="2">{{ $ui }}</td>
                        </tr>
                        @endif
                        @if($dimensions)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/dimensions/" class="text-dark">Dimensions</a></th>
                            <td colspan="2">{{ $dimensions }}</td>
                        </tr>
                        @endif
                        @if($weight)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-weight/" class="text-dark">Weight</a></th>
                            <td colspan="2">{{ $weight }}</td>
                        </tr>
                        @endif
                        @if($sim)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-a-sim/" class="text-dark">SIM</a></th>
                            <td colspan="2">{{ $sim }}</td>
                        </tr>
                        @endif
                        @if($colors)
                        <tr>
                            <th>Colors</th>
                            <td colspan="2">{{ $colors }}</td>
                        </tr>
                        @endif
                        @if($buildd)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-build/" class="text-dark">Build</a></th>
                            <td colspan="2">{{ $buildd }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6"><a href="https://mobilekishop.net/blog/mobile-networks-1g-to-5g/" class="text-danger">NETWORK</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($technology)
                        <tr>
                            <th>Technology</th>
                            <td colspan="2">{{ $technology }}</td>
                        </tr>
                        @endif
                        @if($g2_band)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/2g-mobile-network/" class="text-dark">2G Band</a></th>
                            <td colspan="2">{{ $g2_band }}</td>
                        </tr>
                        @endif
                        @if($g3_band)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/3g-mobile-network/" class="text-dark">3G Band</a></th>
                            <td colspan="2">{{ $g3_band }}</td>
                        </tr>
                        @endif
                        @if($g4_band)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/4g-mobile-network/" class="text-dark">4G Band</a></th>
                            <td colspan="2">{{ $g4_band }}</td>
                        </tr>
                        @endif
                        @if($g5_band)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/5g-mobile-network/" class="text-dark">5G Band</a></th>
                            <td colspan="2">{{ $g5_band }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="2" class="text-center text-danger bg-light fs-6">PROCESSOR</th>
                    </tr>
                </thead>
                <tbody>
                    @if($cpu)
                    <tr>
                        <th><a href="https://mobilekishop.net/blog/what-is-a-cpu/" class="text-dark">CPU</a></th>
                        <td colspan="2">{{ $cpu }}</td>
                    </tr>
                    @endif
                    @if($chipset)
                    <tr>
                        <th><a href="https://mobilekishop.net/blog/what-is-a-chipset/" class="text-dark">Chipset</a></th>
                        <td colspan="2">{{ $chipset }}</td>
                    </tr>
                    @endif
                    @if($gpu)
                    <tr>
                        <th><a href="https://mobilekishop.net/blog/what-is-a-gpu/" class="text-dark">GPU</a></th>
                        <td colspan="2">{{ $gpu }}</td>
                    </tr>
                    @endif
                </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6"><a href="https://mobilekishop.net/blog/mobile-display-technology/" class="text-danger">DISPLAY</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($display)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-display-type/" class="text-dark">Type</a></th>
                            <td colspan="2">{{ $display }}</td>
                        </tr>
                        @endif
                        @if($size)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-display-size/" class="text-dark">Size</a></th>
                            <td colspan="2">{{ $size }}</td>
                        </tr>
                        @endif
                        @if($resolution)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-resolution/" class="text-dark">Resolution</a></th>
                            <td colspan="2">{{ $resolution }}</td>
                        </tr>
                        @endif
                        @if($protection)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-screen-protection/" class="text-dark">Protection</a></th>
                            <td colspan="2">{{ $protection }}</td>
                        </tr>
                        @endif
                        @if($extra_features)
                        <tr>
                            <th>Extra Features</th>
                            <td colspan="2">{{ $extra_features }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6"><a href="https://mobilekishop.net/blog/what-is-rom/" class="text-danger">MEMORY</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($built_in)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-ram/" class="text-dark">Built-in</a></th>
                            <td colspan="2">{{ $built_in }}</td>
                        </tr>
                        @endif
                        @if($card)
                        <tr>
                            <th>Card</th>
                            <td colspan="2">{{ $card }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center bg-light fs-6"><a href="https://mobilekishop.net/blog/what-is-a-camera/" class="text-danger">CAMERA</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($main)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-the-main-rear-camera/" class="text-dark">Main</a></th>
                            <td colspan="2">{!! $main !!}</td>
                        </tr>
                        @endif
                        @if($features)
                        <tr>
                            <th>Features</th>
                            <td colspan="2">{{ $features }}</td>
                        </tr>
                        @endif
                        @if($front)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-the-front-selfie-camera/" class="text-dark">Front</a></th>
                            <td colspan="2">{!! $front !!}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">CONNECTIVITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($wlan)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-wlan/" class="text-dark">WLAN</a></th>
                            <td colspan="2">{{ $wlan }}</td>
                        </tr>
                        @endif
                        @if($bluetooth)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-bluetooth/" class="text-dark">Bluetooth</a></th>
                            <td colspan="2">{{ $bluetooth }}</td>
                        </tr>
                        @endif
                        @if($gps)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-gps/" class="text-dark">GPS</a></th>
                            <td colspan="2">{{ $gps }}</td>
                        </tr>
                        @endif
                        @if($radio)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-a-radio/" class="text-dark">Radio</a></th>
                            <td colspan="2">{{ $radio }}</td>
                        </tr>
                        @endif
                        @if($usb)
                        <tr>
                            <th>USB</th>
                            <td colspan="2">{{ $usb }}</td>
                        </tr>
                        @endif
                        @if($nfc)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-nfc/" class="text-dark">NFC</a></th>
                            <td colspan="2">{{ $nfc }}</td>
                        </tr>
                        @endif
                        @if($infrared)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-infrared/" class="text-dark">Infrared</a></th>
                            <td colspan="2">{{ $infrared }}</td>
                        </tr>
                        @endif
                        @if($data)
                        <tr>
                            <th>Data</th>
                            <td colspan="2">{{ $data }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">FEATURES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sensors)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-are-sensors/" class="text-dark">Sensors</a></th>
                            <td colspan="2">{{ $sensors }}</td>
                        </tr>
                        @endif
                        @if($audio)
                        <tr>
                            <th>Audio</th>
                            <td colspan="2">{{ $audio }}</td>
                        </tr>
                        @endif
                        @if($browser)
                        <tr>
                            <th>Browser</th>
                            <td colspan="2">{{ $browser }}</td>
                        </tr>
                        @endif
                        @if($messaging)
                        <tr>
                            <th>Messaging</th>
                            <td colspan="2">{{ $messaging }}</td>
                        </tr>
                        @endif
                        @if($games)
                        <tr>
                            <th>Games</th>
                            <td colspan="2">{{ $games }}</td>
                        </tr>
                        @endif
                        @if($torch)
                        <tr>
                            <th>Torch</th>
                            <td colspan="2">{{ $torch }}</td>
                        </tr>
                        @endif
                        @if($extra)
                        <tr>
                            <th>Extra</th>
                            <td colspan="2">{{ $extra }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                @if($models || $sar || $sar_eu)
                    <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th colspan="2" class="text-center text-danger bg-light fs-6">MISC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($models)
                            <tr>
                                <th>Models</th>
                                <td colspan="2">{{ $models }}</td>
                            </tr>
                            @endif
                            @if($sar)
                            <tr>
                                <th>SAR</th>
                                <td colspan="2">{{ $sar }}</td>
                            </tr>
                            @endif
                            @if($sar_eu)
                            <tr>
                                <th>SAR EU</th>
                                <td colspan="2">{{ $sar_eu }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                @endif


                <!-- Table for Performance -->
                @if($battery_new || $battery_old || $loudspeaker || $geekbench || $antutu)
                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">PERFORMANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($battery_new)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-a-battery/" class="text-dark">Battery</a></th>
                            <td colspan="2">{{ $battery_new }}</td>
                        </tr>
                        @endif
                        @if($battery_old)
                        <tr>
                            <th>Battery Old</th>
                            <td colspan="2">{{ $battery_old }}</td>
                        </tr>
                        @endif
                        @if($loudspeaker)
                        <tr>
                            <th><a href="https://mobilekishop.net/blog/what-is-a-loudspeaker/" class="text-dark">Loudspeaker</a></th>
                            <td colspan="2">{{ $loudspeaker }}</td>
                        </tr>
                        @endif
                        @if($geekbench)
                        <tr>
                            <th>Geekbench</th>
                            <td colspan="2">{{ $geekbench }}</td>
                        </tr>
                        @endif
                        @if($antutu)
                        <tr>
                            <th>Antutu</th>
                            <td colspan="2">{{ $antutu }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @endif



            </div>
        </div>
    </div>
</div>
<!-- specification div -->