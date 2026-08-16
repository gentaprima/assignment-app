@extends('components.dashboard-layout')

@section('content')

<div class="space-y-6">

    {{-- WELCOME --}}
    <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 to-red-500 p-6 text-white shadow-sm sm:p-8">

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


    {{-- STATISTICS --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


        {{-- Total Karyawan --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Karyawan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        128
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


        {{-- Cabang --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Total Cabang
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        12
                    </p>

                    <p class="mt-1 text-xs text-green-600">
                        Cabang aktif
                    </p>

                </div>


                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50">

                    <span class="material-icons text-purple-600">
                        store
                    </span>

                </div>

            </div>

        </div>


        {{-- Hadir --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Hadir Hari Ini
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        112
                    </p>

                    <p class="mt-1 text-xs text-green-600">
                        87.5% dari total
                    </p>

                </div>


                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                    <span class="material-icons text-green-600">
                        how_to_reg
                    </span>

                </div>

            </div>

        </div>


        {{-- Menunggu --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-sm text-gray-500">
                        Menunggu Persetujuan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        7
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


    {{-- ABSENSI HARI INI --}}
    <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="flex flex-col gap-4 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h3 class="text-lg font-bold text-gray-900">
                    Absensi Hari Ini
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan kehadiran seluruh cabang
                </p>

            </div>


            <button
                type="button"
                class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Lihat Detail
            </button>

        </div>


        <div class="grid grid-cols-1 divide-y divide-gray-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

            {{-- Hadir --}}
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
                            112
                        </p>

                    </div>

                </div>

            </div>


            {{-- Terlambat --}}
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
                            9
                        </p>

                    </div>

                </div>

            </div>


            {{-- Tidak Hadir --}}
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
                            7
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- TWO COLUMNS --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-2">


        {{-- AKTIVITAS --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 p-5">

                <h3 class="font-bold text-gray-900">
                    Aktivitas Terbaru
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Aktivitas sistem terbaru
                </p>

            </div>


            <div class="divide-y divide-gray-100">

                <div class="flex items-center gap-4 p-5">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-50">

                        <span class="material-icons text-green-600">
                            person_add
                        </span>

                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="font-medium text-gray-900">
                            Karyawan baru terdaftar
                        </p>

                        <p class="text-sm text-gray-500">
                            Hari ini
                        </p>

                    </div>

                </div>


                <div class="flex items-center gap-4 p-5">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50">

                        <span class="material-icons text-blue-600">
                            update
                        </span>

                    </div>

                    <div class="min-w-0 flex-1">

                        <p class="font-medium text-gray-900">
                            Jadwal diperbarui
                        </p>

                        <p class="text-sm text-gray-500">
                            Hari ini
                        </p>

                    </div>

                </div>


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
                            Menunggu persetujuan
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- CABANG --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 p-5">

                <h3 class="font-bold text-gray-900">
                    Status Cabang
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan status cabang
                </p>

            </div>


            <div class="divide-y divide-gray-100">

                <div class="flex items-center justify-between p-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50">

                            <span class="material-icons text-green-600">
                                store
                            </span>

                        </div>

                        <div>

                            <p class="font-medium text-gray-900">
                                Tomoro Cibubur
                            </p>

                            <p class="text-sm text-gray-500">
                                18 karyawan
                            </p>

                        </div>

                    </div>


                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        Aktif
                    </span>

                </div>


                <div class="flex items-center justify-between p-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50">

                            <span class="material-icons text-green-600">
                                store
                            </span>

                        </div>

                        <div>

                            <p class="font-medium text-gray-900">
                                Tomoro Depok
                            </p>

                            <p class="text-sm text-gray-500">
                                14 karyawan
                            </p>

                        </div>

                    </div>


                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        Aktif
                    </span>

                </div>


                <div class="flex items-center justify-between p-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100">

                            <span class="material-icons text-gray-500">
                                store
                            </span>

                        </div>

                        <div>

                            <p class="font-medium text-gray-900">
                                Tomoro Bekasi
                            </p>

                            <p class="text-sm text-gray-500">
                                0 karyawan
                            </p>

                        </div>

                    </div>


                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                        Nonaktif
                    </span>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection