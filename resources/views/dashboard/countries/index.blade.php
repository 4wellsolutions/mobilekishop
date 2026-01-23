@extends('layouts.dashboard')

@section('title', 'Countries')

@section('content')
<div class="page-wrapper">
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Countries</h4>
                <div class="ms-auto text-end">
                    <a href="{{ route('dashboard.countries.create') }}" class="btn btn-primary">Add New Country</a>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="card">
            <div class="card-body">
                @include('includes.info-bar')
                <h5 class="card-title">List of Countries</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Country Name</th>
                            <th>Country Code</th>
                            <th>Icon</th>
                            <th>Currency</th>
                            <th>ISO Currency</th>
                            <th>Domain</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($countries as $country)
                        <tr>
                            <td>{{ $country->id }}</td>
                            <td>{{ $country->country_name }}</td>
                            <td>{{ $country->country_code }}</td>
                            <td><span class="{{ strtolower($country->icon) }}"></span></td>
                            <td>{{ $country->currency }}</td>
                            <td>{{ $country->iso_currency }}</td>
                            <td>{{ $country->domain }}</td>
                            <td>
                                <a href="{{ route('dashboard.countries.show', $country->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('dashboard.countries.edit', $country->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('dashboard.countries.destroy', $country->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this country?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if($countries->isEmpty())
                        <tr>
                            <td colspan="8" class="text-center">No countries found.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
