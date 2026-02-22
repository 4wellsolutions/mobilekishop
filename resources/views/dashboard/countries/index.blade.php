@extends('layouts.dashboard')
@section('title', 'Countries')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Countries</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                Countries
            </div>
        </div>
        <a href="{{ route('dashboard.countries.create') }}" class="btn-admin-primary">
            <i class="fas fa-plus"></i> Add Country
        </a>
    </div>

    @include('includes.info-bar')

    <div class="admin-card">
        <div class="admin-card-header">
            <h2>All Countries</h2>
        </div>
        <div class="admin-card-body no-padding">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Country</th>
                            <th>Code</th>
                            <th>Icon</th>
                            <th>Currency</th>
                            <th>ISO</th>
                            <th>Domain</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countries as $country)
                            <tr>
                                <td>{{ $country->id }}</td>
                                <td class="td-title">{{ $country->country_name }}</td>
                                <td><span class="admin-badge badge-default">{{ $country->country_code }}</span></td>
                                <td><span class="{{ strtolower($country->icon) }}"></span></td>
                                <td>{{ $country->currency }}</td>
                                <td>{{ $country->iso_currency }}</td>
                                <td><code style="color:var(--admin-accent); font-size:12px;">{{ $country->domain }}</code></td>
                                <td>
                                    <div class="admin-action-group">
                                        <a href="{{ route('dashboard.countries.edit', $country->id) }}"
                                            class="btn-admin-icon btn-edit" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="{{ route('dashboard.countries.show', $country->id) }}"
                                            class="btn-admin-icon btn-view" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('dashboard.countries.destroy', $country->id) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-admin-icon btn-delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="admin-empty-state">
                                        <i class="fas fa-globe"></i>
                                        <h3>No countries found</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection