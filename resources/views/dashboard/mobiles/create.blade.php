@extends('layouts.dashboard')
@section('title', 'Add Mobile')
@section('content')
    <div class="admin-page-header">
        <div>
            <h1>Add Mobile</h1>
            <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span
                    class="separator">/</span><a href="{{ route('dashboard.mobile.index') }}">Mobiles</a><span
                    class="separator">/</span>Create</div>
        </div>
    </div>
    @include('includes.info-bar')

    {{-- Fetch URL Form --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-header">
            <h2><i class="fas fa-link" style="margin-right:8px;opacity:0.5;"></i>Fetch from URL</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.mobile.fetch') }}" method="post">
                @csrf
                <div style="display:flex;gap:12px;align-items:flex-end;">
                    <div class="admin-form-group" style="flex:1;margin-bottom:0;">
                        <input name="url" class="admin-form-control" placeholder="Enter WhatMobile URL...">
                    </div>
                    <button class="btn-admin-primary" type="submit"><i class="fas fa-download"></i> Fetch</button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($data))
        <form id="form_validation" action="{{ route('dashboard.mobile.store') }}" method="post" class="needs-validation"
            novalidate enctype="multipart/form-data">
            @csrf

            {{-- Basic Info Card --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Basic Information</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-grid">
                        <div class="admin-form-group"><label class="admin-form-label">Brand</label>
                            <input type="text" name="brand" value="{{ $brand }}" class="admin-form-control" required>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Model</label>
                            <input type="text" name="model" value="{{ $name }}" class="admin-form-control" required>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Price in PKR</label>
                            <input type="text" name="price_in_pkr" value="{{ Str_replace(',', '', $price_in_pkr) }}"
                                class="admin-form-control" required>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Price in Dollar</label>
                            <input type="text" name="price_in_dollar" value="{{ $price_in_dollar }}" class="admin-form-control"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fetched Specs Card --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Fetched Specifications</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-grid">
                        @foreach($data as $d)
                            <div class="admin-form-group"><label class="admin-form-label">{{ $d[0] }}</label>
                                <input type="text" name="{{ \Illuminate\Support\Str::slug($d[0], '_') }}" value="{{ $d[1] }}"
                                    class="admin-form-control" required>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Additional Details Card --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Additional Details</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-grid">
                        <div class="admin-form-group"><label class="admin-form-label">Release Date</label>
                            <input type="date" name="release_date" class="admin-form-control" required>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Camera Pixels</label>
                            <select name="pixels" class="admin-form-control" required>
                                <option value="">Select Pixels</option>
                                @foreach(\App\MobilePixel::orderBy('pixels', 'asc')->get() as $pixel)
                                    <option value="{{ $pixel->pixels }}" {{ old('pixels') == $pixel->pixels ? 'selected' : '' }}>
                                        {{ $pixel->pixels }} px</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">No of Cameras</label>
                            <select name="no_of_cameras" class="admin-form-control" required>
                                <option value="">Select Number</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('no_of_cameras') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Screen Size</label>
                            <input type="text" name="screen_size" value="{{ old('screen_size') }}" class="admin-form-control"
                                required>
                            <small style="color:var(--admin-text-secondary);">Only numbers without inches</small>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">RAM</label>
                            <select name="ram_in_gb" class="admin-form-control" required>
                                <option value="">Select GB</option>
                                <option value="256">256 MB</option>
                                <option value="512">512 MB</option>
                                <option value="1">1 GB</option>
                                <option value="1.5">1.5 GB</option>
                                <option value="3">3 GB</option>
                                @for($i = 2; $i <= 24; $i += 2)
                                    <option value="{{ $i }}" {{ old('ram_in_gb') == $i ? 'selected' : '' }}>{{ $i }} GB</option>
                                @endfor
                            </select>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">ROM</label>
                            <select name="rom_in_gb" class="admin-form-control" required>
                                <option value="">Select GB</option>
                                @for($i = 4; $i <= 512; $i *= 2)
                                    <option value="{{ $i }}" {{ old('rom_in_gb') == $i ? 'selected' : '' }}>{{ $i }} GB</option>
                                @endfor
                                <option value="1024" {{ old('rom_in_gb') == 1024 ? 'selected' : '' }}>1024 GB</option>
                                <option value="2048" {{ old('rom_in_gb') == 2048 ? 'selected' : '' }}>2048 GB</option>
                                <option value="3072" {{ old('rom_in_gb') == 3072 ? 'selected' : '' }}>3072 GB</option>
                            </select>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Operating System</label>
                            <select name="operating_system" class="admin-form-control" required>
                                <option value="">Select OS</option>
                                <option value="android">Android</option>
                                <option value="symbian">Symbian</option>
                                <option value="ios">iOS</option>
                                <option value="windows">Windows</option>
                                <option value="blackberry">Blackberry</option>
                                <option value="huawei">Huawei</option>
                            </select>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">No of SIMs</label>
                            <select name="no_of_sims" class="admin-form-control" required>
                                <option value="">Select SIM</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Body & Media Card --}}
            <div class="admin-card" style="margin-bottom:24px;">
                <div class="admin-card-header">
                    <h2>Content & Media</h2>
                </div>
                <div class="admin-card-body">
                    <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">Body</label>
                        <textarea class="admin-form-control" name="body" id="editor" rows="5">{!! old('body', '') !!}</textarea>
                    </div>
                    <div class="admin-form-grid" style="grid-template-columns:repeat(3,auto);justify-content:start;">
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_featured" style="width:18px;height:18px;"> Featured
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_new" style="width:18px;height:18px;"> New
                        </label>
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" name="is_hot" style="width:18px;height:18px;"> Hot
                        </label>
                    </div>
                    <div class="admin-form-group" style="margin-top:16px;"><label class="admin-form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" class="admin-form-control" required>
                    </div>
                    <div style="margin-top:16px;">
                        <label class="admin-form-label">Other Images</label>
                        <div class="images">
                            <a class="btn-admin-sm plusSign" style="cursor:pointer;margin-bottom:12px;display:inline-block;"><i
                                    class="fas fa-plus"></i> Add Image</a>
                            <div class="bigImages" style="margin-bottom:8px;">
                                <input type="file" name="image[]" class="admin-form-control" required>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i>
                            Create Mobile</button></div>
                </div>
            </div>
        </form>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script>
        $(".plusSign").click(function () { $(".images").append($(".bigImages:first").clone().val("")); });
        if (document.querySelector('#editor')) {
            ClassicEditor.create(document.querySelector('#editor')).then(e => console.log(e)).catch(e => console.error(e));
        }
    </script>
@endsection
@section('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>
@endsection