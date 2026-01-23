@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show py-2 rounded-0 mx-0 btn-block" role="alert">
          <ul class="my-0 py-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
  </div>
@endif
@if(Session::has('fail'))
    <div class="alert alert-danger alert-dismissible fade show rounded-0 mx-0 btn-block" role="alert">
        <ul class="my-0 py-0">
            <li>{{Session::get('fail')}}</li>
        </ul>
    </div>
@endif
@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-0 mx-0 btn-block py-2" role="alert">
        <ul class="my-0 py-0">
            <li>{{Session::get('success')}}</li>
        </ul>
    </div>
@endif
    
