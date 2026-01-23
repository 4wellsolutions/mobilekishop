<!-- specification div -->
<div class="row" id="specification">
    <div class="col-12">
        <h2>Specification</h2>
        <div class="product-desc-content mt-2">
            <div class="table-responsive">
                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <tbody>
                        <tr class="">
                            <td rowspan="2" class="text-danger text-uppercase fw-bold">Price</td>
                            <th>{{$country->currency}}</th>
                            <td colspan="2">{{ $product->getFirstVariantPriceForCountry($product->id,$country->id) }}</td>
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

                <!-- BUILD Table -->
                <table border="0" class="table custom-table table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">BUILD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($dimensions)
                        <tr>
                            <th>Dimensions</th>
                            <td colspan="2">{{ $dimensions }}</td>
                        </tr>
                        @endif
                        @if($weight)
                        <tr>
                            <th>Weight</th>
                            <td colspan="2">{{ $weight }}</td>
                        </tr>
                        @endif
                        @if($material)
                        <tr>
                            <th>Material</th>
                            <td colspan="2">{{ $material }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <!-- CONNECTIVITY Table -->
                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">CONNECTIVITY</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($connectivity)
                        <tr>
                            <th>Connectivity</th>
                            <td colspan="2">{{ $connectivity }}</td>
                        </tr>
                        @endif
                        @if($wireless_communication_technology)
                        <tr>
                            <th>Wireless Communication Technology</th>
                            <td colspan="2">{{ $wireless_communication_technology }}</td>
                        </tr>
                        @endif
                        @if($bluetooth_range)
                        <tr>
                            <th>Bluetooth Range</th>
                            <td colspan="2">{{ $bluetooth_range }}</td>
                        </tr>
                        @endif
                        @if($bluetooth_version)
                        <tr>
                            <th>Bluetooth Version</th>
                            <td colspan="2">{{ $bluetooth_version }}</td>
                        </tr>
                        @endif
                        @if($jack)
                        <tr>
                            <th>Jack</th>
                            <td colspan="2">{{ $jack }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <!-- FEATURES Table -->
                <table border="0" class="table custom-table table-bordered table-sm w-100 text-dark" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-danger bg-light fs-6">FEATURES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($special_feature)
                        <tr>
                            <th>Special Feature</th>
                            <td colspan="2">{{ $special_feature }}</td>
                        </tr>
                        @endif
                        @if($compatible_devices)
                        <tr>
                            <th>Compatible Devices</th>
                            <td colspan="2">{{ $compatible_devices }}</td>
                        </tr>
                        @endif
                        @if($control_type)
                        <tr>
                            <th>Control Type</th>
                            <td colspan="2">{{ $control_type }}</td>
                        </tr>
                        @endif
                        @if($cable_feature)
                        <tr>
                            <th>Cable Feature</th>
                            <td colspan="2">{{ $cable_feature }}</td>
                        </tr>
                        @endif
                        @if($water_resistance_level)
                        <tr>
                            <th>Water Resistance Level</th>
                            <td colspan="2">{{ $water_resistance_level }}</td>
                        </tr>
                        @endif
                        @if($style)
                        <tr>
                            <th>Style</th>
                            <td colspan="2">{{ $style }}</td>
                        </tr>
                        @endif
                        @if($audio_driver_type)
                        <tr>
                            <th>Audio Driver Type</th>
                            <td colspan="2">{{ $audio_driver_type }}</td>
                        </tr>
                        @endif
                        @if($charging_time)
                        <tr>
                            <th>Charging Time</th>
                            <td colspan="2">{{ $charging_time }}</td>
                        </tr>
                        @endif
                        @if($batteries)
                        <tr>
                            <th>Batteries</th>
                            <td colspan="2">{{ $batteries }}</td>
                        </tr>
                        @endif
                        @if($battery_life)
                        <tr>
                            <th>Battery Life</th>
                            <td colspan="2">{{ $battery_life }}</td>
                        </tr>
                        @endif
                        @if($included_components)
                        <tr>
                            <th>Included Components</th>
                            <td colspan="2">{{ $included_components }}</td>
                        </tr>
                        @endif
                        @if($age_range)
                        <tr>
                            <th>Age Range</th>
                            <td colspan="2">{{ $age_range }}</td>
                        </tr>
                        @endif
                        @if($product_use)
                        <tr>
                            <th>Product Use</th>
                            <td colspan="2">{{ $product_use }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<!-- specification div -->