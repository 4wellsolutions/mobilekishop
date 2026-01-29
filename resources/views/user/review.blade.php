@extends("layouts.frontend")

@section('title')My Account - MKS @stop

@section('description') @stop

@section("keywords") @stop

@section("canonical") @stop

@section("og_graph") @stop


@section("content")
<main class="main container-lg">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="my-1" style="font-size: 12px;">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{URL::to('/')}}" class="text-decoration-none text-secondary">
                        <img src="{{URL::to('/images/icons/home.png')}}" alt="home-icon" width="16" height="14">
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">My Account</li>
            </ol>
        </div>
    </nav>

    <div class="mb-5">
        <div class="row">
            <div class="col-12 col-md-3">
                @include("includes.user-sidebar")
            </div>
            <div class="col-lg-9 order-lg-last dashboard-content">
                @include("includes.info-bar")

                <div class="product-reviews-content">
                    <div class="row">
                        <div class="col-12">
                            <h2>Reviews ({{(Auth::user()->reviews) ? Auth::user()->reviews->count() : '0'}})</h2>
                            @foreach($reviews as $review)
                                <div class="row my-2 my-md-4">
                                    <div class="col-auto my-auto">
                                        <img src="{{$review->product->thumbnail}}" width="65" height="65"
                                            alt="{{$review->product->name}}" class="img-fluid rounded-0" />
                                    </div>
                                    <div class="col-9">
                                        <h2 class="fs-4">{{Str::title($review->product->name)}}</h2>
                                        <div class="rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->stars)
                                                    <img src="{{URL::to('/images/icons/star-fill.png')}}" alt="filled star">
                                                @else
                                                    <img src="/images/icons/star.png" alt="empty star">
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="mb-1 comment-text-{{$review->id}}">{!! $review->review !!}</p>
                                        <span class="comment-date fs-14"> Posted:
                                            {{\Carbon\Carbon::parse($review->created_at)->diffForHumans()}}</span>
                                        <div class="row">
                                            <div class="col-auto mt-2">
                                                @if($review->is_active)
                                                    <a href="#" class="btn btn-info btn-sm editReview text-white"
                                                        data-bs-toggle="modal" data-bs-target="#reviewEdit"
                                                        data-star="{{$review->stars}}" data-id="{{$review->id}}"><i
                                                            class="far fa-edit"></i> Edit</a>
                                                    <a href="{{route('user.review.delete', [$review->id])}}"
                                                        data-url="{{route('user.review.delete', [$review->id])}}"
                                                        class="btn btn-danger btn-sm  deleteButton"
                                                        onclick="return confirm('Are you sure!')"><i
                                                            class="fas fa-trash-alt"></i> Delete</a>
                                                @else
                                                    <span class="text-dark fst-italic fs-14">Pending Approval</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                            {{$reviews->links()}}
                        </div>
                    </div>
                </div><!-- End .product-reviews-content -->
            </div><!-- End .col-lg-9 -->

        </div><!-- End .row -->
    </div><!-- End .container -->

</main><!-- End .main -->
<!-- Modal -->
<div class="modal fade" id="reviewEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog reviewModal modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body px-3">
                <form action="{{route('user.review.update')}}" method="post" class="form-row" id="reviewForm">
                    @csrf
                    <input type="hidden" name="review_id" id="review_id">
                    <input type="hidden" name="stars" id="stars">
                    <div class="col-12">
                        <div class="form-group rating-form">
                            <label for="rating">Your rating</label>
                            <span class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $review->stars)
                                        <img class="stars star-{{$i}}" id="{{$i}}"
                                            src="{{URL::to('/images/icons/star-fill.png')}}" alt="filled star">
                                    @else
                                        <img class="stars star-{{$i}}" id="{{$i}}" src="/images/icons/star.png"
                                            alt="empty star">
                                    @endif
                                @endfor
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <textarea name="review" class="form-control" id="review" rows="5"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm submitButton">Save changes</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('script') @stop

@section('style') @stop