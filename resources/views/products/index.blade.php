@extends('components.dashboard-layout')

@section('title', 'Data Produk & Menu')

@section('content')

<div
    id="productPage"
    class="space-y-6"
>

    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">
            <span class="material-icons">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <span class="material-icons">error</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Validation Error --}}
    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
            <div class="flex items-start gap-3">
                <span class="material-icons text-red-500">error</span>
                <div>
                    <p class="font-semibold text-red-700">Terdapat kesalahan</p>
                    <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif


    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Produk & Menu</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola menu kopi, minuman, makanan, varian cup, dan harga POS Tomoro Coffee.</p>
        </div>

        {{-- Tambah --}}
        <button
            type="button"
            onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-600 active:bg-red-700"
        >
            <span class="material-icons text-[20px]">add</span>
            Tambah Produk
        </button>
    </div>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Produk --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                    <span class="material-icons text-blue-600">local_cafe</span>
                </div>
            </div>
        </div>

        {{-- Produk Aktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menu Aktif (POS)</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $activeProducts ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                    <span class="material-icons text-green-600">check_circle</span>
                </div>
            </div>
        </div>

        {{-- Kategori --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Kategori Menu</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $categoryCount ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50">
                    <span class="material-icons text-orange-600">category</span>
                </div>
            </div>
        </div>

        {{-- Nonaktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Menu Nonaktif</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $inactiveProducts ?? 0 }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">
                    <span class="material-icons text-red-600">block</span>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        TABLE
    ========================================================== --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Table Header --}}
        <div class="border-b border-gray-200 p-5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">Daftar Menu & Varian</h3>
                    <p class="mt-1 text-sm text-gray-500">Seluruh produk yang tampil pada aplikasi POS Kasir.</p>
                </div>

                {{-- Search --}}
                <form method="GET" action="{{ route('admin.products.index') }}" class="w-full lg:w-80">
                    <div class="relative">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">search</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama menu atau kategori..."
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >
                    </div>
                </form>
            </div>
        </div>


        {{-- =====================================================
            DESKTOP TABLE
        ====================================================== --}}
        <div class="hidden overflow-x-auto md:block">
            <table class="w-full">
                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Produk</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Varian & Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $index => $product)
                        <tr class="transition hover:bg-gray-50">

                            {{-- No --}}
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $products->firstItem() + $index }}
                            </td>

                            {{-- Produk --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 shrink-0 rounded-xl object-cover border">
                                    @else
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-100 font-semibold text-red-600">
                                            <span class="material-icons text-xl">local_cafe</span>
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-gray-900">{{ $product->name }}</p>
                                        <p class="truncate text-xs text-gray-500">{{ $product->variants->count() }} Varian Ukuran</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Kategori --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 capitalize">
                                    {{ $product->category }}
                                </span>
                            </td>

                            {{-- Varian & Harga --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($product->variants as $variant)
                                        <span class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                                            <span class="font-bold text-gray-900">{{ $variant->cup_size }}:</span> 
                                            Rp {{ number_format($variant->price, 0, ',', '.') }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    {{-- DETAIL --}}
                                    <button
                                        type="button"
                                        onclick="openDetailModal(this)"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->category }}"
                                        data-status="{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}"
                                        data-variants="{{ json_encode($product->variants) }}"
                                        data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                                        title="Detail"
                                    >
                                        <span class="material-icons text-[20px]">visibility</span>
                                    </button>

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        onclick="openEditModal(this)"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->category }}"
                                        data-status="{{ $product->is_active ? '1' : '0' }}"
                                        data-variants="{{ json_encode($product->variants) }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-blue-500 transition hover:bg-blue-50"
                                        title="Edit"
                                    >
                                        <span class="material-icons text-[20px]">edit</span>
                                    </button>

                                    {{-- DELETE --}}
                                    <button
                                        type="button"
                                        onclick="openDeleteModal(this)"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50"
                                        title="Hapus"
                                    >
                                        <span class="material-icons text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
                                        <span class="material-icons text-3xl text-gray-400">local_cafe</span>
                                    </div>
                                    <h3 class="mt-4 font-semibold text-gray-900">Belum ada produk</h3>
                                    <p class="mt-1 text-sm text-gray-500">Tambahkan produk kopi/minuman pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        {{-- =====================================================
            MOBILE TABLE / CARDS
        ====================================================== --}}
        <div class="divide-y divide-gray-100 md:hidden">
            @forelse($products as $product)
                <div class="p-4">
                    <div class="flex gap-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 shrink-0 rounded-xl object-cover border">
                        @else
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 font-semibold text-red-600">
                                <span class="material-icons">local_cafe</span>
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="truncate text-xs text-amber-700 font-medium capitalize">{{ $product->category }}</p>
                                </div>

                                @if($product->is_active)
                                    <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                @else
                                    <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">Nonaktif</span>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach($product->variants as $variant)
                                    <span class="inline-block rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600">
                                        {{ $variant->cup_size }}: Rp {{ number_format($variant->price, 0, ',', '.') }}
                                    </span>
                                @endforeach
                            </div>

                            <div class="mt-4 flex gap-2">
                                <button
                                    type="button"
                                    onclick="openDetailModal(this)"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-category="{{ $product->category }}"
                                    data-status="{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}"
                                    data-variants="{{ json_encode($product->variants) }}"
                                    data-image="{{ $product->image ? asset('storage/' . $product->image) : '' }}"
                                    class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600"
                                >
                                    <span class="material-icons text-[18px]">visibility</span>
                                    Detail
                                </button>

                                <button
                                    type="button"
                                    onclick="openEditModal(this)"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-category="{{ $product->category }}"
                                    data-status="{{ $product->is_active ? '1' : '0' }}"
                                    data-variants="{{ json_encode($product->variants) }}"
                                    class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600"
                                >
                                    <span class="material-icons text-[18px]">edit</span>
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <span class="material-icons text-4xl text-gray-300">local_cafe</span>
                    <p class="mt-3 font-medium text-gray-900">Belum ada produk</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>


{{-- =============================================================
    MODAL BACKDROP
============================================================= --}}
<div id="modalBackdrop" class="fixed inset-0 z-[100] hidden bg-black/50 p-4 backdrop-blur-sm">
    <div id="modalContainer" class="flex min-h-full items-center justify-center">

        {{-- =====================================================
            CREATE MODAL
        ====================================================== --}}
        <div id="createModal" class="modal-box hidden w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Tambah Produk Baru</h3>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan produk dan harga varian ukuran cup.</p>
                </div>
                <button type="button" onclick="closeModal()" class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">

                    {{-- Nama Produk --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nama Produk / Menu</label>
                        <input type="text" name="name" required placeholder="Contoh: Tomoro Aren Latte" class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">
                            <option value="">Pilih Kategori</option>
                            <option value="Coffee">Coffee</option>
                            <option value="Non-Coffee">Non-Coffee</option>
                            <option value="Toast & Food">Toast & Food</option>
                        </select>
                    </div>

                    {{-- Gambar --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Foto Produk (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:rounded-xl file:border-0 file:bg-red-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-red-700 hover:file:bg-red-100">
                    </div>

                    {{-- Varian Ukuran Cup & Harga --}}
                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <label class="mb-3 block text-sm font-bold text-gray-900">Varian Ukuran & Harga (Rp)</label>
                        
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="w-24 text-sm font-medium text-gray-700">Small</span>
                                <input type="number" name="prices[Small]" required placeholder="Harga Small (cth: 18000)" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-red-500">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-24 text-sm font-medium text-gray-700">Medium</span>
                                <input type="number" name="prices[Medium]" placeholder="Harga Medium (cth: 22000)" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Status Produk</label>
                        <select name="is_active" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">
                            <option value="1">Aktif (Tampil di POS)</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                </div>

                <div class="flex gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <button type="button" onclick="closeModal()" class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-red-500 px-4 py-3 text-sm font-semibold text-white hover:bg-red-600">Simpan</button>
                </div>
            </form>
        </div>


        {{-- =====================================================
            DETAIL MODAL
        ====================================================== --}}
        <div id="detailModal" class="modal-box hidden w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Detail Produk</h3>
                    <p class="mt-1 text-sm text-gray-500">Rincian menu dan varian harga.</p>
                </div>
                <button type="button" onclick="closeModal()" class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <div class="p-6">
                <div class="flex flex-col items-center text-center">
                    <div id="detailImageContainer" class="mb-3 flex h-24 w-24 items-center justify-center rounded-2xl bg-red-100 text-red-600">
                        <span class="material-icons text-4xl">local_cafe</span>
                    </div>
                    <h3 id="detailName" class="text-xl font-bold text-gray-900">-</h3>
                    <p id="detailCategory" class="mt-1 text-sm font-semibold text-amber-600 capitalize">-</p>
                </div>

                <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200">
                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4">
                        <span class="text-sm text-gray-500">Status Menu</span>
                        <span id="detailStatus" class="text-sm font-semibold">-</span>
                    </div>

                    <div class="p-4 bg-gray-50">
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Daftar Varian & Harga</p>
                        <div id="detailVariantsList" class="space-y-2">
                            <!-- Varian di-inject via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                <button type="button" onclick="closeModal()" class="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800">Tutup</button>
            </div>
        </div>


        {{-- =====================================================
            EDIT MODAL
        ====================================================== --}}
        <div id="editModal" class="modal-box hidden w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Edit Produk</h3>
                    <p class="mt-1 text-sm text-gray-500">Perbarui informasi produk dan harga.</p>
                </div>
                <button type="button" onclick="closeModal()" class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nama Produk</label>
                        <input id="editName" type="text" name="name" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Kategori</label>
                        <select id="editCategory" name="category" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500">
                            <option value="Coffee">Coffee</option>
                            <option value="Non-Coffee">Non-Coffee</option>
                            <option value="Toast & Food">Toast & Food</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Ganti Foto (Opsional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700">
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                        <label class="mb-3 block text-sm font-bold text-gray-900">Varian Ukuran & Harga (Rp)</label>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="w-24 text-sm font-medium text-gray-700">Small</span>
                                <input id="editPriceRegular" type="number" name="prices[Small]" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-red-500">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-24 text-sm font-medium text-gray-700">Medium</span>
                                <input id="editPriceLarge" type="number" name="prices[Medium]" class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-red-500">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Status</label>
                        <select id="editStatus" name="is_active" required class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <button type="button" onclick="closeModal()" class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>


        {{-- =====================================================
            DELETE MODAL
        ====================================================== --}}
        <div id="deleteModal" class="modal-box hidden w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <span class="material-icons text-3xl text-red-600">delete</span>
                </div>

                <h3 class="mt-5 text-lg font-bold text-gray-900">Hapus Produk?</h3>
                <p class="mt-2 text-sm leading-6 text-gray-500">
                    Apakah Anda yakin ingin menghapus produk
                    <strong id="deleteProductName" class="font-semibold text-gray-900">-</strong>?
                    <br>Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <button type="button" onclick="closeModal()" class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700">Ya, Hapus</button>
                </div>
            </form>
        </div>

    </div>
</div>


{{-- =============================================================
    JAVASCRIPT MODAL LOGIC
============================================================= --}}
<script>
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalBoxes = document.querySelectorAll('.modal-box');

    function openModal(modalId) {
        modalBackdrop.classList.remove('hidden');
        modalBoxes.forEach(m => m.classList.add('hidden'));
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modalBackdrop.classList.add('hidden');
        modalBoxes.forEach(m => m.classList.add('hidden'));
        document.body.classList.remove('overflow-hidden');
    }

    modalBackdrop.addEventListener('click', (e) => { if (e.target === modalBackdrop) closeModal(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

    function openCreateModal() {
        openModal('createModal');
    }

    function openDetailModal(button) {
        const name = button.dataset.name;
        const category = button.dataset.category;
        const status = button.dataset.status;
        const image = button.dataset.image;
        const variants = JSON.parse(button.dataset.variants || '[]');

        document.getElementById('detailName').textContent = name;
        document.getElementById('detailCategory').textContent = category;
        
        const imgContainer = document.getElementById('detailImageContainer');
        if (image) {
            imgContainer.innerHTML = `<img src="${image}" class="h-full w-full rounded-2xl object-cover">`;
        } else {
            imgContainer.innerHTML = `<span class="material-icons text-4xl">local_cafe</span>`;
        }

        const statusElement = document.getElementById('detailStatus');
        statusElement.textContent = status;
        statusElement.className = status === 'Aktif' ? 'text-sm font-semibold text-green-600' : 'text-sm font-semibold text-red-600';

        const variantsList = document.getElementById('detailVariantsList');
        variantsList.innerHTML = '';
        variants.forEach(v => {
            variantsList.innerHTML += `
                <div class="flex justify-between items-center text-sm border-b border-gray-200 pb-1">
                    <span class="font-semibold text-gray-700">${v.cup_size}</span>
                    <span class="font-bold text-gray-900">Rp ${Number(v.price).toLocaleString('id-ID')}</span>
                </div>
            `;
        });

        openModal('detailModal');
    }

    function openEditModal(button) {
        const id = button.dataset.id;
        const name = button.dataset.name;
        const category = button.dataset.category;
        const status = button.dataset.status;
        const variants = JSON.parse(button.dataset.variants || '[]');

        document.getElementById('editName').value = name;
        document.getElementById('editCategory').value = category;
        document.getElementById('editStatus').value = status;

        // Populate harga varian
        document.getElementById('editPriceRegular').value = '';
        document.getElementById('editPriceLarge').value = '';

        variants.forEach(v => {
            if (v.cup_size === 'Small') document.getElementById('editPriceRegular').value = parseInt(v.price);
            if (v.cup_size === 'Medium') document.getElementById('editPriceLarge').value = parseInt(v.price);
        });

        document.getElementById('editForm').action = `/admin/products/${id}`;
        openModal('editModal');
    }

    function openDeleteModal(button) {
        const id = button.dataset.id;
        const name = button.dataset.name;

        document.getElementById('deleteProductName').textContent = name;
        document.getElementById('deleteForm').action = `/admin/products/${id}`;
        openModal('deleteModal');
    }
</script>

@endsection