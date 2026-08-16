@extends('components.dashboard-layout')

@section('title', 'Dashboard Karyawan')

@section('content')

<div class="space-y-5 sm:space-y-6">

    {{-- =========================================================
        WELCOME
    ========================================================== --}}

    <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 via-red-500 to-orange-400 p-5 text-white shadow-sm sm:rounded-3xl sm:p-6 lg:p-8">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="min-w-0">

                <p class="text-sm font-medium text-red-100">
                    Selamat datang kembali 👋
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">
                    {{ auth()->user()->name }}
                </h1>

                <p class="mt-2 max-w-xl text-sm leading-6 text-red-50 sm:text-base">
                    Pantau jadwal dan status absensi Anda hari ini.
                </p>

                @if(auth()->user()->branch)
                    <div class="mt-4 flex items-center gap-2">

                        <span class="material-icons text-lg">
                            store
                        </span>

                        <span class="text-sm font-semibold">
                            {{ auth()->user()->branch->name }}
                        </span>

                    </div>
                @endif

            </div>


            <div class="w-full shrink-0 rounded-2xl border border-white/20 bg-white/10 px-4 py-3 backdrop-blur-sm sm:w-auto">

                <p class="text-xs font-medium uppercase tracking-wider text-red-100">
                    Hari ini
                </p>

                <p class="mt-1 text-sm font-semibold sm:text-base">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
        STATISTIK MINGGU INI
    ========================================================== --}}

    <section class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">

        {{-- Total Jadwal --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Jadwal
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $totalSchedule }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Minggu ini
                    </p>

                </div>

                <div class="hidden h-11 w-11 items-center justify-center rounded-xl bg-blue-50 sm:flex">

                    <span class="material-icons text-blue-600">
                        calendar_month
                    </span>

                </div>

            </div>

        </div>


        {{-- Hadir --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Hadir
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $totalPresent }}
                    </p>

                    <p class="mt-1 text-xs text-green-600">
                        Sudah absen
                    </p>

                </div>

                <div class="hidden h-11 w-11 items-center justify-center rounded-xl bg-green-50 sm:flex">

                    <span class="material-icons text-green-600">
                        how_to_reg
                    </span>

                </div>

            </div>

        </div>


        {{-- Off --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Off
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $totalOff }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Minggu ini
                    </p>

                </div>

                <div class="hidden h-11 w-11 items-center justify-center rounded-xl bg-gray-100 sm:flex">

                    <span class="material-icons text-gray-500">
                        event_busy
                    </span>

                </div>

            </div>

        </div>


        {{-- Izin --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Izin
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $totalLeave }}
                    </p>

                    <p class="mt-1 text-xs text-orange-600">
                        Minggu ini
                    </p>

                </div>

                <div class="hidden h-11 w-11 items-center justify-center rounded-xl bg-orange-50 sm:flex">

                    <span class="material-icons text-orange-600">
                        pending_actions
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        JADWAL + ABSENSI HARI INI
    ========================================================== --}}

    <section class="grid grid-cols-1 gap-5 lg:grid-cols-2">


        {{-- JADWAL HARI INI --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Jadwal Hari Ini
                    </p>

                    @if($todaySchedule)

                        @if($todaySchedule->status === 'scheduled' && $todaySchedule->shift)

                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">

                                {{ \Carbon\Carbon::parse($todaySchedule->shift->start_time)->format('H:i') }}

                            </h2>

                            <p class="mt-1 text-sm text-gray-500">

                                Sampai
                                {{ \Carbon\Carbon::parse($todaySchedule->shift->end_time)->format('H:i') }}

                            </p>

                        @elseif($todaySchedule->status === 'off')

                            <h2 class="mt-2 text-3xl font-bold text-gray-900">
                                OFF
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Anda libur hari ini
                            </p>

                        @elseif($todaySchedule->status === 'leave')

                            <h2 class="mt-2 text-3xl font-bold text-gray-900">
                                IZIN
                            </h2>

                            <p class="mt-1 text-sm text-gray-500">
                                Jadwal izin
                            </p>

                        @endif

                    @else

                        <h2 class="mt-2 text-3xl font-bold text-gray-900">
                            -
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Belum ada jadwal
                        </p>

                    @endif

                </div>


                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50">

                    <span class="material-icons text-blue-600">
                        schedule
                    </span>

                </div>

            </div>


            @if($todaySchedule)

                <div class="mt-5 rounded-xl bg-gray-50 p-4">

                    <div class="flex items-center justify-between gap-3">

                        <div>

                            <p class="text-xs text-gray-400">
                                Shift
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">

                                {{ $todaySchedule->shift?->name ?? ucfirst($todaySchedule->status) }}

                            </p>

                        </div>


                        @if($todaySchedule->status === 'scheduled')

                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                Terjadwal
                            </span>

                        @elseif($todaySchedule->status === 'off')

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                Off
                            </span>

                        @elseif($todaySchedule->status === 'leave')

                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                Izin
                            </span>

                        @endif

                    </div>

                </div>

            @endif

        </div>


        {{-- STATUS ABSENSI --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Status Absensi
                    </p>


                    @if($todayAttendance)

                        <h2 class="mt-2 text-3xl font-bold text-gray-900 sm:text-4xl">

                            {{ $todayAttendance->check_in_at
                                ? $todayAttendance->check_in_at->format('H:i')
                                : '--:--'
                            }}

                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Jam masuk
                        </p>

                    @else

                        <h2 class="mt-2 text-3xl font-bold text-gray-900 sm:text-4xl">
                            --:--
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Belum absen masuk
                        </p>

                    @endif

                </div>


                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl
                    {{ $todayAttendance ? 'bg-green-50' : 'bg-red-50' }}">

                    <span class="material-icons
                        {{ $todayAttendance ? 'text-green-600' : 'text-red-600' }}">

                        {{ $todayAttendance ? 'check_circle' : 'fingerprint' }}

                    </span>

                </div>

            </div>


            @if($todayAttendance)

                <div class="mt-5 grid grid-cols-2 gap-3">

                    <div class="rounded-xl bg-green-50 p-4">

                        <p class="text-xs text-green-600">
                            Jam Masuk
                        </p>

                        <p class="mt-1 font-bold text-green-700">

                            {{ $todayAttendance->check_in_at?->format('H:i') ?? '--:--' }}

                        </p>

                    </div>


                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-xs text-gray-500">
                            Jam Pulang
                        </p>

                        <p class="mt-1 font-bold text-gray-700">

                            {{ $todayAttendance->check_out_at?->format('H:i') ?? '--:--' }}

                        </p>

                    </div>

                </div>

            @else

                <div class="mt-5 rounded-xl bg-red-50 px-4 py-3">

                    <div class="flex items-center gap-2">

                        <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>

                        <span class="text-sm font-medium text-red-700">
                            Anda belum melakukan absen masuk hari ini
                        </span>

                    </div>

                </div>

            @endif

        </div>

    </section>


    {{-- =========================================================
        LOKASI CABANG
    ========================================================== --}}

    @if($todaySchedule?->weeklySchedule?->branch)

        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm sm:rounded-3xl">

            <div class="border-b border-gray-100 p-5 sm:p-6">

                <div class="flex items-start gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50">

                        <span class="material-icons text-red-600">
                            location_on
                        </span>

                    </div>

                    <div class="min-w-0">

                        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
                            Lokasi Cabang
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Lokasi tempat Anda bertugas hari ini.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-5 sm:p-6">

                <div class="rounded-2xl bg-gray-50 p-5">

                    <div class="flex items-start gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm">

                            <span class="material-icons text-gray-600">
                                store
                            </span>

                        </div>


                        <div class="min-w-0">

                            <p class="font-semibold text-gray-900">
                                {{ $todaySchedule->weeklySchedule->branch->name }}
                            </p>

                            <p class="mt-1 text-sm leading-5 text-gray-500">
                                {{ $todaySchedule->weeklySchedule->branch->address }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    @endif


    {{-- =========================================================
        JADWAL MINGGU INI
    ========================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:rounded-3xl">

        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">

            <div>

                <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
                    Jadwal Minggu Ini
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $startOfWeek ?? now()->startOfWeek()->translatedFormat('d F Y') }}
                </p>

            </div>


            @if(Route::has('employee.schedules'))

                <a href="{{ route('employee.schedules') }}"
                   class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">

                    <span class="material-icons text-lg">
                        calendar_month
                    </span>

                    Lihat Jadwal

                </a>

            @endif

        </div>


        <div class="divide-y divide-gray-100">

            @forelse($weeklySchedules as $schedule)

                <div class="flex items-center gap-3 p-4 sm:gap-4 sm:p-5">

                    <div class="flex h-11 w-11 shrink-0 flex-col items-center justify-center rounded-xl bg-gray-50">

                        <span class="text-[10px] font-medium uppercase text-gray-400">
                            {{ $schedule->work_date->translatedFormat('D') }}
                        </span>

                        <span class="text-sm font-bold text-gray-900">
                            {{ $schedule->work_date->format('d') }}
                        </span>

                    </div>


                    <div class="min-w-0 flex-1">

                        <p class="truncate font-semibold text-gray-900">

                            {{ $schedule->shift?->name ?? ucfirst($schedule->status) }}

                        </p>

                        @if($schedule->shift)

                            <p class="truncate text-xs text-gray-500 sm:text-sm">

                                {{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') }}

                                -

                                {{ \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i') }}

                            </p>

                        @else

                            <p class="text-xs text-gray-500">
                                Tidak ada shift
                            </p>

                        @endif

                    </div>


                    @if($schedule->status === 'scheduled')

                        @if($schedule->attendance?->check_in_at)

                            <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                Hadir
                            </span>

                        @else

                            <span class="shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                Terjadwal
                            </span>

                        @endif

                    @elseif($schedule->status === 'off')

                        <span class="shrink-0 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">
                            Off
                        </span>

                    @elseif($schedule->status === 'leave')

                        <span class="shrink-0 rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700">
                            Izin
                        </span>

                    @endif

                </div>

            @empty

                <div class="p-8 text-center">

                    <span class="material-icons text-4xl text-gray-300">
                        event_busy
                    </span>

                    <p class="mt-3 font-medium text-gray-700">
                        Belum ada jadwal
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Belum ada jadwal kerja untuk minggu ini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
        AKTIVITAS ABSENSI
    ========================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:rounded-3xl">

        <div class="border-b border-gray-100 p-5 sm:p-6">

            <div class="flex items-center justify-between gap-3">

                <div>

                    <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
                        Aktivitas Absensi
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Riwayat absensi terbaru Anda.
                    </p>

                </div>

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gray-100">

                    <span class="material-icons text-gray-500">
                        history
                    </span>

                </div>

            </div>

        </div>


        <div class="divide-y divide-gray-100">

            @forelse($recentAttendances as $attendance)

                <div class="flex items-center gap-3 p-4 sm:gap-4 sm:p-5">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-50">

                        <span class="material-icons text-green-600">
                            login
                        </span>

                    </div>


                    <div class="min-w-0 flex-1">

                        <p class="truncate text-sm font-semibold text-gray-900 sm:text-base">
                            Absen Masuk
                        </p>

                        <p class="truncate text-xs text-gray-500 sm:text-sm">

                            {{ $attendance->branch?->name ?? 'Cabang tidak tersedia' }}

                        </p>

                    </div>


                    <div class="shrink-0 text-right">

                        <p class="text-sm font-semibold text-gray-900">

                            {{ $attendance->check_in_at?->format('H:i') ?? '--:--' }}

                        </p>

                        <p class="text-xs font-medium text-green-600">

                            {{ $attendance->check_in_at?->translatedFormat('d M Y') }}

                        </p>

                    </div>

                </div>

            @empty

                <div class="p-8 text-center">

                    <span class="material-icons text-4xl text-gray-300">
                        history
                    </span>

                    <p class="mt-3 font-medium text-gray-700">
                        Belum ada aktivitas
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Riwayat absensi Anda akan muncul di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
        INFORMASI
    ========================================================== --}}

    <section class="rounded-2xl border border-blue-100 bg-blue-50 p-4 sm:p-5">

        <div class="flex items-start gap-3">

            <span class="material-icons shrink-0 text-blue-600">
                info
            </span>

            <div>

                <h3 class="text-sm font-semibold text-blue-900">
                    Informasi Absensi
                </h3>

                <p class="mt-1 text-xs leading-5 text-blue-700 sm:text-sm">
                    Pastikan GPS aktif dan Anda berada di dalam radius lokasi cabang
                    saat melakukan absensi.
                </p>

            </div>

        </div>

    </section>

</div>

@endsection