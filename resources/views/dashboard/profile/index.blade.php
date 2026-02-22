@extends('layouts.dashboard')
@section('title', 'My Profile')

@section('content')
    <div class="admin-page-header">
        <div>
            <h1>My Profile</h1>
            <div class="breadcrumb-nav">
                <a href="{{ route('dashboard.index') }}">Dashboard</a>
                <span class="separator">/</span>
                My Profile
            </div>
        </div>
    </div>

    @include('includes.info-bar')

    {{-- Profile Info Card --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-header">
            <h2><i class="fas fa-user" style="margin-right:8px; opacity:0.5;"></i>Profile Information</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.profile.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Full Name</label>
                            <input type="text" name="name" class="admin-form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Email Address</label>
                            <input type="email" name="email" class="admin-form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="admin-form-control"
                                value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Account Type</label>
                            <input type="text" class="admin-form-control" value="Administrator" disabled>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:12px; margin-top:8px;">
                    <button type="submit" class="btn-admin-primary">
                        <i class="fas fa-save" style="margin-right:6px;"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Password Card --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fas fa-lock" style="margin-right:8px; opacity:0.5;"></i>Change Password</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.profile.password') }}" method="POST" id="passwordForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Current Password</label>
                            <input type="password" name="current_password" class="admin-form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="admin-form-group">
                            <label class="admin-form-label">New Password</label>
                            <input type="password" name="password" class="admin-form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="admin-form-control" required>
                        </div>
                    </div>
                </div>

                <div style="margin-top:8px;">
                    <button type="submit" class="btn-admin-primary">
                        <i class="fas fa-key" style="margin-right:6px;"></i>Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection