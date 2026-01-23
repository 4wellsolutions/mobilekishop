@if(!$product->images->isEmpty())
<!-- images div -->
<div class="row my-3 my-md-5" id="images">
    <div class="col-12">
        <h2>Images</h2>
            @foreach($product->images as $image)
            <div class="row">
                <div class="col-12 text-center">
                    <img src="{{URL::to('/images/thumbnail.png')}}" data-echo="{{URL::to('/products/'.$image->name)}}" class="img-fluid mx-auto my-2" alt="{{$product->slug}}">
                </div>
            </div>
            @endforeach
    </div>
</div>
<!-- images div -->
@endif