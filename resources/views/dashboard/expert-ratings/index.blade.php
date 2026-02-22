@extends("layouts.dashboard")

@section("content")
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Expert Ratings</h4>
                <div class="ms-auto text-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Expert Ratings</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        @include("includes.info-bar")

        {{-- Search --}}
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.expert-ratings.index') }}"
                    class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label">Search Product</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by product name..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary me-2">Search</button>
                        <a href="{{ route('dashboard.expert-ratings.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Products & Expert Ratings</h5>
                <div class="table-responsive">
                    <table id="zero_config" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.#</th>
                                <th>Product</th>
                                <th>Overall</th>
                                <th>Status</th>
                                <th>Rated By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($product->thumbnail)
                                                <img src="{{ $product->thumbnail }}" alt=""
                                                    style="width:40px; height:50px; object-fit:contain;" class="me-2">
                                            @endif
                                            {{ $product->name }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->expertRating)
                                            <span
                                                class="badge {{ $product->expertRating->overall >= 7 ? 'bg-success' : ($product->expertRating->overall >= 5 ? 'bg-warning' : 'bg-danger') }} px-3 py-2"
                                                style="font-size: 14px;">
                                                {{ $product->expertRating->overall }}/10
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($product->expertRating)
                                            <span class="badge bg-success">Rated</span>
                                        @else
                                            <span class="badge bg-secondary">Not Rated</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->expertRating->rated_by ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('dashboard.expert-ratings.edit', $product->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-star me-1"></i>{{ $product->expertRating ? 'Edit' : 'Rate' }}
                                        </a>
                                        @if($product->expertRating)
                                            <form method="POST"
                                                action="{{ route('dashboard.expert-ratings.destroy', $product->id) }}"
                                                class="d-inline" onsubmit="return confirm('Remove expert rating?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i
                                                        class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
            </div>
        </div>
    </div>
</div>
@stop