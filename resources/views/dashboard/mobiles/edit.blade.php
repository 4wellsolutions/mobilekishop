@extends('layouts.dashboard')
@section('title', 'Edit Mobile')
@section('content')
    @php
        $excludedKeys = [
            "id",
            "thumbnail",
            "url",
            "created_at",
            "updated_at",
            "deleted_at",
            "images_count",
            "brand_id",
            "slug",
            "release_date",
            "body",
            "operating_system",
            "no_of_sims",
            "rom_in_gb",
            "ram_in_gb",
            "screen_size",
            "no_of_cameras",
            "pixels",
            "battery"
        ];
        $column12Keys = ["title", "description", "keywords", "slug"];
    @endphp

    <div class="admin-page-header">
        <div>
            <h1>Edit Mobile</h1>
            <div class="breadcrumb-nav"><a href="{{ route('dashboard.index') }}">Dashboard</a><span
                    class="separator">/</span><a href="{{ route('dashboard.mobile.index') }}">Mobiles</a><span
                    class="separator">/</span>Edit</div>
        </div>
    </div>
    @include('includes.info-bar')

    {{-- Re-fetch URL --}}
    <div class="admin-card" style="margin-bottom:24px;">
        <div class="admin-card-header">
            <h2><i class="fas fa-sync-alt" style="margin-right:8px;opacity:0.5;"></i>Re-fetch Data</h2>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('dashboard.mobile.edit', $mobile->id) }}" method="get">
                <div style="display:flex;gap:12px;align-items:flex-end;">
                    <div class="admin-form-group" style="flex:1;margin-bottom:0;">
                        <input type="url" name="url" value="{{ isset($_GET['url']) ? $_GET['url'] : '' }}"
                            class="admin-form-control" placeholder="Enter WhatMobile URL to re-fetch..." required>
                    </div>
                    <button class="btn-admin-primary" type="submit"><i class="fas fa-download"></i> Re Fetch</button>
                </div>
            </form>
        </div>
    </div>

    @php use Illuminate\Support\Facades\DB;
    $columns = DB::getSchemaBuilder()->getColumnListing('mobiles'); @endphp

    <form id="form_validation" action="{{ route('dashboard.mobile.update', $mobile->id) }}" class="needs-validation"
        enctype="multipart/form-data" novalidate method="post">
        @csrf @method('put')

        {{-- Brand & Fields --}}
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Mobile Details</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-grid">
                    <div class="admin-form-group"><label class="admin-form-label">Brand</label>
                        <select name="brand_id" class="admin-form-control">
                            @foreach($brands as $b)
                                <option value="{{ $b->id }}" {{ $b->id == $mobile->brand_id ? 'selected' : '' }}>{{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(isset($_GET['url']))
                        <div class="admin-form-group"><label class="admin-form-label">Model</label>
                            <input type="text" name="model" value="{{ $name }}" class="admin-form-control" required>
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Price in PKR</label>
                            <input type="text" name="price_in_pkr" value="{{ $price_in_pkr }}" class="admin-form-control">
                        </div>
                        <div class="admin-form-group"><label class="admin-form-label">Price in Dollar</label>
                            <input type="text" name="price_in_dollar" value="{{ $price_in_dollar }}" class="admin-form-control">
                        </div>
                        @foreach($data as $d)
                            <div class="admin-form-group" @if(in_array($d[0], $column12Keys)) style="grid-column:1/-1;" @endif>
                                <label class="admin-form-label">{{ $d[0] }}</label>
                                <input type="text" name="{{ \Illuminate\Support\Str::slug($d[0], '_') }}" value="{{ $d[1] }}"
                                    class="admin-form-control">
                            </div>
                        @endforeach
                    @else
                        @foreach($columns as $column)
                            @if(!in_array($column, $excludedKeys))
                                <div class="admin-form-group" @if(in_array($column, $column12Keys)) style="grid-column:1/-1;" @endif>
                                    <label class="admin-form-label">{{ ucwords(Str::slug($column, '-')) }}</label>
                                    <input type="text" name="{{ $column }}" value="{{ $mobile[$column] }}" class="admin-form-control">
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Technical Specs Card --}}
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Technical Specifications</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-grid">
                    <div class="admin-form-group"><label class="admin-form-label">Release Date</label>
                        <input type="date" name="release_date" value="{{ date('Y-m-d', strtotime($mobile->release_date)) }}"
                            class="admin-form-control" required>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Camera Pixels</label>
                        <select name="pixels" class="admin-form-control" required>
                            <option value="">Select Pixels</option>
                            @foreach(\App\MobilePixel::orderBy('pixels', 'asc')->get() as $pixel)
                                <option value="{{ $pixel->pixels }}" {{ $mobile->pixels == $pixel->pixels ? 'selected' : '' }}>
                                    {{ $pixel->pixels }} px</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">No of Cameras</label>
                        <select name="no_of_cameras" class="admin-form-control" required>
                            <option value="">Select Number</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ $mobile->no_of_cameras == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Screen Size</label>
                        <input type="text" name="screen_size" value="{{ $mobile->screen_size }}" class="admin-form-control"
                            required>
                        <small style="color:var(--admin-text-secondary);">Only numbers without inches</small>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Battery (Capacity:
                            {{ $mobile->capacity }})</label>
                        <input type="text" name="battery" value="{{ $mobile->battery }}" class="admin-form-control"
                            required>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">RAM</label>
                        <select name="ram_in_gb" class="admin-form-control" required>
                            <option value="">Select GB</option>
                            <option value="256" {{ $mobile->ram_in_gb == '256' ? 'selected' : '' }}>256 MB</option>
                            <option value="512" {{ $mobile->ram_in_gb == '512' ? 'selected' : '' }}>512 MB</option>
                            <option value="1" {{ $mobile->ram_in_gb == '1' ? 'selected' : '' }}>1 GB</option>
                            <option value="1.5" {{ $mobile->ram_in_gb == '1.5' ? 'selected' : '' }}>1.5 GB</option>
                            <option value="3" {{ $mobile->ram_in_gb == '3' ? 'selected' : '' }}>3 GB</option>
                            @for($i = 2; $i <= 24; $i += 2)
                                <option value="{{ $i }}" {{ $mobile->ram_in_gb == $i ? 'selected' : '' }}>{{ $i }} GB</option>
                            @endfor
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">ROM</label>
                        <select name="rom_in_gb" class="admin-form-control" required>
                            <option value="">Select GB</option>
                            @for($i = 4; $i <= 512; $i *= 2)
                                <option value="{{ $i }}" {{ $mobile->rom_in_gb == $i ? 'selected' : '' }}>{{ $i }} GB</option>
                            @endfor
                            <option value="1024" {{ $mobile->rom_in_gb == 1024 ? 'selected' : '' }}>1024 GB</option>
                            <option value="2048" {{ $mobile->rom_in_gb == 2048 ? 'selected' : '' }}>2048 GB</option>
                            <option value="3072" {{ $mobile->rom_in_gb == 3072 ? 'selected' : '' }}>3072 GB</option>
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">Operating System</label>
                        <select name="operating_system" class="admin-form-control" required>
                            <option value="">Select OS</option>
                            <option value="android" {{ $mobile->operating_system == 'android' ? 'selected' : '' }}>Android
                            </option>
                            <option value="symbian" {{ $mobile->operating_system == 'symbian' ? 'selected' : '' }}>Symbian
                            </option>
                            <option value="ios" {{ $mobile->operating_system == 'ios' ? 'selected' : '' }}>iOS</option>
                            <option value="windows" {{ $mobile->operating_system == 'windows' ? 'selected' : '' }}>Windows
                            </option>
                            <option value="blackberry" {{ $mobile->operating_system == 'blackberry' ? 'selected' : '' }}>
                                Blackberry</option>
                            <option value="huawei" {{ $mobile->operating_system == 'huawei' ? 'selected' : '' }}>Huawei
                            </option>
                        </select>
                    </div>
                    <div class="admin-form-group"><label class="admin-form-label">No of SIMs</label>
                        <select name="no_of_sims" class="admin-form-control" required>
                            <option value="">Select SIM</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ $mobile->no_of_sims == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content & Media Card --}}
        <div class="admin-card" style="margin-bottom:24px;">
            <div class="admin-card-header">
                <h2>Content & Media</h2>
            </div>
            <div class="admin-card-body">
                <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">Body</label>
                    <textarea class="admin-form-control" name="body" id="editor" rows="5">{{ $mobile->body }}</textarea>
                </div>

                {{-- Current Thumbnail --}}
                <div style="margin-bottom:16px;">
                    <label class="admin-form-label">Current Thumbnail</label>
                    <div style="margin-top:8px;"><img src="{{ $mobile->thumbnail }}"
                            style="max-height:100px;border-radius:8px;border:1px solid var(--admin-border);"></div>
                </div>
                <div class="admin-form-group" style="margin-bottom:16px;"><label class="admin-form-label">New
                        Thumbnail</label>
                    <input type="file" name="thumbnail" class="admin-form-control" required>
                    <input type="hidden" name="image_exist" value="{{ count($mobile->images) }}">
                </div>

                {{-- Existing Images --}}
                @if(!$mobile->images->isEmpty())
                    <div style="margin-bottom:16px;">
                        <label class="admin-form-label">Existing Images</label>
                        <div
                            style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;margin-top:8px;">
                            @foreach($mobile->images as $img)
                                <div style="text-align:center;border:1px solid var(--admin-border);border-radius:8px;padding:8px;">
                                    <img src="{{ URL::to('/mobiles/') }}/{{ $img->name }}"
                                        style="max-height:120px;border-radius:4px;">
                                    <div style="margin-top:8px;"><a href="#"
                                            data-url="{{ route('dashboard.mobile.delete.file', [$mobile->id, $img->name]) }}"
                                            class="deleteButton" style="color:#ef4444;font-size:13px;"><i class="fas fa-trash"></i>
                                            Delete</a></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Additional Images --}}
                <div style="margin-top:16px;">
                    <label class="admin-form-label">Add More Images</label>
                    <div class="images">
                        <a class="btn-admin-sm plusSign" style="cursor:pointer;margin:8px 0 12px;display:inline-block;"><i
                                class="fas fa-plus"></i> Add Image</a>
                        <div class="bigImages" style="margin-bottom:8px;">
                            <input type="file" name="image[]" class="admin-form-control" required>
                        </div>
                    </div>
                </div>

                <div style="margin-top:24px;"><button type="submit" class="btn-admin-primary"><i class="fas fa-save"></i>
                        Update Mobile</button></div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/31.1.0/classic/ckeditor.js"></script>
    <script>
        $(".plusSign").click(function () { $(".images").append($(".bigImages:first").clone().val("")); });
        ClassicEditor.create(document.querySelector('#editor')).then(e => console.log(e)).catch(e => console.error(e));
        $('.deleteButton').click(function () { if (confirm('Are you sure?')) { var url = $(this).data('url'); window.location.href = url; } });
    </script>
@endsection
@section('styles')
    <style>
        .ck-editor__editable_inline {
            min-height: 200px;
        }
    </style>
@endsection