@extends('layouts.admin.app', ['pageTitle' => $pageTitle])

@section('content')

{{-- ====== Breadcrumb ====== --}}
<div class="breadcrumb-header justify-content-between">
    <div class="d-flex align-items-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle ?? 'Dashboard' }}</li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
            </ol>
        </nav>
    </div>
</div>

{{-- ============================================================
     SUMMARY STATS
============================================================ --}}
<div class="row g-3 mb-3">
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px; background: #eef2ff; border-radius: 12px;">
                    <i class="fas fa-book text-primary fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Total Books</div>
                    <div class="fw-bold fs-5">{{ $stats['total_books'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px; background: #ecfdf5; border-radius: 12px;">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Active Books</div>
                    <div class="fw-bold fs-5">{{ $stats['active_books'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Out of Stock</div>
                    <div class="fw-bold fs-5">{{ $stats['out_of_stock'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                     style="width: 48px; height: 48px; background: #fdf2f8; border-radius: 12px;">
                    <i class="fas fa-coins text-danger fa-lg"></i>
                </div>
                <div>
                    <div class="small text-muted">Stock Value</div>
                    <div class="fw-bold fs-5">৳{{ number_format($stats['total_stock_value'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     FILTER SECTION
============================================================ --}}
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.ebook.index') }}" id="ebookFilterForm">
            <div class="row g-2 align-items-end">

                {{-- Status --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Featured --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Featured</label>
                    <select name="is_featured" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured Only</option>
                        <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>

                {{-- Stock --}}
                <div class="col-md-2 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Stock</label>
                    <select name="stock" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="in"  {{ request('stock') === 'in'  ? 'selected' : '' }}>In Stock</option>
                        <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-md-6 col-sm-6">
                    <label class="form-label mb-1 small fw-bold">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           value="{{ request('search') }}"
                           placeholder="Book name, author, description...">
                </div>

                {{-- Buttons --}}
                <div class="col-md-12 d-flex justify-content-end gap-2 mt-2">
                    <a href="{{ route('admin.ebook.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ============================================================
     E-BOOK LIST
============================================================ --}}
<div class="main-content-body">
    <div class="row row-sm">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <p class="card-title my-0">
                        {{ $pageTitle }}
                        <span class="badge bg-danger side-badge" style="font-size:17px;">
                            {{ $ebooks->count() }}
                        </span>
                    </p>
                    <div class="d-flex">
                        <button type="button" class="btn btn-success me-2"
                                data-bs-toggle="modal" data-bs-target="#addEbookModal">
                            <i class="fas fa-plus d-inline"></i> Add New Book
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="file-datatable"
                               class="border-top-0 table table-bordered text-nowrap key-buttons border-bottom">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">SL</th>
                                    <th class="border-bottom-0">Photo</th>
                                    <th class="border-bottom-0">Book Name</th>
                                    <th class="border-bottom-0">Author</th>
                                    <th class="border-bottom-0">Pages</th>
                                    <th class="border-bottom-0">Price</th>
                                    <th class="border-bottom-0">Sell Price</th>
                                    <th class="border-bottom-0">Commission</th>
                                    <th class="border-bottom-0">Stock</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ebooks as $key => $ebook)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        {{-- Photo --}}
                                        <td>
                                            @if ($ebook->book_photo)
                                                <img src="{{ asset('storage/' . $ebook->book_photo) }}"
                                                     alt="{{ $ebook->book_name }}"
                                                     style="width: 100%; height: 60px; object-fit: cover;
                                                            border-radius: 6px; border: 1px solid #e5e7eb;">
                                            @else
                                                <span class="badge bg-light text-muted">No Image</span>
                                            @endif
                                        </td>

                                        {{-- Book Name --}}
                                        <td>
                                            <div class="fw-bold">{{ $ebook->book_name }}</div>
                                            @if ($ebook->is_featured)
                                                <span class="badge bg-warning text-dark">Featured</span>
                                            @endif
                                        </td>

                                        {{-- Author --}}
                                        <td>{{ $ebook->author_name }}</td>

                                        {{-- Pages --}}
                                        <td>{{ $ebook->total_page ?? '—' }}</td>

                                        {{-- Price --}}
                                        <td>
                                            <span class="text-muted text-decoration-line-through small d-block">
                                                ৳{{ number_format($ebook->price, 0) }}
                                            </span>
                                        </td>

                                        {{-- Sell Price --}}
                                        <td>
                                            <span class="fw-bold text-success">
                                                ৳{{ number_format($ebook->sell_price, 0) }}
                                            </span>
                                            @if ($ebook->has_discount)
                                                <small class="text-muted d-block">
                                                    ({{ $ebook->discount_percent }}% off)
                                                </small>
                                            @endif
                                        </td>

                                        {{-- Commission --}}
                                        <td>
                                            <span class="badge bg-info">
                                                {{ number_format($ebook->commission, 0) }}%
                                            </span>
                                        </td>

                                        {{-- Stock --}}
                                        <td>
                                            @if ($ebook->quantity > 0)
                                                <span class="badge bg-success">{{ $ebook->quantity }}</span>
                                            @else
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @endif
                                        </td>

                                        {{-- Status --}}
                                        <td>
                                            @if ($ebook->status === 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editEbookModal-{{ $ebook->id }}"
                                                    title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('admin.ebook.delete', $ebook->id) }}"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure to delete this book?')"
                                               title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                            No Books Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     ADD MODAL
============================================================ --}}
<div class="modal fade" id="addEbookModal" tabindex="-1"
     aria-labelledby="addEbookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <form action="{{ route('admin.ebook.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-light" id="addEbookModalLabel">
                        <i class="fas fa-book-medical"></i> Add New E-Book
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        {{-- Book Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Book Name <span class="text-danger">*</span></label>
                            <input type="text" name="book_name" class="form-control"
                                   value="{{ old('book_name') }}"
                                   placeholder="e.g. Higher Math 1st Paper" required>
                            @error('book_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Author Name --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Author Name <span class="text-danger">*</span></label>
                            <input type="text" name="author_name" class="form-control"
                                   value="{{ old('author_name') }}"
                                   placeholder="e.g. Prof. Rafiqul Islam" required>
                            @error('author_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Photo + Page --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Book Photo</label>
                            <input type="file" name="book_photo" class="form-control" accept="image/*"
                                   onchange="previewAddImage(event)">
                            <small class="text-muted">Max 2MB • jpg, jpeg, png, webp</small>
                            @error('book_photo')
                                <small class="text-danger d-block">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                <img id="addImagePreview" src="" alt="Preview"
                                     style="display:none; max-width: 100px; max-height: 120px;
                                            object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                            </div>
                        </div>

                        {{-- Total Page --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Page</label>
                            <input type="number" name="total_page" class="form-control"
                                   value="{{ old('total_page') }}" min="1"
                                   placeholder="e.g. 450">
                        </div>

                        {{-- Price --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', 0) }}" min="0" step="1" required>
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Discount --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Discount (%)</label>
                            <input type="number" name="discount" class="form-control"
                                   value="{{ old('discount', 0) }}" min="0" max="100" step="0.01">
                            @error('discount')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Sell Price --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sell Price (৳)</label>
                            <input type="number" name="sell_price" class="form-control"
                                   value="{{ old('sell_price') }}" min="0" step="1"
                                   placeholder="Auto if empty" readonly>
                            @error('sell_price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Commission --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Commission (%)</label>
                            <input type="number" name="commission" class="form-control"
                                   value="{{ old('commission', 0) }}" min="0" max="100" step="0.01"
                                   placeholder="e.g. 20">
                        </div>

                        {{-- Quantity --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity (Stock)</label>
                            <input type="number" name="quantity" class="form-control"
                                   value="{{ old('quantity', 0) }}" min="0"
                                   placeholder="e.g. 100">
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active"   {{ old('status') === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Featured --}}
                        <div class="col-md-4 mb-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_featured"
                                       id="add_is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="add_is_featured">Featured Book</label>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="Short description (optional)">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ============================================================
     EDIT MODALS (per row)
============================================================ --}}
@foreach ($ebooks as $ebook)
    <div class="modal fade" id="editEbookModal-{{ $ebook->id }}" tabindex="-1"
         aria-labelledby="editEbookModalLabel-{{ $ebook->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <form action="{{ route('admin.ebook.update', $ebook->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-light" id="editEbookModalLabel-{{ $ebook->id }}">
                            <i class="fas fa-edit"></i> Edit E-Book
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            {{-- Book Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Book Name <span class="text-danger">*</span></label>
                                <input type="text" name="book_name" class="form-control"
                                       value="{{ $ebook->book_name }}" required>
                            </div>

                            {{-- Author --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Author Name <span class="text-danger">*</span></label>
                                <input type="text" name="author_name" class="form-control"
                                       value="{{ $ebook->author_name }}" required>
                            </div>

                            {{-- Photo --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Book Photo</label>
                                <input type="file" name="book_photo" class="form-control" accept="image/*"
                                       onchange="previewEditImage(event, {{ $ebook->id }})">
                                <small class="text-muted">Max 2MB • Leave empty to keep current</small>
                                <div class="mt-2">
                                    @if ($ebook->book_photo)
                                        <img id="editImagePreview_{{ $ebook->id }}"
                                             src="{{ asset('storage/' . $ebook->book_photo) }}"
                                             alt="Current"
                                             style="max-width: 100px; max-height: 120px; object-fit: cover;
                                                    border-radius: 6px; border: 1px solid #e5e7eb;">
                                    @else
                                        <img id="editImagePreview_{{ $ebook->id }}" src="" alt="Preview"
                                             style="display:none; max-width: 100px; max-height: 120px;
                                                    object-fit: cover; border-radius: 6px; border: 1px solid #e5e7eb;">
                                    @endif
                                </div>
                            </div>

                            {{-- Page --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Page</label>
                                <input type="number" name="total_page" class="form-control"
                                       value="{{ $ebook->total_page }}" min="1">
                            </div>

                            {{-- Price --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Price (৳) <span class="text-danger">*</span></label>
                                <input type="number" name="price" class="form-control"
                                       value="{{ $ebook->price }}" min="0" step="1" required>
                            </div>

                            {{-- Discount --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" name="discount" class="form-control"
                                       value="{{ $ebook->discount }}" min="0" max="100" step="0.01">
                            </div>

                            {{-- Sell Price --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sell Price (৳)</label>
                                <input type="number" name="sell_price" class="form-control"
                                       value="{{ $ebook->sell_price }}" min="0" step="1" readonly>
                            </div>

                            {{-- Commission --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Commission (%)</label>
                                <input type="number" name="commission" class="form-control"
                                       value="{{ $ebook->commission }}" min="0" max="100" step="0.01">
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quantity (Stock)</label>
                                <input type="number" name="quantity" class="form-control"
                                       value="{{ $ebook->quantity }}" min="0">
                            </div>

                            {{-- Status --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active"   {{ $ebook->status === 'active'   ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $ebook->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            {{-- Featured --}}
                            <div class="col-md-4 mb-3 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                           id="edit_is_featured_{{ $ebook->id }}" value="1"
                                           {{ $ebook->is_featured ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_is_featured_{{ $ebook->id }}">
                                        Featured Book
                                    </label>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control"
                                          rows="3">{{ $ebook->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

{{-- ============================================================
     SCRIPTS — Image Preview + Auto Sell Price
============================================================ --}}
@push('admin')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ============================================================
       IMAGE PREVIEW — Add
    ============================================================ */
    window.previewAddImage = function (event) {
        const input   = event.target;
        const preview = document.getElementById('addImagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    };

    /* ============================================================
       IMAGE PREVIEW — Edit (per row)
    ============================================================ */
    window.previewEditImage = function (event, ebookId) {
        const input   = event.target;
        const preview = document.getElementById('editImagePreview_' + ebookId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    /* ============================================================
       AUTO-CALCULATE SELL PRICE
       sell_price = price - (price × discount / 100)
    ============================================================ */
    function calculateSellPrice(modalEl) {
        const priceInput     = modalEl.querySelector('input[name="price"]');
        const discountInput  = modalEl.querySelector('input[name="discount"]');
        const sellPriceInput = modalEl.querySelector('input[name="sell_price"]');

        if (!priceInput || !sellPriceInput) return;

        const price    = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountInput?.value) || 0;

        if (price <= 0) {
            sellPriceInput.value = '';
            return;
        }

        let sellPrice = price;

        if (discount > 0 && discount <= 100) {
            sellPrice = price - (price * discount / 100);
        }

        // Round to 2 decimals
        sellPriceInput.value = Math.round(sellPrice * 100) / 100;
    }

    // Attach to all modals (Add + Edit)
    document.querySelectorAll('.modal').forEach(function (modal) {
        const priceInput    = modal.querySelector('input[name="price"]');
        const discountInput = modal.querySelector('input[name="discount"]');

        if (priceInput)    priceInput.addEventListener('input',    () => calculateSellPrice(modal));
        if (discountInput) discountInput.addEventListener('input', () => calculateSellPrice(modal));
    });

});
</script>
@endpush