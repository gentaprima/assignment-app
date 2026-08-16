@extends('components.dashboard-layout')

@section('title', 'Data Cabang')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
        FLASH MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-700">

            <span class="material-icons">
                check_circle
            </span>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    @if(session('error'))

        <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

            <span class="material-icons">
                error
            </span>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif


    {{-- Validation Error --}}
    @if($errors->any())

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5">

            <div class="flex items-start gap-3">

                <span class="material-icons text-red-500">
                    error
                </span>

                <div>

                    <p class="font-semibold text-red-700">
                        Terdapat kesalahan
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-red-600">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

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

            <h2 class="text-2xl font-bold text-gray-900">
                Data Cabang
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Kelola cabang Tomoro Coffee dan lokasi absensinya.
            </p>

        </div>


        <button
            type="button"
            onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-600 active:bg-red-700"
        >

            <span class="material-icons text-[20px]">
                add
            </span>

            Tambah Cabang

        </button>

    </div>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">


        {{-- Total --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Cabang
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalBranches }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">

                    <span class="material-icons text-blue-600">
                        store
                    </span>

                </div>

            </div>

        </div>


        {{-- Aktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Cabang Aktif
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $activeBranches }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                    <span class="material-icons text-green-600">
                        check_circle
                    </span>

                </div>

            </div>

        </div>


        {{-- Nonaktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Cabang Nonaktif
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $inactiveBranches }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50">

                    <span class="material-icons text-orange-600">
                        block
                    </span>

                </div>

            </div>

        </div>


        {{-- Karyawan --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Karyawan
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalEmployees }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">

                    <span class="material-icons text-red-600">
                        groups
                    </span>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TABLE CONTAINER
    ========================================================== --}}

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">


        {{-- HEADER --}}
        <div class="border-b border-gray-200 p-5">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h3 class="font-semibold text-gray-900">
                        Daftar Cabang
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Daftar seluruh cabang yang terdaftar.
                    </p>

                </div>


                {{-- Search --}}
                <form
                    method="GET"
                    action="{{ route('admin.branches') }}"
                    class="w-full lg:w-80"
                >

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            search
                        </span>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari cabang..."
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

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            No
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Cabang
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Lokasi
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Radius
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Karyawan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($branches as $index => $branch)

                        <tr class="transition hover:bg-gray-50">


                            {{-- No --}}
                            <td class="px-6 py-4 text-sm text-gray-500">

                                {{ $branches->firstItem() + $index }}

                            </td>


                            {{-- Nama --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50">

                                        <span class="material-icons text-red-500">
                                            store
                                        </span>

                                    </div>


                                    <div class="min-w-0">

                                        <p class="truncate font-semibold text-gray-900">
                                            {{ $branch->name }}
                                        </p>

                                        <p class="truncate text-xs text-gray-400">
                                            ID #{{ $branch->id }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Lokasi --}}
                            <td class="max-w-xs px-6 py-4">

                                <div class="flex items-start gap-2">

                                    <span class="material-icons mt-0.5 text-[18px] text-gray-400">
                                        location_on
                                    </span>

                                    <div class="min-w-0">

                                        <p class="truncate text-sm text-gray-700">
                                            {{ $branch->address ?? 'Alamat belum diisi' }}
                                        </p>

                                        <p class="mt-1 text-xs text-gray-400">

                                            {{ $branch->latitude }},
                                            {{ $branch->longitude }}

                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Radius --}}
                            <td class="px-6 py-4">

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">

                                    <span class="material-icons text-[16px]">
                                        radio_button_checked
                                    </span>

                                    {{ $branch->radius }} m

                                </span>

                            </td>


                            {{-- Karyawan --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-2">

                                    <span class="material-icons text-[18px] text-gray-400">
                                        groups
                                    </span>

                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $branch->users_count }}
                                    </span>

                                </div>

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if($branch->is_active)

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

                                        data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}"
                                        data-address="{{ $branch->address }}"
                                        data-latitude="{{ $branch->latitude }}"
                                        data-longitude="{{ $branch->longitude }}"
                                        data-radius="{{ $branch->radius }}"
                                        data-employees="{{ $branch->users_count }}"
                                        data-status="{{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}"
                                        data-created="{{ $branch->created_at?->format('d M Y') }}"

                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                                        title="Detail"
                                    >

                                        <span class="material-icons text-[20px]">
                                            visibility
                                        </span>

                                    </button>


                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        onclick="openEditModal(this)"

                                        data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}"
                                        data-address="{{ $branch->address }}"
                                        data-latitude="{{ $branch->latitude }}"
                                        data-longitude="{{ $branch->longitude }}"
                                        data-radius="{{ $branch->radius }}"
                                        data-status="{{ $branch->is_active ? '1' : '0' }}"

                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-blue-500 transition hover:bg-blue-50"
                                        title="Edit"
                                    >

                                        <span class="material-icons text-[20px]">
                                            edit
                                        </span>

                                    </button>


                                    {{-- DELETE --}}
                                    <button
                                        type="button"
                                        onclick="openDeleteModal(this)"

                                        data-id="{{ $branch->id }}"
                                        data-name="{{ $branch->name }}"
                                        data-employees="{{ $branch->users_count }}"

                                        class="flex h-9 w-9 items-center justify-center rounded-lg text-red-500 transition hover:bg-red-50"
                                        title="Hapus"
                                    >

                                        <span class="material-icons text-[20px]">
                                            delete
                                        </span>

                                    </button>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                                        <span class="material-icons text-3xl text-gray-400">
                                            store
                                        </span>

                                    </div>

                                    <h3 class="mt-4 font-semibold text-gray-900">
                                        Belum ada cabang
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Tambahkan cabang pertama Anda.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            MOBILE CARD
        ====================================================== --}}

        <div class="divide-y divide-gray-100 md:hidden">

            @forelse($branches as $branch)

                <div class="p-4">

                    <div class="flex gap-3">


                        {{-- Icon --}}
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50">

                            <span class="material-icons text-red-500">
                                store
                            </span>

                        </div>


                        <div class="min-w-0 flex-1">


                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-900">
                                        {{ $branch->name }}
                                    </p>

                                    <p class="mt-1 truncate text-sm text-gray-500">
                                        {{ $branch->address ?? 'Alamat belum diisi' }}
                                    </p>

                                </div>


                                @if($branch->is_active)

                                    <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Nonaktif
                                    </span>

                                @endif

                            </div>


                            {{-- Info --}}
                            <div class="mt-4 grid grid-cols-2 gap-3">


                                <div class="rounded-xl bg-gray-50 p-3">

                                    <p class="text-xs text-gray-400">
                                        Radius
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-700">
                                        {{ $branch->radius }} meter
                                    </p>

                                </div>


                                <div class="rounded-xl bg-gray-50 p-3">

                                    <p class="text-xs text-gray-400">
                                        Karyawan
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-700">
                                        {{ $branch->users_count }} orang
                                    </p>

                                </div>

                            </div>


                            {{-- Coordinates --}}
                            <div class="mt-3 rounded-xl bg-gray-50 p-3">

                                <div class="flex items-center gap-2">

                                    <span class="material-icons text-[17px] text-gray-400">
                                        location_on
                                    </span>

                                    <p class="text-xs text-gray-500">

                                        {{ $branch->latitude }},
                                        {{ $branch->longitude }}

                                    </p>

                                </div>

                            </div>


                            {{-- Actions --}}
                            <div class="mt-4 flex gap-2">


                                <button
                                    type="button"
                                    onclick="openDetailModal(this)"

                                    data-id="{{ $branch->id }}"
                                    data-name="{{ $branch->name }}"
                                    data-address="{{ $branch->address }}"
                                    data-latitude="{{ $branch->latitude }}"
                                    data-longitude="{{ $branch->longitude }}"
                                    data-radius="{{ $branch->radius }}"
                                    data-employees="{{ $branch->users_count }}"
                                    data-status="{{ $branch->is_active ? 'Aktif' : 'Nonaktif' }}"
                                    data-created="{{ $branch->created_at?->format('d M Y') }}"

                                    class="flex flex-1 items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-600"
                                >

                                    <span class="material-icons text-[18px]">
                                        visibility
                                    </span>

                                    Detail

                                </button>


                                <button
                                    type="button"
                                    onclick="openEditModal(this)"

                                    data-id="{{ $branch->id }}"
                                    data-name="{{ $branch->name }}"
                                    data-address="{{ $branch->address }}"
                                    data-latitude="{{ $branch->latitude }}"
                                    data-longitude="{{ $branch->longitude }}"
                                    data-radius="{{ $branch->radius }}"
                                    data-status="{{ $branch->is_active ? '1' : '0' }}"

                                    class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600"
                                >

                                    <span class="material-icons text-[18px]">
                                        edit
                                    </span>

                                    Edit

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-10 text-center">

                    <span class="material-icons text-4xl text-gray-300">
                        store
                    </span>

                    <p class="mt-3 font-medium text-gray-900">
                        Belum ada cabang
                    </p>

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}
        @if($branches->hasPages())

            <div class="border-t border-gray-200 px-5 py-4">

                {{ $branches->withQueryString()->links() }}

            </div>

        @endif

    </div>

