@extends("layouts.frontend")

@section('title')My Account - MKS @stop

@section('description') @stop

@section("keywords") @stop

@section("canonical") @stop

@section("og_graph") @stop


@section("content")
<style type="text/css">
     .offcanvas-backdrop{
        background: #FFF!important;
    }
    .widget a{
        text-decoration: none !important;
        color: #777;
    }
    .widget-title a{
        text-decoration: none !important;
        font-family: Poppins,sans-serif;
        color: #343a40;
        font-size: 18px;
    }
    
    .widget-body li a{
        font-size: 14px;
    }
    .nav-tabs.nav-item a{
        text-decoration: none !important;
        color: #343a40;
    }
    .nav-tabs.nav-tabs .nav-link{
        color: #31343a;
        border: none;
    }
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        color: black !important;
        border-bottom: 2px solid #000000 !important;
    }
    .nav-tabs.nav-link:hover{
        border-bottom: 2px solid #000000 !important;
    }
    h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
        font-weight: 700;
        line-height: 1.1;
        font-family: Poppins,sans-serif;
    }
    body{
        font-family: "Open Sans",sans-serif;
    }
    @media(max-width: 576px){
        .cameraBlock{
            border: none !important;
            border-bottom: 1px solid #dee2e6!important;
        }
        .screenBlock{
            border-bottom: 1px solid #dee2e6!important;
        }
        .mobileTable tr td:first-child{ 
            display: none; 
        }
        .mobile_content a{
            color: black;
        }
        .mobile_content h2{
            font-size: 18px !important;
        }
        .mobileTable tr th{
            color: #dc3545!important
        }
        .table{
            font-size: .8rem;
        }
        .imgDiv{
            height: 120px;
        }
        .detailDiv{
            height: 120px;
        }

        .product-title{
            font-size: 14px;
            font-weight: normal;
        }
        .product-price{
            font-size: 16px;
        }
    }
    @media(min-width: 577px){
        .imgDiv{
            height: 130px;
        }
        .detailDiv{
            height: 130px;
        }
        .product-title{
            font-size: 18px;
            font-weight: normal;
        }
        .product-price{
            font-size: 20px;
        }
    }
    .nav-tabs .nav-link{
        font-size: .9rem;
        padding-right: 7px;
        padding-left: 7px;
    }
    .mobile_image{
        max-height: 160px;
        width: auto;
    }
    .stars{
        cursor: pointer;
    }
    main a{
        text-decoration: none;
    }

    .rating {
  display: inline-block;
  font-size: 0;
}

.rating input {
  display: none;
}

.rating label {
  cursor: pointer;
  width: 30px;
  height: 30px;
  background-image: url("{{URL::to('/images/icons/star.png')}}");
  background-size: 100% 100%;
  display: inline-block;
  font-size: 0;
}

.rating input:checked + label,
.rating input:checked + label ~ label {
  background-image: url("{{URL::to('/images/icons/star-fill.png')}}");
}

.rating label:hover,
.rating label:hover ~ label {
  background-image: url("{{URL::to('/images/icons/star-fill.png')}}");
}

</style>
<script type="text/javascript">
    const ratingInputs = document.querySelectorAll('.rating input');

for (let i = 0; i < ratingInputs.length; i++) {
  ratingInputs[i].addEventListener('change', () => {
    const ratingValue = ratingInputs[i].value;
    console.log(`Selected rating: ${ratingValue}`);
  });
}

</script>
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
                                    <img src="{{$review->product->thumbnail}}" width="65" height="65" alt="{{$review->product->name}}" class="img-fluid rounded-0" />
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
                                    <span class="comment-date fs-14"> Posted: {{\Carbon\Carbon::parse($review->created_at)->diffForHumans()}}</span>
                                    <div class="row">
                                        <div class="col-auto mt-2">
                                            @if($review->is_active)
                                                <a href="#" class="btn btn-info btn-sm editReview text-white" data-bs-toggle="modal" data-bs-target="#reviewEdit" data-star="{{$review->stars}}" data-id="{{$review->id}}"><i class="far fa-edit"></i> Edit</a>
                                                <a href="{{route('user.review.delete',[$review->id])}}"  data-url="{{route('user.review.delete',[$review->id])}}" class="btn btn-danger btn-sm  deleteButton" onclick="return confirm('Are you sure!')"><i class="fas fa-trash-alt"></i> Delete</a>
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
<div class="modal fade" id="reviewEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <img class="stars star-{{$i}}" id="{{$i}}" src="{{URL::to('/images/icons/star-fill.png')}}" alt="filled star">
                        @else
                            <img class="stars star-{{$i}}" id="{{$i}}" src="/images/icons/star.png" alt="empty star">
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

@section('script')
<script type="text/javascript">
    $(".submitButton").click(function(){
        // console.log("submit form button");
        $("#reviewForm").submit();
    });
    $(".stars").on("click",function(){
        $(".submitButton").attr("disabled",false);
        $("#stars").val($(this).attr('id'));
        var star = $(this).attr('id');
        console.log(star);
        for (let i = 1; i <= 5; i++) {
            if(i <= star){
                console.log(".star-"+ i);
                $(".star-"+ i).attr("src","{{URL::to('/images/icons/star-fill.png')}}");
            }else{
                $(".star-"+i).attr("src","{{URL::to('/images/icons/star.png')}}");
            }
        }
    });
	
    $(".editReview").click(function(){
        var id = $(this).data("id");
        // console.log(".comment-text-"+id);
        var stars = $(this).attr("data-star");
        var review = $(".comment-text-"+id).text();
        // console.log(review);
        $("#review_id").val(id);
        $("#stars").val(stars);
        $(".star-"+stars).addClass("active");
        $("#review").html(review);
        
    });
    $(".stars").on("click",function(){
        // console.log($(this).attr('id'));
        $("#stars").val($(this).attr('id'));
    });
</script>
@stop

@section('style')
<style type="text/css">
    .rating-stars a {
        color: #FD5B5A !important;
    }
    @media(min-width: 768px){
        .reviewModal{
            width: 50% !important;
        }
    }
    @media(max-width: 767px){
        .reviewModal{
            width: 100% !important;
        }
    }
</style>
@stop