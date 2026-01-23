@foreach($compares as $compare)
    @include("includes.compare-details",["compare" => $compare])
@endforeach