<div class="row">
    @isset($connectivity)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/handfree_type.png')}}" alt="screen" width="24" height="24" class="img-fluid mb-2"> 
            {{ $connectivity}}
        </p>
    </div>
    @endisset

    @isset($bluetooth_version)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/bluetooth.png')}}" alt="camera" width="24" height="24" class="img-fluid mb-2"> 
            {{ $bluetooth_version }}
        </p>
    </div>
    @endisset

    @isset($ear_placement)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/weight.png')}}" alt="ram" width="24" height="24" class="img-fluid mb-2"> 
            {{ $ear_placement }}
        </p>
    </div>
    @endisset

    @isset($battery_time_intro)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/charging.png')}}" alt="battery" width="24" height="24" class="img-fluid mb-2"> 
            {{ $battery_time_intro }}
        </p>
    </div>
    @endisset

    @isset($noice_control_intro)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/playtime.png')}}" alt="os" width="24" height="24" class="img-fluid mb-2"> 
            {{ $noice_control_intro }}
        </p>
    </div>
    @endisset

    @isset($water_resistance_level)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/water.png')}}" alt="cpu" width="24" height="24" class="img-fluid mb-2"> 
            {{ $water_resistance_level }}
        </p>
    </div>
    @endisset
</div>
