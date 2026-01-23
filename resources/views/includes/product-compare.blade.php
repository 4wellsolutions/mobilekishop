<!-- compare div -->
<div class="row">
    <div class="col-12">
        @if(!$compares->isEmpty())
            <section class="row my-2 my-md-3">
                <div class="row d-flex justify-content-center">
                    <h2 class="pb-2">{{Str::title($product->name)}} Comparison</h2>
                </div>
                <div class="row">
                    @foreach($compares as $compare)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                      <div class="card" style="border: none;">
                        <a href="{{$compare->link}}">
                            <img class="card-img img-fluid" src="{{URL::to('/images/comparison.jpg')}}" data-echo="{{$compare->thumbnail}}" alt="{{$compare->alt}}" width="366" height="206">
                        </a>
                        <div class="card-body py-1 px-1" style="min-height: auto;">
                          <a href="{{$compare->link}}" style="text-decoration: none;">
                            <h4 class="text-dark text-center font-weight-normal" style="font-size: 16px;">{!! Str::title(Str::of($compare->product1 . " <strong>VS</strong> ". $compare->product2. ($compare->product3 ? " <strong>Vs</strong> ". $compare->product3 : ""))->replace('-', ' ')) !!}</h4>
                          </a>
                        </div>
                      </div>
                    </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>          
</div>
<!-- compare div -->