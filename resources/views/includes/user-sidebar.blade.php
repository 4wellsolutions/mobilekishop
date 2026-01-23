<aside class="sidebar">
	<div class="widget widget-dashboard">
		<h3 class="widget-title">My Account</h3>

		<ul class="list-unstyled ms-3 mb-1">
			<li class="active"><a href="{{route('user.index')}}" {{Request::routeIs('user.index') ? "class=fw-bold" : ''}}>Profile</a></li>
			<li><a href="{{route('user.review')}}" {{Request::routeIs('user.review') ? "class=fw-bold" : ''}}>Reviews</a></li>
			<li><a href="{{route('user.wishlist')}}" {{Request::routeIs('user.wishlist') ? "class=fw-bold" : ''}}>Wishlist</a></li>
		</ul>
	</div>
</aside>