</div>


{{-- =============================================================
    MODAL BACKDROP
============================================================= --}}

<div
    id="modalBackdrop"
    class="fixed inset-0 z-[100] hidden bg-black/50 p-4 backdrop-blur-sm"
>

    <div
        id="modalContainer"
        class="flex min-h-full items-center justify-center"
    >


        {{-- =====================================================
            CREATE MODAL
        ====================================================== --}}

        <div
            id="createModal"
            class="modal-box hidden w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl"
        >

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Tambah Cabang
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan cabang baru ke sistem.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600"
                >

                    <span class="material-icons">
                        close
                    </span>

                </button>

            </div>


            <form
                method="POST"
                action="{{ route('admin.branches.store') }}"
            >

                @csrf


                <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">

                    {{-- Kode --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Kode Cabang
                        </label>

                        <input
                            type="text"
                            name="code"
                            required
                            placeholder="Contoh: TMR-001"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>

                    {{-- Nama --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Cabang
                        </label>

                        <input
                            type="text"
                            name="name"
                            required
                            placeholder="Contoh: Tomoro Coffee Cibubur"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Alamat --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Alamat
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            placeholder="Alamat lengkap cabang..."
                            class="w-full resize-none rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        ></textarea>

                    </div>


                    {{-- Coordinates --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                        {{-- Latitude --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Latitude
                            </label>

                            <input
                                id="createLatitude"
                                type="number"
                                name="latitude"
                                required
                                step="any"
                                min="-90"
                                max="90"
                                placeholder="-6.1234567"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >

                        </div>


                        {{-- Longitude --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Longitude
                            </label>

                            <input
                                id="createLongitude"
                                type="number"
                                name="longitude"
                                required
                                step="any"
                                min="-180"
                                max="180"
                                placeholder="106.1234567"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >

                        </div>

                    </div>


                    {{-- Radius --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Radius Absensi
                        </label>

                        <div class="relative">

                            <input
                                type="number"
                                name="radius"
                                required
                                min="10"
                                max="1000"
                                value="100"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-16 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                                meter
                            </span>

                        </div>

                        <p class="mt-1 text-xs text-gray-400">
                            Jarak maksimum karyawan dari titik cabang untuk melakukan absensi.
                        </p>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select
                            name="is_active"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>


                <div class="flex gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                    <button
                        type="button"
                        onclick="closeModal()"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-red-500 px-4 py-3 text-sm font-semibold text-white hover:bg-red-600"
                    >
                        Simpan
                    </button>

                </div>

            </form>

        </div>


        {{-- =====================================================
            DETAIL MODAL
        ====================================================== --}}

        <div
            id="detailModal"
            class="modal-box hidden w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl"
        >

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Detail Cabang
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Informasi cabang dan lokasi absensi.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100"
                >

                    <span class="material-icons">
                        close
                    </span>

                </button>

            </div>


            <div class="max-h-[70vh] overflow-y-auto p-6">


                {{-- Branch Header --}}
                <div class="flex flex-col items-center text-center">

                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-red-50">

                        <span class="material-icons text-4xl text-red-500">
                            store
                        </span>

                    </div>


                    <h3
                        id="detailName"
                        class="mt-4 text-xl font-bold text-gray-900"
                    >
                        -
                    </h3>


                    <span
                        id="detailStatus"
                        class="mt-2 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700"
                    >
                        -
                    </span>

                </div>


                {{-- Information --}}
                <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200">


                    {{-- Address --}}
                    <div class="border-b border-gray-100 px-4 py-4">

                        <div class="flex items-start gap-3">

                            <span class="material-icons text-gray-400">
                                location_on
                            </span>

                            <div>

                                <p class="text-xs text-gray-400">
                                    Alamat
                                </p>

                                <p
                                    id="detailAddress"
                                    class="mt-1 text-sm font-medium leading-6 text-gray-900"
                                >
                                    -
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Coordinates --}}
                    <div class="border-b border-gray-100 px-4 py-4">

                        <div class="flex items-start gap-3">

                            <span class="material-icons text-gray-400">
                                gps_fixed
                            </span>

                            <div>

                                <p class="text-xs text-gray-400">
                                    Koordinat
                                </p>

                                <p
                                    id="detailCoordinates"
                                    class="mt-1 break-all text-sm font-medium text-gray-900"
                                >
                                    -
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Radius --}}
                    <div class="border-b border-gray-100 px-4 py-4">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <span class="material-icons text-gray-400">
                                    radio_button_checked
                                </span>

                                <span class="text-sm text-gray-500">
                                    Radius Absensi
                                </span>

                            </div>

                            <span
                                id="detailRadius"
                                class="text-sm font-semibold text-gray-900"
                            >
                                -
                            </span>

                        </div>

                    </div>


                    {{-- Employees --}}
                    <div class="border-b border-gray-100 px-4 py-4">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <span class="material-icons text-gray-400">
                                    groups
                                </span>

                                <span class="text-sm text-gray-500">
                                    Karyawan
                                </span>

                            </div>

                            <span
                                id="detailEmployees"
                                class="text-sm font-semibold text-gray-900"
                            >
                                -
                            </span>

                        </div>

                    </div>


                    {{-- Created --}}
                    <div class="px-4 py-4">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <span class="material-icons text-gray-400">
                                    calendar_today
                                </span>

                                <span class="text-sm text-gray-500">
                                    Dibuat
                                </span>

                            </div>

                            <span
                                id="detailCreated"
                                class="text-sm font-medium text-gray-900"
                            >
                                -
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">

                <button
                    type="button"
                    onclick="closeModal()"
                    class="w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Tutup
                </button>

            </div>

        </div>


        {{-- =====================================================
            EDIT MODAL
        ====================================================== --}}

        <div
            id="editModal"
            class="modal-box hidden w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl"
        >

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Edit Cabang
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Perbarui informasi cabang.
                    </p>

                </div>


                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100"
                >

                    <span class="material-icons">
                        close
                    </span>

                </button>

            </div>


            <form
                id="editForm"
                method="POST"
            >

                @csrf

                @method('PUT')


                <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">


                    {{-- Nama --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Cabang
                        </label>

                        <input
                            id="editName"
                            type="text"
                            name="name"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Address --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Alamat
                        </label>

                        <textarea
                            id="editAddress"
                            name="address"
                            rows="3"
                            class="w-full resize-none rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        ></textarea>

                    </div>


                    {{-- Coordinates --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Latitude
                            </label>

                            <input
                                id="editLatitude"
                                type="number"
                                name="latitude"
                                required
                                step="any"
                                min="-90"
                                max="90"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >

                        </div>


                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Longitude
                            </label>

                            <input
                                id="editLongitude"
                                type="number"
                                name="longitude"
                                required
                                step="any"
                                min="-180"
                                max="180"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >

                        </div>

                    </div>


                    {{-- Radius --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Radius Absensi
                        </label>

                        <div class="relative">

                            <input
                                id="editRadius"
                                type="number"
                                name="radius"
                                required
                                min="10"
                                max="1000"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 pr-16 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                                meter
                            </span>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select
                            id="editStatus"
                            name="is_active"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>


                <div class="flex gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                    <button
                        type="button"
                        onclick="closeModal()"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        class="flex-1 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"
                    >
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>


        {{-- =====================================================
            DELETE MODAL
        ====================================================== --}}

        <div
            id="deleteModal"
            class="modal-box hidden w-full max-w-md overflow-hidden rounded-3xl bg-white shadow-2xl"
        >

            <div class="p-6 text-center">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100">

                    <span class="material-icons text-3xl text-red-600">
                        delete
                    </span>

                </div>


                <h3 class="mt-5 text-lg font-bold text-gray-900">
                    Hapus Cabang?
                </h3>


                <p class="mt-2 text-sm leading-6 text-gray-500">

                    Apakah Anda yakin ingin menghapus cabang

                    <strong
                        id="deleteBranchName"
                        class="font-semibold text-gray-900"
                    >
                        -
                    </strong>

                    ?

                </p>


                <div
                    id="deleteEmployeeWarning"
                    class="mt-4 hidden rounded-xl bg-orange-50 px-4 py-3 text-left text-xs leading-5 text-orange-700"
                >

                    <div class="flex gap-2">

                        <span class="material-icons text-[18px]">
                            warning
                        </span>

                        <span>
                            Cabang ini masih memiliki karyawan. Cabang tidak dapat dihapus sampai seluruh karyawan dipindahkan atau dihapus.
                        </span>

                    </div>

                </div>

            </div>


            <form
                id="deleteForm"
                method="POST"
            >

                @csrf

                @method('DELETE')


                <div class="flex gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                    <button
                        type="button"
                        onclick="closeModal()"
                        class="flex-1 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Batal
                    </button>


                    <button
                        id="deleteSubmitButton"
                        type="submit"
                        class="flex-1 rounded-xl bg-red-600 px-4 py-3 text-sm font-semibold text-white hover:bg-red-700"
                    >
                        Ya, Hapus
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
    JAVASCRIPT
============================================================= --}}

<script>

    const modalBackdrop = document.getElementById('modalBackdrop');

    const modalBoxes = document.querySelectorAll('.modal-box');


    /*
    |--------------------------------------------------------------------------
    | Open Modal
    |--------------------------------------------------------------------------
    */

    function openModal(modalId)
    {
        modalBackdrop.classList.remove('hidden');

        modalBoxes.forEach(function(modal) {

            modal.classList.add('hidden');

        });

        document
            .getElementById(modalId)
            .classList.remove('hidden');

        document.body.classList.add('overflow-hidden');
    }


    /*
    |--------------------------------------------------------------------------
    | Close Modal
    |--------------------------------------------------------------------------
    */

    function closeModal()
    {
        modalBackdrop.classList.add('hidden');

        modalBoxes.forEach(function(modal) {

            modal.classList.add('hidden');

        });

        document.body.classList.remove('overflow-hidden');
    }


    /*
    |--------------------------------------------------------------------------
    | Click Backdrop
    |--------------------------------------------------------------------------
    */

    modalBackdrop.addEventListener('click', function(event) {

        if (event.target === modalBackdrop) {

            closeModal();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Escape
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function(event) {

        if (event.key === 'Escape') {

            closeModal();

        }

    });


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    function openCreateModal()
    {
        openModal('createModal');
    }


    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    function openDetailModal(button)
    {
        const name = button.dataset.name;
        const address = button.dataset.address;
        const latitude = button.dataset.latitude;
        const longitude = button.dataset.longitude;
        const radius = button.dataset.radius;
        const employees = button.dataset.employees;
        const status = button.dataset.status;
        const created = button.dataset.created;


        document.getElementById('detailName').textContent =
            name;

        document.getElementById('detailAddress').textContent =
            address || 'Alamat belum diisi';

        document.getElementById('detailCoordinates').textContent =
            `${latitude}, ${longitude}`;

        document.getElementById('detailRadius').textContent =
            `${radius} meter`;

        document.getElementById('detailEmployees').textContent =
            `${employees} orang`;

        document.getElementById('detailCreated').textContent =
            created;


        const statusElement =
            document.getElementById('detailStatus');


        statusElement.textContent =
            status;


        if (status === 'Aktif') {

            statusElement.className =
                'mt-2 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700';

        } else {

            statusElement.className =
                'mt-2 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700';

        }


        openModal('detailModal');
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    function openEditModal(button)
    {
        const id = button.dataset.id;


        document.getElementById('editName').value =
            button.dataset.name || '';


        document.getElementById('editAddress').value =
            button.dataset.address || '';


        document.getElementById('editLatitude').value =
            button.dataset.latitude || '';


        document.getElementById('editLongitude').value =
            button.dataset.longitude || '';


        document.getElementById('editRadius').value =
            button.dataset.radius || '100';


        document.getElementById('editStatus').value =
            button.dataset.status;


        document.getElementById('editForm').action =
            `/admin/branches/${id}`;


        openModal('editModal');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    function openDeleteModal(button)
    {
        const id = button.dataset.id;

        const name = button.dataset.name;

        const employees =
            parseInt(button.dataset.employees || '0');


        document.getElementById('deleteBranchName').textContent =
            name;


        const warning =
            document.getElementById('deleteEmployeeWarning');

        const submitButton =
            document.getElementById('deleteSubmitButton');


        if (employees > 0) {

            warning.classList.remove('hidden');

            submitButton.disabled = true;

            submitButton.classList.add(
                'cursor-not-allowed',
                'opacity-50'
            );

        } else {

            warning.classList.add('hidden');

            submitButton.disabled = false;

            submitButton.classList.remove(
                'cursor-not-allowed',
                'opacity-50'
            );

        }


        document.getElementById('deleteForm').action =
            `/admin/branches/${id}`;


        openModal('deleteModal');
    }

</script>

@endsection