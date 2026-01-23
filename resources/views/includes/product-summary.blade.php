<div class="row">
    @isset($screen_size)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/screen_size.png')}}" alt="screen" width="24" height="24" class="img-fluid mb-2"> 
            {{ $screen_size }} Inches Screen
        </p>
    </div>
    @endisset

    @isset($pixels)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/camera.png')}}" alt="camera" width="24" height="24" class="img-fluid mb-2"> 
            {{ $pixels }} MP Rear Camera
        </p>
    </div>
    @endisset

    @isset($ram_in_gb, $rom_in_gb)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/ram.png')}}" alt="ram" width="24" height="24" class="img-fluid mb-2"> 
            {{ $ram_in_gb }} RAM + {{ $rom_in_gb }}
        </p>
    </div>
    @endisset

    @isset($battery)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/battery.png')}}" alt="battery" width="24" height="24" class="img-fluid mb-2"> 
            {{ $battery }} mAh Battery
        </p>
    </div>
    @endisset

    @isset($os)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/os.png')}}" alt="os" width="24" height="24" class="img-fluid mb-2"> 
            {{ $os }}
        </p>
    </div>
    @endisset

    @isset($gpu)
    <div class="col-12 col-md-6">
        <p class="mb-0 fw-normal text-uppercase fs-14">
            <img src="{{URL::to('/images/icons/cpu.png')}}" alt="cpu" width="24" height="24" class="img-fluid mb-2"> 
            {{ $gpu }}
        </p>
    </div>
    @endisset
</div>
