@extends('components.dashboard-layout')

@section('content')

    <div class="space-y-6">

        {{-- ===================================== --}}
        {{-- WELCOME --}}
        {{-- ===================================== --}}

        <section
            class="overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 to-red-500 p-6 text-white shadow-sm sm:p-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm text-red-100">
                        Selamat datang kembali 👋
                    </p>

                    <h2 class="mt-1 text-2xl font-bold sm:text-3xl">
                        {{ auth()->user()->name }}
                    </h2>

                    <p class="mt-2 text-sm text-red-100">
                        Berikut ringkasan sistem absensi hari ini.
                    </p>

                </div>


                <div class="rounded-xl bg-white/10 px-5 py-3">

                    <p class="text-xs text-red-100">
                        Hari ini
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ now()->translatedFormat('d F Y') }}
                    </p>

                </div>

            </div>

        </section>


        {{-- ===================================== --}}
        {{-- STATISTICS --}}
        {{-- ===================================== --}}

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


            {{-- TOTAL KARYAWAN --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Karyawan
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ number_format($totalEmployees) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Karyawan terdaftar
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">

                        <span class="material-icons text-blue-600">
                            people
                        </span>

                    </div>

                </div>

            </div>


            {{-- TOTAL CABANG --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Cabang
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ number_format($totalBranches) }}
                        </p>

                        <p class="mt-1 text-xs text-green-600">
                            {{ $activeBranches }} cabang aktif
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50">

                        <span class="material-icons text-purple-600">
                            store
                        </span>

                    </div>

                </div>

            </div>


            {{-- HADIR --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Hadir Hari Ini
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ number_format($totalPresent) }}
                        </p>

                        <p class="mt-1 text-xs text-green-600">

                            @if($totalEmployees > 0)

                                {{ number_format(($totalPresent / $totalEmployees) * 100, 1) }}%

                                dari total

                            @else

                                0% dari total

                            @endif

                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                        <span class="material-icons text-green-600">
                            how_to_reg
                        </span>

                    </div>

                </div>

            </div>


            {{-- MENUNGGU PERSETUJUAN --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Menunggu Persetujuan
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ number_format($pendingCorrections) }}
                        </p>

                        <p class="mt-1 text-xs text-red-600">
                            Perlu ditinjau
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">

                        <span class="material-icons text-red-600">
                            pending_actions
                        </span>

                    </div>

                </div>

            </div>

        </section>


        {{-- ===================================== --}}
        {{-- ABSENSI HARI INI --}}
        {{-- ===================================== --}}

        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex flex-col gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h3 class="text-lg font-bold text-gray-900">
                        Absensi Hari Ini
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Ringkasan kehadiran seluruh karyawan
                    </p>

                </div>

                <a href="{{ route('reports.index') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">

                    <span class="material-icons text-lg">
                        edit_calendar
                    </span>

                    Lihat Report

                </a>

            </div>


            <div class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">


                {{-- HADIR --}}

                <div class="p-6">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-50">

                            <span class="material-icons text-green-600">
                                check_circle
                            </span>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Hadir
                            </p>

                            <p class="text-2xl font-bold text-gray-900">
                                {{ $totalPresent }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- TERLAMBAT --}}

                <div class="p-6">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-50">

                            <span class="material-icons text-orange-600">
                                schedule
                            </span>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Terlambat
                            </p>

                            <p class="text-2xl font-bold text-gray-900">
                                {{ $totalLate }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- TIDAK HADIR --}}

                <div class="p-6">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50">

                            <span class="material-icons text-red-600">
                                cancel
                            </span>

                        </div>

                        <div>

                            <p class="text-sm text-gray-500">
                                Tidak Hadir
                            </p>

                            <p class="text-2xl font-bold text-gray-900">
                                {{ $totalAbsent }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ===================================== --}}
        {{-- TWO COLUMNS --}}
        {{-- ===================================== --}}

        <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">


            {{-- ================================= --}}
            {{-- AKTIVITAS --}}
            {{-- ================================= --}}

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 p-5">

                    <h3 class="font-bold text-gray-900">
                        Aktivitas Terbaru
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Ringkasan aktivitas sistem
                    </p>

                </div>


                <div class="divide-y divide-gray-100">

                    {{-- Karyawan --}}

                    <div class="flex items-center gap-4 p-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-50">

                            <span class="material-icons text-green-600">
                                person_add
                            </span>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="font-medium text-gray-900">
                                Total karyawan terdaftar
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $totalEmployees }} karyawan
                            </p>

                        </div>

                    </div>


                    {{-- Cabang --}}

                    <div class="flex items-center gap-4 p-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50">

                            <span class="material-icons text-blue-600">
                                store
                            </span>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="font-medium text-gray-900">
                                Cabang aktif
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $activeBranches }} cabang aktif
                            </p>

                        </div>

                    </div>


                    {{-- Perbaikan --}}

                    <div class="flex items-center gap-4 p-5">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-orange-50">

                            <span class="material-icons text-orange-600">
                                edit
                            </span>

                        </div>

                        <div class="min-w-0 flex-1">

                            <p class="font-medium text-gray-900">
                                Perbaikan absensi
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $pendingCorrections }} menunggu persetujuan
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================= --}}
            {{-- CABANG --}}
            {{-- ================================= --}}

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="flex items-center justify-between border-b border-gray-200 p-5">

                    <div>

                        <h3 class="font-bold text-gray-900">
                            Status Cabang
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Ringkasan cabang perusahaan
                        </p>

                    </div>

                    <a href="{{ route('admin.branches') }}" class="text-sm font-medium text-red-600 hover:text-red-700">
                        Lihat semua
                    </a>

                </div>


                <div class="divide-y divide-gray-100">

                    @forelse($branches as $branch)

                        <div class="flex items-center justify-between gap-4 p-5">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl
                                                {{ $branch->is_active ? 'bg-green-50' : 'bg-gray-100' }}">

                                    <span class="material-icons
                                                    {{ $branch->is_active ? 'text-green-600' : 'text-gray-500' }}">

                                        store

                                    </span>

                                </div>


                                <div class="min-w-0">

                                    <p class="truncate font-medium text-gray-900">
                                        {{ $branch->name }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        {{ $branch->employee_count }} karyawan
                                    </p>

                                </div>

                            </div>


                            @if($branch->is_active)

                                <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                    Aktif
                                </span>

                            @else

                                <span class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                    Nonaktif
                                </span>

                            @endif

                        </div>

                    @empty

                        <div class="p-8 text-center">

                            <span class="material-icons text-4xl text-gray-300">
                                store
                            </span>

                            <p class="mt-2 text-sm text-gray-500">
                                Belum ada data cabang.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </section>

    </div>

@endsection