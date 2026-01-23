<form action="{{route('search')}}" class="form-inline my-3" method="get">
  <div class="input-group rounded-pill border border-3 m-2 bg-white">
    <input type="search" class="form-control rounded-pill border-0" name="query" id="searchInput" placeholder="Search..." required>
    <div class="input-group-text bg-white rounded-pill border-0 border-start">
      <button class="bg-white border-0 px-0" type="submit"><img src="{{URL::to('/images/icons/search.png')}}" alt="search-icon" width="24" height="24"></button>
    </div>
  </div>
</form>
