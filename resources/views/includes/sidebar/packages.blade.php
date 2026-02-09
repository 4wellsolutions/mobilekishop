<!-- Networks -->
<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 mb-4 dark:bg-slate-900 dark:ring-slate-800">
    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-wide">Networks</h3>
    <ul class="space-y-1">
        @php
            $networks = App\Models\Package::distinct('filter_network')->pluck('filter_network');
        @endphp
        @if($networks->isNotEmpty())
            @foreach($networks as $net)
                <li>
                    <a href="{{ route('package.network.index', $net) }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition {{ (request()->segment(2) == $net) ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                        <img src="{{ URL::to('/images/packages/' . $net . '.png') }}" alt="{{ $net }}" width="24" height="24"
                            class="rounded-full border border-slate-200">
                        <span>{{ Str::title($net) }}</span>
                    </a>
                </li>
            @endforeach
        @endif
    </ul>
</div>

<!-- Package Type -->
<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 mb-4 dark:bg-slate-900 dark:ring-slate-800">
    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-wide">Package Type</h3>
    <ul class="space-y-1">
        @php
            $types = ['hybrid', 'voice', 'data', 'sms'];
            $currentNetwork = request()->segment(2);
        @endphp
        @if($currentNetwork && !in_array($currentNetwork, ['all', 'packages']))
            @foreach($types as $type)
                <li>
                    <a href="{{ url('packages/' . $currentNetwork . '/' . $type) }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition {{ (request()->segment(3) == $type) ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                        {{ Str::title($type) }}
                    </a>
                </li>
            @endforeach
        @else
            <li class="text-slate-400 text-xs px-3 py-2">Select a network to see types</li>
        @endif
    </ul>
</div>

<!-- Validity -->
<div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-4 dark:bg-slate-900 dark:ring-slate-800">
    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-3 uppercase tracking-wide">Validity</h3>
    <ul class="space-y-1">
        @php
            $validities = ['hourly', 'daily', 'weekly', 'monthly'];
        @endphp
        @if($currentNetwork && !in_array($currentNetwork, ['all', 'packages']))
            @foreach($validities as $validity)
                <li>
                    <a href="{{ url('packages/' . $currentNetwork . '/' . $validity) }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition {{ (request()->segment(3) == $validity) ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800' }}">
                        {{ Str::title($validity) }}
                    </a>
                </li>
            @endforeach
        @else
            <li class="text-slate-400 text-xs px-3 py-2">Select a network to see validity</li>
        @endif
    </ul>
</div>