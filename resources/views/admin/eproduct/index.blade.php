@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

<style>
    /* ============================================================
       MODAL SCROLL FIX — works even with <form> wrapper
    ============================================================ */
    #addModal .modal-content,
    #editModal .modal-content {
        max-height: 90vh;
        height: 90vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #addModal .modal-content > .modal-header,
    #editModal .modal-content > .modal-header {
        flex-shrink: 0;
        flex-grow: 0;
    }

    /* ⚠️ FIX: form ট্যাগও flex container হবে */
    #addModal .modal-content > form,
    #editModal .modal-content > form {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
        overflow: hidden;
    }

    #addModal .modal-content > form > .modal-body,
    #editModal .modal-content > form > .modal-body {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        flex: 1 1 auto;
        min-height: 0;
    }

    #addModal .modal-content > form > .modal-footer,
    #editModal .modal-content > form > .modal-footer {
        flex-shrink: 0;
        flex-grow: 0;
    }

    /* Nice scrollbar */
    #addModal .modal-body::-webkit-scrollbar,
    #editModal .modal-body::-webkit-scrollbar {
        width: 8px;
    }
    #addModal .modal-body::-webkit-scrollbar-track,
    #editModal .modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    #addModal .modal-body::-webkit-scrollbar-thumb,
    #editModal .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    #addModal .modal-body::-webkit-scrollbar-thumb:hover,
    #editModal .modal-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Mobile: full height */
    @media (max-width: 991.98px) {
        #addModal .modal-dialog,
        #editModal .modal-dialog {
            margin: 0.5rem;
        }
        #addModal .modal-content,
        #editModal .modal-content {
            max-height: calc(100vh - 1rem);
            height: calc(100vh - 1rem);
        }
    }
</style>

