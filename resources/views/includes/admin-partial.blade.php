<!-- resources/views/partials/admin_menu.blade.php -->
<div class="admin-bar bg-dark text-white py-2">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col">
                <span class="me-3">Admin Panel</span>
                <a href="{{ route('dashboard.index') }}" class="btn btn-sm btn-outline-light me-2">Dashboard</a>
                @if(Route::is('product.show') && isset($product))
                    <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-sm btn-outline-light" target="_blank">Edit Product</a>
                @endif

            </div>
            <div class="col-auto">
                <small>Logged in as Admin</small>
            </div>
        </div>
    </div>
</div>