@extends("layouts.dashboard")
@section("title","Error Logs")
@section("content")
  <div class="page-wrapper">
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Error Logs</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Error Logs
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Error Logs</h5>
          @include("includes.info-bar")
          <table class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>URL</th>
                <th>Error Code</th>
                <th>Message</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($errorLogs as $errorLog)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $errorLog->url }}</td>
                  <td>{{ $errorLog->error_code }}</td>
                  <td>{{ $errorLog->message }}</td>
                  <td>
                    <form action="{{ route('dashboard.error_logs.destroy', $errorLog->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this error log?')">Delete</button>
                    </form>

                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          {{ $errorLogs->links("pagination::bootstrap-4") }}
        </div>
      </div>
    </div>
  </div>
@stop
