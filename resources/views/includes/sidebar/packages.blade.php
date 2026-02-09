<div class="widget-sidebar p-2 m-2 border rounded bg-white shadow-sm">
    <div class="widget-title border-bottom mb-2">
        <p class="p-2 d-block text-uppercase fw-bold mb-0">Networks</p>
    </div>
    <div class="widget-body">
        <ul class="list-unstyled ps-2 pt-1">
            @php
                $networks = App\Models\Package::distinct('filter_network')->pluck('filter_network');
            @endphp
            @if($networks->isNotEmpty())
                @foreach($networks as $net)
                    <li class="mb-2">
                        <a href="{{route('package.network.index', $net)}}"
                            class="text-decoration-none text-dark d-flex align-items-center">
                            <img src="{{URL::to('/images/packages/' . $net . '.png')}}" alt="{{$net}}" width="24" height="24"
                                class="me-2 rounded-circle border">
                            <span
                                class="{{ (request()->segment(2) == $net) ? 'fw-bold text-primary' : '' }}">{{ Str::title($net) }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 border rounded bg-white shadow-sm mt-3">
    <div class="widget-title border-bottom mb-2">
        <p class="p-2 d-block text-uppercase fw-bold mb-0">Package Type</p>
    </div>
    <div class="widget-body">
        <ul class="list-unstyled ps-2 pt-1">
            @php
                $types = ['hybrid', 'voice', 'data', 'sms'];
                $currentNetwork = request()->segment(2);
            @endphp
            @if($currentNetwork && !in_array($currentNetwork, ['all', 'packages']))
                @foreach($types as $type)
                    <li class="mb-1">
                        <a href="{{ url('packages/' . $currentNetwork . '/' . $type) }}"
                            class="text-decoration-none text-dark {{ (request()->segment(3) == $type) ? 'fw-bold text-primary' : '' }}">
                            {{ Str::title($type) }}
                        </a>
                    </li>
                @endforeach
            @else
                <li class="text-muted small">Select a network to see types</li>
            @endif
        </ul>
    </div>
</div>

<div class="widget-sidebar p-2 m-2 border rounded bg-white shadow-sm mt-3">
    <div class="widget-title border-bottom mb-2">
        <p class="p-2 d-block text-uppercase fw-bold mb-0">Validity</p>
    </div>
    <div class="widget-body">
        <ul class="list-unstyled ps-2 pt-1">
            @php
                $validities = ['hourly', 'daily', 'weekly', 'monthly'];
            @endphp
            @if($currentNetwork && !in_array($currentNetwork, ['all', 'packages']))
                @foreach($validities as $validity)
                    <li class="mb-1">
                        <a href="{{ url('packages/' . $currentNetwork . '/' . $validity) }}"
                            class="text-decoration-none text-dark {{ (request()->segment(3) == $validity) ? 'fw-bold text-primary' : '' }}">
                            {{ Str::title($validity) }}
                        </a>
                    </li>
                @endforeach
            @else
                <li class="text-muted small">Select a network to see validity</li>
            @endif
        </ul>
    </div>
</div>