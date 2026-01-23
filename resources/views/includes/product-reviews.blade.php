<!-- review div -->
<div class="row" id="reviews">
    <div class="col-12 col-md-12">
        <div class="add-product-review bg-light rounded p-2 shadow">
            <form action="#" data-action="#" class="comment-form m-0" method="post" id="reviewForm">
                <input type="hidden" name="stars" id="stars">
                <input type="hidden" name="product_id" id="product_id" value="{{$product->id}}">
                @csrf
                <h3 class="fs-3 review-title">Add a Review</h3>

                <div class="rating-form">
                    <label for="rating">Your rating</label>
                    <span class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <img src="{{URL::to('/images/icons/star.png')}}" id="star-{{ $i }}" class="stars" alt="star" width="24" height="24" data-value="{{ $i }}">
                        @endfor
                    </span>
                    <div class="error-message" id="stars-error" style="color: red; display: none;">Please provide a rating.</div>
                </div>

                @auth
                    <div class="form-group">
                        <label>Your Review</label>
                        <textarea cols="5" name="review" rows="6" class="form-control form-control-sm mb-0" id="review"></textarea>
                        <small class="text-info">Do not share any personal information.</small>
                        <div class="error-message" id="review-error" style="color: red; display: none;">Please provide a review.</div>
                    </div><!-- End .form-group -->
                    
                    <div class="col-12 pt-2">
                        <button type="submit" class="btn btn-custom submitReview">Submit</button>
                    </div>
                @endauth
            </form>
        </div>
    </div>

    <div class="col-12 col-md-12">
        @if(count($product->reviews))
        @foreach($product->reviews()->orderBy("created_at","DESC")->get() as $review)
            <div class="col-12 my-2 my-md-3">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex mb-2">
                            <div class="me-3">
                                <img src="{{URL::to('/images/profile.png')}}" class="rounded-circle" width="65" height="65" alt="avatar"/>
                            </div>
                            <div>
                                <h5 class="card-title">
                                    @if($review->user)
                                        {{$review->user->name}}
                                    @else
                                        {{$review->name}}
                                    @endif
                                </h5>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <small>Posted: {{\Carbon\Carbon::parse($review->created_at)->diffForHumans()}}</small>
                                </h6>
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <div class="product-ratings">
                                        <span class="rating-stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->stars)
                                                    <img src="{{ URL::to('/images/icons/star-fill.png') }}" id="star-{{ $i }}" class="stars" alt="filled star" width="24" height="24">
                                                @else
                                                    <img src="{{ URL::to('/images/icons/star.png') }}" id="star-{{ $i }}" class="stars" alt="empty star" width="24" height="24">
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                </h6>
                                <div class="product-ratings">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $review->stars)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-muted"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="card-text">
                            {!! $review->review !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach    
    @endif
    </div>      
</div>        
<div id="toastContainer" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11;"></div>
<!-- add review div -->
