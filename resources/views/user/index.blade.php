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
</style>
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
						<h2>Edit Account Information</h2>
						<form action="{{route('user.update',Auth::User()->id)}}" method="post">
					    @csrf
					    <div class="row">
					        <div class="col-sm-11 mx-auto">
					            <div class="row">
					                <div class="col-md-4 mb-3">
					                    <div class="form-group required-field">
					                        <label for="acc-name">First Name</label>
					                        <input type="text" class="form-control" id="acc-name" name="name" value="{{Auth::User()->name}}" required>
					                    </div><!-- End .form-group -->
					                </div><!-- End .col-md-4 -->
					                <div class="col-md-4 mb-3">
					                    <div class="form-group required-field">
					                        <label for="email">Email</label>
					                        <input type="email" class="form-control" id="email" value="{{Auth::User()->email}}" readonly>
					                    </div><!-- End .form-group -->
					                </div><!-- End .col-md-4 -->
					                <div class="col-md-4 mb-3">
					                    <div class="form-group">
					                        <label for="phone">Phone Number</label>
					                        <input type="text" value="{{Auth::user()->phone_number}}" class="form-control" id="phone" name="phone">
					                    </div><!-- End .form-group -->
					                </div><!-- End .col-md-4 -->
					            </div><!-- End .row -->

					            <div class="mb-3"></div><!-- margin -->

					            <div class="custom-control custom-checkbox mb-3">
					                <input type="checkbox" name="newsletter" class="custom-control-input" id="newsletter" {{Auth::User()->newsletter ? 'checked' : ''}}>
					                <label class="custom-control-label" for="newsletter">NewsLetter</label>
					            </div><!-- End .custom-checkbox -->

					            <div class="custom-control custom-checkbox mb-3">
					                <input type="checkbox" class="custom-control-input" id="change-pass-checkbox" value="1">
					                <label class="custom-control-label" for="change-pass-checkbox">Change Password</label>
					            </div><!-- End .custom-checkbox -->

					            <div id="account-chage-pass" class="mt-3 d-none">
					                <h3 class="mb-3">Change Password</h3>
					                <div class="row">
					                    <div class="col-md-6 mb-3">
					                        <div class="form-group required-field">
					                            <label for="password">Password</label>
					                            <input type="password" class="form-control" id="password" name="password">
					                        </div><!-- End .form-group -->
					                    </div><!-- End .col-md-6 -->

					                    <div class="col-md-6 mb-3">
					                        <div class="form-group required-field">
					                            <label for="password_confirmation">Confirm Password</label>
					                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
					                        </div><!-- End .form-group -->
					                    </div><!-- End .col-md-6 -->
					                    <div class="required text-end mb-3">* Required Field</div>
					                </div><!-- End .row -->
					            </div><!-- End #account-chage-pass -->

					            
					            <div class="form-footer d-flex justify-content-end">
					                <button type="submit" class="btn btn-primary">Save</button>
					            </div><!-- End .form-footer -->
					        </div><!-- End .col-sm-11 -->
					    </div><!-- End .row -->
					</form>

					</div><!-- End .col-lg-9 -->
				</div><!-- End .row -->
			</div><!-- End .container -->
		</main><!-- End .main -->
@stop

@section('script')
<script type="text/javascript">
	$("#change-pass-checkbox").change(function() {
		
    if(this.checked) {
    	$("#account-chage-pass").fadeIn().removeClass("d-none");
        $("#password").attr("required",true);
        $("#password_confirmation").attr("required",true);
    }else{
    	$("#account-chage-pass").fadeOut();
    	$("#password").attr("required",false);
        $("#password_confirmation").attr("required",false);
    }
	});
</script>
@stop