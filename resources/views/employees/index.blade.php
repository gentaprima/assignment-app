@extends('components.dashboard-layout')

@section('title', 'Data Karyawan')

@section('content')

<div
    id="employeePage"
    class="space-y-6"
>


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
                Data Karyawan
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Kelola data karyawan Tomoro Coffee.
            </p>

        </div>


        {{-- Tambah --}}
        <button
            type="button"
            onclick="openCreateModal()"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-600 active:bg-red-700"
        >

            <span class="material-icons text-[20px]">
                add
            </span>

            Tambah Karyawan

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
                        Total Karyawan
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $totalEmployees }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">

                    <span class="material-icons text-blue-600">
                        groups
                    </span>

                </div>

            </div>

        </div>


        {{-- Aktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Karyawan Aktif
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $activeEmployees }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                    <span class="material-icons text-green-600">
                        person
                    </span>

                </div>

            </div>

        </div>


        {{-- PIC --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        PIC
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $picCount }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50">

                    <span class="material-icons text-orange-600">
                        supervisor_account
                    </span>

                </div>

            </div>

        </div>


        {{-- Cabang --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Cabang
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900">
                        {{ $branchCount }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">

                    <span class="material-icons text-red-600">
                        store
                    </span>

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

                    <h3 class="font-semibold text-gray-900">
                        Daftar Karyawan
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Seluruh karyawan yang terdaftar dalam sistem.
                    </p>

                </div>


                {{-- Search --}}
                <form
                    method="GET"
                    action="{{ route('admin.employees') }}"
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
                            placeholder="Cari nama atau email..."
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
                            Karyawan
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Role
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Cabang
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

                    @forelse($employees as $index => $employee)

                        @php

                            $roleClass = match($employee->role) {

                                'admin' =>
                                    'bg-purple-50 text-purple-700',

                                'manager' =>
                                    'bg-blue-50 text-blue-700',

                                'pic' =>
                                    'bg-orange-50 text-orange-700',

                                default =>
                                    'bg-gray-100 text-gray-700',

                            };

                        @endphp


                        <tr class="transition hover:bg-gray-50">


                            {{-- No --}}
                            <td class="px-6 py-4 text-sm text-gray-500">

                                {{ $employees->firstItem() + $index }}

                            </td>


                            {{-- Karyawan --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 font-semibold text-red-600">

                                        {{ strtoupper(substr($employee->name, 0, 1)) }}

                                    </div>


                                    <div class="min-w-0">

                                        <p class="truncate font-semibold text-gray-900">
                                            {{ $employee->name }}
                                        </p>

                                        <p class="truncate text-sm text-gray-500">
                                            {{ $employee->email }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- Role --}}
                            <td class="px-6 py-4">

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold capitalize {{ $roleClass }}">

                                    {{ $employee->role }}

                                </span>

                            </td>


                            {{-- Cabang --}}
                            <td class="px-6 py-4 text-sm text-gray-600">

                                {{ $employee->branch?->name ?? '-' }}

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @if($employee->is_active)

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
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ $employee->name }}"
                                        data-email="{{ $employee->email }}"
                                        data-role="{{ $employee->role }}"
                                        data-branch="{{ $employee->branch?->name ?? '-' }}"
                                        data-status="{{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}"
                                        data-created="{{ $employee->created_at?->format('d M Y') }}"
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
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ $employee->name }}"
                                        data-email="{{ $employee->email }}"
                                        data-role="{{ $employee->role }}"
                                        data-branch="{{ $employee->branch_id }}"
                                        data-status="{{ $employee->is_active ? '1' : '0' }}"
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
                                        data-id="{{ $employee->id }}"
                                        data-name="{{ $employee->name }}"
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

                            <td colspan="6" class="px-6 py-16 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                                        <span class="material-icons text-3xl text-gray-400">
                                            groups
                                        </span>

                                    </div>

                                    <h3 class="mt-4 font-semibold text-gray-900">
                                        Belum ada karyawan
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Tambahkan karyawan pertama Anda.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            MOBILE
        ====================================================== --}}

        <div class="divide-y divide-gray-100 md:hidden">

            @forelse($employees as $employee)

                <div class="p-4">

                    <div class="flex gap-3">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100 font-semibold text-red-600">

                            {{ strtoupper(substr($employee->name, 0, 1)) }}

                        </div>


                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-900">
                                        {{ $employee->name }}
                                    </p>

                                    <p class="truncate text-sm text-gray-500">
                                        {{ $employee->email }}
                                    </p>

                                </div>


                                @if($employee->is_active)

                                    <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Nonaktif
                                    </span>

                                @endif

                            </div>


                            <div class="mt-3 grid grid-cols-2 gap-3">

                                <div>

                                    <p class="text-xs text-gray-400">
                                        Role
                                    </p>

                                    <p class="mt-1 text-sm font-medium capitalize text-gray-700">
                                        {{ $employee->role }}
                                    </p>

                                </div>


                                <div>

                                    <p class="text-xs text-gray-400">
                                        Cabang
                                    </p>

                                    <p class="mt-1 truncate text-sm font-medium text-gray-700">
                                        {{ $employee->branch?->name ?? '-' }}
                                    </p>

                                </div>

                            </div>


                            <div class="mt-4 flex gap-2">

                                <button
                                    type="button"
                                    onclick="openDetailModal(this)"
                                    data-id="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}"
                                    data-email="{{ $employee->email }}"
                                    data-role="{{ $employee->role }}"
                                    data-branch="{{ $employee->branch?->name ?? '-' }}"
                                    data-status="{{ $employee->is_active ? 'Aktif' : 'Nonaktif' }}"
                                    data-created="{{ $employee->created_at?->format('d M Y') }}"
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
                                    data-id="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}"
                                    data-email="{{ $employee->email }}"
                                    data-role="{{ $employee->role }}"
                                    data-branch="{{ $employee->branch_id }}"
                                    data-status="{{ $employee->is_active ? '1' : '0' }}"
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
                        groups
                    </span>

                    <p class="mt-3 font-medium text-gray-900">
                        Belum ada karyawan
                    </p>

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}
        @if($employees->hasPages())

            <div class="border-t border-gray-200 px-5 py-4">

                {{ $employees->withQueryString()->links() }}

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
                        Tambah Karyawan
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan akun karyawan baru.
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
                action="{{ route('admin.employees.store') }}"
            >

                @csrf

                <div class="max-h-[70vh] space-y-5 overflow-y-auto p-6">


                    {{-- Nama --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="name"
                            required
                            placeholder="Contoh: Genta Prima"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Email --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="nama@tomorocoffee.com"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Password --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            minlength="8"
                            placeholder="Minimal 8 karakter"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Role --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Role
                        </label>

                        <select
                            name="role"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                            <option value="">
                                Pilih Role
                            </option>

                            <option value="employee">
                                Employee
                            </option>

                            <option value="pic">
                                PIC
                            </option>

                            <option value="manager">
                                Manager
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                        </select>

                    </div>


                    {{-- Cabang --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Cabang
                        </label>

                        <select
                            name="branch_id"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                            <option value="">
                                Tidak ada cabang
                            </option>

                            @foreach($branches as $branch)

                                <option value="{{ $branch->id }}">
                                    {{ $branch->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Status --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select
                            name="is_active"
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
                        Detail Karyawan
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Informasi akun karyawan.
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


            <div class="p-6">

                {{-- Profile --}}
                <div class="flex flex-col items-center text-center">

                    <div
                        id="detailAvatar"
                        class="flex h-20 w-20 items-center justify-center rounded-full bg-red-100 text-2xl font-bold text-red-600"
                    >
                        -
                    </div>

                    <h3
                        id="detailName"
                        class="mt-4 text-xl font-bold text-gray-900"
                    >
                        -
                    </h3>

                    <p
                        id="detailRole"
                        class="mt-1 text-sm capitalize text-gray-500"
                    >
                        -
                    </p>

                </div>


                {{-- Information --}}
                <div class="mt-6 overflow-hidden rounded-2xl border border-gray-200">

                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4">

                        <div class="flex items-center gap-3">

                            <span class="material-icons text-gray-400">
                                email
                            </span>

                            <span class="text-sm text-gray-500">
                                Email
                            </span>

                        </div>

                        <span
                            id="detailEmail"
                            class="max-w-[55%] truncate text-right text-sm font-medium text-gray-900"
                        >
                            -
                        </span>

                    </div>


                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4">

                        <div class="flex items-center gap-3">

                            <span class="material-icons text-gray-400">
                                store
                            </span>

                            <span class="text-sm text-gray-500">
                                Cabang
                            </span>

                        </div>

                        <span
                            id="detailBranch"
                            class="text-right text-sm font-medium text-gray-900"
                        >
                            -
                        </span>

                    </div>


                    <div class="flex items-center justify-between border-b border-gray-100 px-4 py-4">

                        <div class="flex items-center gap-3">

                            <span class="material-icons text-gray-400">
                                verified_user
                            </span>

                            <span class="text-sm text-gray-500">
                                Status
                            </span>

                        </div>

                        <span
                            id="detailStatus"
                            class="text-sm font-semibold"
                        >
                            -
                        </span>

                    </div>


                    <div class="flex items-center justify-between px-4 py-4">

                        <div class="flex items-center gap-3">

                            <span class="material-icons text-gray-400">
                                calendar_today
                            </span>

                            <span class="text-sm text-gray-500">
                                Bergabung
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
                        Edit Karyawan
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Perbarui informasi karyawan.
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
                            Nama Lengkap
                        </label>

                        <input
                            id="editName"
                            type="text"
                            name="name"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Email --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Email
                        </label>

                        <input
                            id="editEmail"
                            type="email"
                            name="email"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                    </div>


                    {{-- Password --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Password Baru
                        </label>

                        <input
                            type="password"
                            name="password"
                            minlength="8"
                            placeholder="Kosongkan jika tidak ingin mengubah"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                        <p class="mt-1 text-xs text-gray-400">
                            Kosongkan jika password tidak ingin diubah.
                        </p>

                    </div>


                    {{-- Role --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Role
                        </label>

                        <select
                            id="editRole"
                            name="role"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                            <option value="employee">
                                Employee
                            </option>

                            <option value="pic">
                                PIC
                            </option>

                            <option value="manager">
                                Manager
                            </option>

                            <option value="admin">
                                Admin
                            </option>

                        </select>

                    </div>


                    {{-- Cabang --}}
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Cabang
                        </label>

                        <select
                            id="editBranch"
                            name="branch_id"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"
                        >

                            <option value="">
                                Tidak ada cabang
                            </option>

                            @foreach($branches as $branch)

                                <option value="{{ $branch->id }}">
                                    {{ $branch->name }}
                                </option>

                            @endforeach

                        </select>

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
                    Hapus Karyawan?
                </h3>


                <p class="mt-2 text-sm leading-6 text-gray-500">

                    Apakah Anda yakin ingin menghapus karyawan

                    <strong
                        id="deleteEmployeeName"
                        class="font-semibold text-gray-900"
                    >
                        -
                    </strong>

                    ?

                    <br>

                    Data yang sudah dihapus tidak dapat dikembalikan.

                </p>

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
    JAVASCRIPT MODAL
============================================================= --}}

<script>

    const modalBackdrop = document.getElementById('modalBackdrop');

    const modalBoxes = document.querySelectorAll('.modal-box');


    /**
     * Buka modal tertentu
     */
    function openModal(modalId)
    {
        modalBackdrop.classList.remove('hidden');

        modalBoxes.forEach(function (modal) {
            modal.classList.add('hidden');
        });

        document
            .getElementById(modalId)
            .classList.remove('hidden');

        document.body.classList.add('overflow-hidden');
    }


    /**
     * Tutup modal
     */
    function closeModal()
    {
        modalBackdrop.classList.add('hidden');

        modalBoxes.forEach(function (modal) {
            modal.classList.add('hidden');
        });

        document.body.classList.remove('overflow-hidden');
    }


    /**
     * Klik backdrop untuk menutup
     */
    modalBackdrop.addEventListener('click', function (event) {

        if (event.target === modalBackdrop) {
            closeModal();
        }

    });


    /**
     * ESC untuk menutup
     */
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeModal();
        }

    });


    /**
     * CREATE
     */
    function openCreateModal()
    {
        openModal('createModal');
    }


    /**
     * DETAIL
     */
    function openDetailModal(button)
    {
        const name = button.dataset.name;
        const email = button.dataset.email;
        const role = button.dataset.role;
        const branch = button.dataset.branch;
        const status = button.dataset.status;
        const created = button.dataset.created;


        document.getElementById('detailAvatar').textContent =
            name.charAt(0).toUpperCase();

        document.getElementById('detailName').textContent =
            name;

        document.getElementById('detailEmail').textContent =
            email;

        document.getElementById('detailRole').textContent =
            role;

        document.getElementById('detailBranch').textContent =
            branch;

        document.getElementById('detailCreated').textContent =
            created;


        const statusElement =
            document.getElementById('detailStatus');


        statusElement.textContent = status;


        if (status === 'Aktif') {

            statusElement.className =
                'text-sm font-semibold text-green-600';

        } else {

            statusElement.className =
                'text-sm font-semibold text-red-600';

        }


        openModal('detailModal');
    }


    /**
     * EDIT
     */
    function openEditModal(button)
    {
        const id = button.dataset.id;
        const name = button.dataset.name;
        const email = button.dataset.email;
        const role = button.dataset.role;
        const branch = button.dataset.branch;
        const status = button.dataset.status;


        document.getElementById('editName').value =
            name;

        document.getElementById('editEmail').value =
            email;

        document.getElementById('editRole').value =
            role;

        document.getElementById('editBranch').value =
            branch || '';

        document.getElementById('editStatus').value =
            status;


        document.getElementById('editForm').action =
            `/admin/employees/${id}`;


        openModal('editModal');
    }


    /**
     * DELETE
     */
    function openDeleteModal(button)
    {
        const id = button.dataset.id;
        const name = button.dataset.name;


        document.getElementById('deleteEmployeeName').textContent =
            name;


        document.getElementById('deleteForm').action =
            `/admin/employees/${id}`;


        openModal('deleteModal');
    }

</script>

@endsection