@extends('layouts.dashboard')
@section('title', 'Create Comparison')
@section('content')
  <div class="admin-page-header">
    <div>
      <h1>Create Comparison</h1>
      <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span class="separator">/</span><a
          href="{{ route('dashboard.compares.index') }}">Comparisons</a><span class="separator">/</span>Create</div>
    </div>
  </div>
  @include('includes.info-bar')

  @if(\Request::has('product1'))
    @php
      if (App\Models\Compare::whereProduct1(\Request::get('product1'))->whereProduct2(\Request::get('product2'))->whereProduct3(null)->first()) {
        echo "<div class='admin-card' style='margin-bottom:16px;border-left:4px solid #ef4444;'><div class='admin-card-body' style='color:#ef4444;font-weight:600;'>Compare Already exists!</div></div>";
      } elseif (App\Models\Compare::whereProduct1(\Request::get('product1'))->whereProduct2(\Request::get('product2'))->whereProduct3(\Request::get('product3'))->first()) {
        echo "<div class='admin-card' style='margin-bottom:16px;border-left:4px solid #ef4444;'><div class='admin-card-body' style='color:#ef4444;font-weight:600;'>Compare Already exists!</div></div>";
      }
    @endphp
  @endif

  {{-- Search Form --}}
  <div class="admin-card" style="margin-bottom:24px;">
    <div class="admin-card-header">
      <h2><i class="fas fa-search" style="margin-right:8px;opacity:0.5;"></i>Search Products to Compare</h2>
    </div>
    <div class="admin-card-body">
      <form action="#" method="get">
        <input type="hidden" name="search1" value="{{ \Request::get('search1', '') }}" id="input-search1">
        <input type="hidden" name="search2" value="{{ \Request::get('search2', '') }}" id="input-search2">
        <input type="hidden" name="search3" value="{{ \Request::get('search3') }}" id="input-search3">
        <div class="admin-form-grid" style="grid-template-columns:repeat(3,1fr) auto;">
          <div class="admin-form-group"><label class="admin-form-label">Product 1</label>
            <input type="text" id="search1" value="{{ \Request::get('search1', '') }}"
              class="admin-form-control searchInput" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Product 2</label>
            <input type="text" id="search2" value="{{ \Request::get('search2', '') }}"
              class="admin-form-control searchInput" required>
          </div>
          <div class="admin-form-group"><label class="admin-form-label">Product 3 (Optional)</label>
            <input type="text" id="search3" value="{{ \Request::get('search3', '') }}"
              class="admin-form-control searchInput">
          </div>
          <div class="admin-form-group" style="display:flex;align-items:flex-end;">
            <button type="submit" class="btn-admin-primary"><i class="fas fa-link"></i> Generate URL</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  @if(\Request::has('search1') && \Request::has('search2'))
    {{-- Submit Form --}}
    <div class="admin-card" style="margin-bottom:24px;">
      <div class="admin-card-header">
        <h2>Comparison Details</h2>
      </div>
      <div class="admin-card-body">
        <form action="{{ route('dashboard.compares.store') }}" method="post" enctype="multipart/form-data">
          @csrf
          @php
            $base_url = URL::to('/compare') . "/";
            $url = \Request::get('search1') . "-vs-" . \Request::get('search2');
            if (\Request::get('search3'))
              $url .= "-vs-" . \Request::get('search3');
            $final_url = $base_url . $url;
          @endphp
          <input type="hidden" name="thumbnail" id="thumbnail" value="{{ $image }}">
          <input type="hidden" name="product1" value="{{ \Request::get('search1') }}">
          <input type="hidden" name="product2" value="{{ \Request::get('search2') }}">
          <input type="hidden" name="product3" value="{{ \Request::get('search3') }}">
          <input type="hidden" name="alt" value="{{ Str::slug($url) }}">
          <input type="hidden" name="name" value="{{ Str::slug($url) }}.jpg">
          <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">Compare URL</label>
            <input type="text" name="url" class="admin-form-control" value="{{ $final_url }}" readonly required>
          </div>
          <div style="margin-bottom:16px;">
            <label class="admin-form-label">Thumbnail Preview</label>
            <div style="margin-top:8px;"><img src="{{ $image }}"
                style="max-height:200px;border-radius:8px;border:1px solid var(--admin-border);"></div>
          </div>
          <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">Custom Thumbnail
              (Optional, 640×360)</label>
            <input type="file" name="thumbnail1" class="admin-form-control">
          </div>
          <button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i> Create Comparison</button>
        </form>
      </div>
    </div>
  @endif

  @if(!$compares->isEmpty())
    <div class="admin-card">
      <div class="admin-card-header">
        <h2>Last 10 Comparisons</h2>
      </div>
      <div class="admin-card-body" style="padding:0;">
        <div class="admin-table-wrapper">
          <table class="admin-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Link</th>
                <th>Image</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($compares as $compare)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;">{{ $compare->link }}</td>
                  <td><img src="{{ $compare->thumbnail }}" style="height:50px;border-radius:4px;"></td>
                  <td><a href="{{ route('dashboard.compares.edit', $compare->id) }}" class="btn-admin-sm"><i
                        class="fas fa-edit"></i> Edit</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endif
@endsection
@section('styles')
  <style>
    .twitter-typeahead {
      width: 100% !important;
    }

    .tt-menu {
      width: inherit !important;
      position: inherit !important;
    }
  </style>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/0.11.1/typeahead.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
  <script>
    var route = "{{ route('autocomplete.search') }}";
    var engine = new Bloodhound({ remote: { url: route + "?query=%QUERY%", wildcard: '%QUERY%' }, datumTokenizer: Bloodhound.tokenizers.whitespace('query'), queryTokenizer: Bloodhound.tokenizers.whitespace });
    $(".searchInput").typeahead({ hint: false, highlight: true, minLength: 1 }, {
      source: engine.ttAdapter(), limit: 9999, name: 'usersList', displayKey: 'name',
      templates: {
        empty: ['<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'],
        header: ['<div class="list-group search-results-dropdown">'],
        suggestion: function (data) { return '<div class="row bg-white border-bottom"><div class="col-4 col-md-3"><img src="' + data.thumbnail + '" class="img-fluid searchImage my-1"></div><div class="col-8 col-md-9 text-dark text-uppercase" style="font-weight:600;">' + data.name + '</div></div>' }
      }
    });
    $('.searchInput').bind('typeahead:select', function (ev, suggestion) { $("#input-" + $(this).attr("id")).val(suggestion.slug); });
  </script>
@endsection