<div class="container-fluid py-3">

    {{-- ============ HEADER ============ --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1 text-light">{{ $pageTitle }}</h4>
            <p class="text-light small mb-0">Manage all your e-products in one place</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus-circle"></i> Add Product
        </button>
    </div>

    {{-- ============ STATS ============ --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;">
                        <i class="fas fa-box text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Products</div>
                        <div class="fw-bold fs-4">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success-subtle rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;">
                        <i class="fas fa-check-circle text-success fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Active</div>
                        <div class="fw-bold fs-4 text-success">{{ number_format($stats['active']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning-subtle rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;">
                        <i class="fas fa-star text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Featured</div>
                        <div class="fw-bold fs-4 text-warning">{{ number_format($stats['featured']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-danger-subtle rounded-3 d-flex align-items-center justify-content-center"
                         style="width:52px;height:52px;">
                        <i class="fas fa-exclamation-triangle text-danger fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Out of Stock</div>
                        <div class="fw-bold fs-4 text-danger">{{ number_format($stats['out_stock']) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ TABLE ============ --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width:40px;">#</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Commission</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="ps-4">{{ $products->firstItem() + $loop->index }}</td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($product->product_photo)
                                            <img src="{{ asset('storage/' . $product->product_photo) }}"
                                                 style="width:48px;height:48px;object-fit:cover;border-radius:8px;box-shadow:0 2px 6px rgba(0,0,0,.08);">
                                        @else
                                            <div style="width:48px;height:48px;background:#eef2ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                                <i class="fas fa-box text-primary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold small">
                                                {{ Str::limit($product->product_name, 32) }}
                                            </div>
                                            <div class="d-flex gap-1 flex-wrap mt-1">
                                                @if ($product->is_featured)
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size:10px;">
                                                        <i class="fas fa-star"></i> Featured
                                                    </span>
                                                @endif
                                                @if ($product->size || $product->color)
                                                    <span class="badge bg-light text-secondary border" style="font-size:10px;">
                                                        {{ collect([$product->size, $product->color])->filter()->implode(' • ') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="small text-muted">{{ $product->brand_name ?? '—' }}</td>

                                <td>
                                    @if ($product->has_discount)
                                        <div class="small text-muted text-decoration-line-through">
                                            ৳{{ number_format($product->price, 0) }}
                                        </div>
                                        <div class="fw-bold text-primary">
                                            ৳{{ number_format($product->customer_price, 0) }}
                                        </div>
                                    @else
                                        <div class="fw-bold text-primary">
                                            ৳{{ number_format($product->customer_price, 0) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="fw-semibold text-success">
                                    ৳{{ number_format($product->commission, 0) }}
                                </td>

                                <td>
                                    @if ($product->quantity > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            {{ $product->quantity }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            Out
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($product->status === 'active')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button"
                                                class="btn btn-outline-primary edit-btn"
                                                title="Edit"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->product_name }}"
                                                data-brand="{{ $product->brand_name }}"
                                                data-size="{{ $product->size }}"
                                                data-color="{{ $product->color }}"
                                                data-price="{{ $product->price }}"
                                                data-discount="{{ $product->discount }}"
                                                data-customer_price="{{ $product->customer_price }}"
                                                data-commission="{{ $product->commission }}"
                                                data-quantity="{{ $product->quantity }}"
                                                data-description="{{ $product->description }}"
                                                data-warranty="{{ $product->warranty }}"
                                                data-status="{{ $product->status }}"
                                                data-featured="{{ $product->is_featured ? 1 : 0 }}"
                                                data-photo="{{ $product->product_photo ? asset('storage/' . $product->product_photo) : '' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <a href="{{ route('admin.eproduct.delete', $product->id) }}"
                                           class="btn btn-outline-danger"
                                           title="Delete"
                                           onclick="return confirm('Delete this product permanently?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3"
                                             style="width:80px;height:80px;">
                                            <i class="fas fa-box-open fa-2x text-muted"></i>
                                        </div>
                                        <h6 class="fw-bold">No products found</h6>
                                        <p class="text-muted small mb-3">Click "Add Product" to create your first product</p>
                                        <button class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#addModal">
                                            <i class="fas fa-plus"></i> Add Product
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($products->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <small class="text-muted">
                    Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
                    of {{ $products->total() }} products
                </small>
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

{{-- ============================================================
     ADD MODAL
============================================================ --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 text-white"
                 style="background:linear-gradient(135deg,#4f46e5,#7c3aed);">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-light">Add New Product</h5>
                        <small class="opacity-75">Fill all required (*) fields</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.eproduct.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">

                    {{-- IMAGE UPLOAD --}}
                    <div class="text-center mb-4">
                        <label for="add_photo" class="d-inline-block position-relative" style="cursor:pointer;">
                            <div id="add_photo_preview"
                                 class="rounded-3 d-flex align-items-center justify-content-center mx-auto"
                                 style="width:140px;height:140px;background:#f8fafc;border:2px dashed #cbd5e1;overflow:hidden;">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-1"></i>
                                    <div class="text-muted" style="font-size:11px;">Upload Photo</div>
                                </div>
                            </div>
                            <input type="file" name="product_photo" id="add_photo"
                                   class="d-none" accept="image/*">
                            <span class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:32px;height:32px;bottom:-6px;right:-6px;box-shadow:0 2px 8px rgba(0,0,0,.15);">
                                <i class="fas fa-camera" style="font-size:12px;"></i>
                            </span>
                        </label>
                        <div class="small text-muted mt-2" style="font-size:11px;">
                            JPG, PNG, WEBP — Max 2MB
                        </div>
                    </div>

                    {{-- BASIC INFO --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:26px;height:26px;">
                                <i class="fas fa-info-circle" style="font-size:12px;"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Basic Information</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-tag text-muted"></i></span>
                                    <input type="text" name="product_name" class="form-control"
                                           placeholder="e.g. Wireless Bluetooth Earbuds"
                                           value="{{ old('product_name') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Brand Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-copyright text-muted"></i></span>
                                    <input type="text" name="brand_name" class="form-control"
                                           placeholder="e.g. SoundMax, Samsung"
                                           value="{{ old('brand_name') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Warranty</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-shield-alt text-muted"></i></span>
                                    <input type="text" name="warranty" class="form-control"
                                           placeholder="e.g. 1 Year, 6 Months"
                                           value="{{ old('warranty') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Size</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-ruler-combined text-muted"></i></span>
                                    <input type="text" name="size" class="form-control"
                                           placeholder="e.g. M, L, XL"
                                           value="{{ old('size') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Color</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-palette text-muted"></i></span>
                                    <input type="text" name="color" class="form-control"
                                           placeholder="e.g. Red, Blue, Black"
                                           value="{{ old('color') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3"
                                          placeholder="Write a short product description...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- PRICING --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:26px;height:26px;">
                                <i class="fas fa-money-bill-wave" style="font-size:12px;"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Pricing</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    Price (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-success">৳</span>
                                    <input type="number" name="price" step="0.01" min="0"
                                           class="form-control" placeholder="0.00"
                                           value="{{ old('price', 0) }}" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number" name="discount" step="0.01" min="0" max="100"
                                           class="form-control" placeholder="0"
                                           value="{{ old('discount', 0) }}">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    Customer Price (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-primary">৳</span>
                                    <input type="number" name="customer_price" step="0.01" min="0"
                                           class="form-control" placeholder="Auto-calculated"
                                           value="{{ old('customer_price', 0) }}" required>
                                </div>
                                <small class="text-muted" style="font-size:11px;">
                                    <i class="fas fa-info-circle"></i> Price & discount থেকে auto হিসাব হবে
                                </small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">
                                    Commission (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-warning-subtle fw-bold text-warning">৳</span>
                                    <input type="number" name="commission" step="0.01" min="0"
                                           class="form-control" placeholder="e.g. 300"
                                           value="{{ old('commission', 0) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INVENTORY --}}
                    <div class="mb-2">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:26px;height:26px;">
                                <i class="fas fa-cog" style="font-size:12px;"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Inventory & Status</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">
                                    Quantity <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-cubes text-muted"></i></span>
                                    <input type="number" name="quantity" min="0"
                                           class="form-control" placeholder="0"
                                           value="{{ old('quantity', 0) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                        ✅ Active (Visible to users)
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        ⛔ Inactive (Hidden)
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <div class="card bg-warning-subtle border-warning-subtle">
                                    <div class="card-body py-2">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_featured"
                                                   id="add_is_featured" value="1"
                                                   {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-semibold" for="add_is_featured">
                                                ⭐ Mark as Featured Product
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     EDIT MODAL
============================================================ --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 text-white"
                 style="background:linear-gradient(135deg,#0ea5e9,#6366f1);">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center"
                         style="width:42px;height:42px;">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-light">Edit Product</h5>
                        <small class="opacity-75">Update product details</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">

                    {{-- IMAGE UPLOAD --}}
                    <div class="text-center mb-4">
                        <label for="edit_photo" class="d-inline-block position-relative" style="cursor:pointer;">
                            <div id="edit_photo_preview"
                                 class="rounded-3 d-flex align-items-center justify-content-center mx-auto"
                                 style="width:140px;height:140px;background:#f8fafc;border:2px dashed #cbd5e1;overflow:hidden;">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-1"></i>
                                    <div class="text-muted" style="font-size:11px;">Change Photo</div>
                                </div>
                            </div>
                            <input type="file" name="product_photo" id="edit_photo"
                                   class="d-none" accept="image/*">
                            <span class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:32px;height:32px;bottom:-6px;right:-6px;box-shadow:0 2px 8px rgba(0,0,0,.15);">
                                <i class="fas fa-camera" style="font-size:12px;"></i>
                            </span>
                        </label>
                        <div class="small text-muted mt-2" style="font-size:11px;">
                            Empty রাখলে পুরনো ছবি থাকবে
                        </div>
                    </div>

                    {{-- BASIC INFO --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:26px;height:26px;">
                                <i class="fas fa-info-circle" style="font-size:12px;"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Basic Information</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-tag text-muted"></i></span>
                                    <input type="text" name="product_name" id="edit_product_name"
                                           class="form-control" placeholder="Product name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Brand Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-copyright text-muted"></i></span>
                                    <input type="text" name="brand_name" id="edit_brand_name"
                                           class="form-control" placeholder="Brand">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Warranty</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-shield-alt text-muted"></i></span>
                                    <input type="text" name="warranty" id="edit_warranty"
                                           class="form-control" placeholder="1 Year, 6 Months">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Size</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-ruler-combined text-muted"></i></span>
                                    <input type="text" name="size" id="edit_size"
                                           class="form-control" placeholder="M, L, XL">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Color</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-palette text-muted"></i></span>
                                    <input type="text" name="color" id="edit_color"
                                           class="form-control" placeholder="Red, Blue">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">Description</label>
                                <textarea name="description" id="edit_description" class="form-control"
                                          rows="3" placeholder="Write a short product description..."></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- PRICING --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:26px;height:26px;">
                                <i class="fas fa-money-bill-wave" style="font-size:12px;"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Pricing</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    Price (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-success">৳</span>
                                    <input type="number" name="price" id="edit_price" step="0.01" min="0"
                                           class="form-control" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number" name="discount" id="edit_discount" step="0.01"
                                           min="0" max="100" class="form-control" placeholder="0">
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">
                                    Customer Price (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold text-primary">৳</span>
                                    <input type="number" name="customer_price" id="edit_customer_price"
                                           step="0.01" min="0" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-semibold">
                                    Commission (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-warning-subtle fw-bold text-warning">৳</span>
                                    <input type="number" name="commission" id="edit_commission"
                                           step="0.01" min="0" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- INVENTORY --}}
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center"
                                  style="width:26px;height:26px;">
                                <i class="fas fa-cog" style="font-size:12px;"></i>
                            </span>
                            <h6 class="fw-bold mb-0">Inventory & Status</h6>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">
                                    Quantity <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-cubes text-muted"></i></span>
                                    <input type="number" name="quantity" id="edit_quantity"
                                           min="0" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="active">✅ Active</option>
                                    <option value="inactive">⛔ Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <div class="card bg-warning-subtle border-warning-subtle">
                                    <div class="card-body py-2">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" name="is_featured"
                                                   id="edit_is_featured" value="1">
                                            <label class="form-check-label small fw-semibold" for="edit_is_featured">
                                                ⭐ Mark as Featured Product
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('admin')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       EDIT MODAL — populate fields
    ============================================================ */
    const editModalEl = document.getElementById('editModal');
    const editModal   = new bootstrap.Modal(editModalEl);
    const editPreview = document.getElementById('edit_photo_preview');

    document.querySelectorAll('.edit-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const d = this.dataset;

            document.getElementById('editForm').action = '/admin/eproducts/update/' + d.id;

            document.getElementById('edit_product_name').value   = d.name || '';
            document.getElementById('edit_brand_name').value     = d.brand || '';
            document.getElementById('edit_size').value           = d.size || '';
            document.getElementById('edit_color').value          = d.color || '';
            document.getElementById('edit_price').value          = d.price || 0;
            document.getElementById('edit_discount').value       = d.discount || 0;
            document.getElementById('edit_customer_price').value = d.customer_price || 0;
            document.getElementById('edit_commission').value     = d.commission || 0;
            document.getElementById('edit_quantity').value       = d.quantity || 0;
            document.getElementById('edit_description').value    = d.description || '';
            document.getElementById('edit_warranty').value       = d.warranty || '';
            document.getElementById('edit_status').value         = d.status || 'active';
            document.getElementById('edit_is_featured').checked  = d.featured == '1';

            if (d.photo) {
                editPreview.innerHTML =
                    '<img src="' + d.photo + '" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">';
            } else {
                editPreview.innerHTML =
                    '<div class="text-center">' +
                        '<i class="fas fa-cloud-upload-alt fa-2x text-primary mb-1"></i>' +
                        '<div class="text-muted" style="font-size:11px;">Change Photo</div>' +
                    '</div>';
            }

            document.querySelector('#editForm input[name="product_photo"]').value = '';

            editModal.show();
        });
    });

    /* ============================================================
       IMAGE PREVIEW — ADD
    ============================================================ */
    const addPhotoInput = document.getElementById('add_photo');
    const addPreview    = document.getElementById('add_photo_preview');

    if (addPhotoInput) {
        addPhotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    addPreview.innerHTML =
                        '<img src="' + ev.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ============================================================
       IMAGE PREVIEW — EDIT
    ============================================================ */
    const editPhotoInput = document.getElementById('edit_photo');

    if (editPhotoInput) {
        editPhotoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    editPreview.innerHTML =
                        '<img src="' + ev.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:10px;">';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* ============================================================
       AUTO-CALC customer_price — ADD
    ============================================================ */
    const addPrice     = document.querySelector('#addModal input[name="price"]');
    const addDiscount  = document.querySelector('#addModal input[name="discount"]');
    const addCustPrice = document.querySelector('#addModal input[name="customer_price"]');

    function recalcAdd() {
        const p = parseFloat(addPrice.value) || 0;
        const d = parseFloat(addDiscount.value) || 0;
        if (p > 0) {
            addCustPrice.value = (p - (p * d / 100)).toFixed(2);
        }
    }
    if (addPrice && addDiscount && addCustPrice) {
        addPrice.addEventListener('input', recalcAdd);
        addDiscount.addEventListener('input', recalcAdd);
    }

    /* ============================================================
       AUTO-CALC customer_price — EDIT
    ============================================================ */
    const editPrice     = document.getElementById('edit_price');
    const editDiscount  = document.getElementById('edit_discount');
    const editCustPrice = document.getElementById('edit_customer_price');

    function recalcEdit() {
        const p = parseFloat(editPrice.value) || 0;
        const d = parseFloat(editDiscount.value) || 0;
        if (p > 0) {
            editCustPrice.value = (p - (p * d / 100)).toFixed(2);
        }
    }
    if (editPrice && editDiscount && editCustPrice) {
        editPrice.addEventListener('input', recalcEdit);
        editDiscount.addEventListener('input', recalcEdit);
    }
});
</script>
@endpush