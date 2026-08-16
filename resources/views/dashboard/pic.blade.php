@extends('components.dashboard-layout')

@section('title', 'Dashboard PIC')

@section('content')

<div class="space-y-5 sm:space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-red-600 via-red-500 to-orange-400 p-5 text-white shadow-sm sm:rounded-3xl sm:p-6 lg:p-8">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="min-w-0">

                <p class="text-sm font-medium text-red-100">
                    Selamat datang kembali 👋
                </p>

                <h1 class="mt-1 text-2xl font-bold tracking-tight sm:text-3xl">
                    {{ $user->name }}
                </h1>

                <p class="mt-2 text-sm leading-6 text-red-50 sm:text-base">
                    Pantau absensi dan kelola jadwal karyawan cabang Anda.
                </p>

                {{-- CABANG --}}
                <div class="mt-4 flex items-start gap-2">

                    <span class="material-icons text-lg">
                        store
                    </span>

                    <div>

                        <p class="text-sm font-semibold">
                            {{ $branch?->name ?? 'Cabang belum ditentukan' }}
                        </p>

                        @if($branch)
                            <p class="mt-0.5 text-xs text-red-100">
                                {{ $branch->code }}
                            </p>
                        @endif

                    </div>

                </div>

            </div>


            {{-- TANGGAL --}}

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
        STATISTIK
    ========================================================== --}}

    <section class="grid grid-cols-2 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">


        {{-- KARYAWAN --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-start justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Karyawan
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $totalEmployees }}
                    </p>

                    <p class="mt-1 text-xs text-gray-400">
                        Total karyawan cabang
                    </p>

                </div>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 sm:h-11 sm:w-11">

                    <span class="material-icons text-blue-600">
                        groups
                    </span>

                </div>

            </div>

        </div>


        {{-- HADIR --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-start justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Hadir
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $hadir }}
                    </p>

                    <p class="mt-1 text-xs font-medium text-green-600">

                        @if($totalEmployees > 0)
                            {{ number_format(($hadir / $totalEmployees) * 100, 1) }}%
                        @else
                            0%
                        @endif

                        dari karyawan

                    </p>

                </div>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 sm:h-11 sm:w-11">

                    <span class="material-icons text-green-600">
                        how_to_reg
                    </span>

                </div>

            </div>

        </div>


        {{-- TERLAMBAT --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-start justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Terlambat
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $terlambat }}
                    </p>

                    <p class="mt-1 text-xs text-orange-600">
                        Hari ini
                    </p>

                </div>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 sm:h-11 sm:w-11">

                    <span class="material-icons text-orange-600">
                        schedule
                    </span>

                </div>

            </div>

        </div>


        {{-- BELUM ABSEN --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <div class="flex items-start justify-between gap-3">

                <div>

                    <p class="text-xs font-medium text-gray-500 sm:text-sm">
                        Belum Absen
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl">
                        {{ $belumAbsen }}
                    </p>

                    <p class="mt-1 text-xs text-red-600">
                        Perlu diperiksa
                    </p>

                </div>

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 sm:h-11 sm:w-11">

                    <span class="material-icons text-red-600">
                        person_off
                    </span>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        QUICK ACTION
    ========================================================== --}}

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">


        {{-- JADWAL --}}

        <a href="{{ route('pic.schedules') }}"
           class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-red-200 hover:shadow-md sm:p-6">

            <div class="flex items-center gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-red-50">

                    <span class="material-icons text-red-600">
                        calendar_month
                    </span>

                </div>

                <div class="min-w-0 flex-1">

                    <h3 class="font-semibold text-gray-900">
                        Kelola Jadwal
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500 sm:text-sm">
                        Buat dan kelola jadwal karyawan cabang.
                    </p>

                </div>

                <span class="material-icons text-gray-300 group-hover:text-red-500">
                    chevron_right
                </span>

            </div>

        </a>


        {{-- KARYAWAN --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="flex items-center gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50">

                    <span class="material-icons text-blue-600">
                        groups
                    </span>

                </div>

                <div class="min-w-0 flex-1">

                    <h3 class="font-semibold text-gray-900">
                        Karyawan
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500 sm:text-sm">
                        {{ $totalEmployees }} karyawan di cabang ini.
                    </p>

                </div>

            </div>

        </div>


        {{-- JADWAL MINGGU --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="flex items-center gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-purple-50">

                    <span class="material-icons text-purple-600">
                        date_range
                    </span>

                </div>

                <div class="min-w-0 flex-1">

                    <h3 class="font-semibold text-gray-900">
                        Jadwal Minggu Ini
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-gray-500 sm:text-sm">
                        {{ $totalWeeklySchedules }} jadwal dibuat.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        ABSENSI HARI INI
    ========================================================== --}}

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:rounded-3xl">


        <div class="flex flex-col gap-3 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">

            <div>

                <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
                    Absensi Hari Ini
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Monitoring kehadiran karyawan
                    {{ $branch?->name }}
                </p>

            </div>

        </div>


        {{-- DESKTOP --}}

        <div class="hidden overflow-x-auto md:block">

            <table class="w-full min-w-[750px]">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Karyawan
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Jadwal
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Jam Masuk
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($todayAssignments as $assignment)

                        @php

                            $employee = $assignment->user;
                            $attendance = $assignment->attendance;
                            $shift = $assignment->shift;

                            $initial = strtoupper(
                                substr($employee->name ?? '?', 0, 1)
                            );

                        @endphp


                        <tr class="transition hover:bg-gray-50">

                            {{-- KARYAWAN --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-100 text-sm font-semibold text-red-600">

                                        {{ $initial }}

                                    </div>

                                    <div>

                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $employee->name }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ ucfirst($employee->role) }}
                                        </p>

                                    </div>

                                </div>

                            </td>


                            {{-- SHIFT --}}

                            <td class="px-6 py-4 text-sm text-gray-600">

                                @if($assignment->status === 'off')

                                    <span class="font-medium text-gray-500">
                                        OFF
                                    </span>

                                @elseif($assignment->status === 'leave')

                                    <span class="font-medium text-orange-600">
                                        IZIN
                                    </span>

                                @elseif($shift)

                                    {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}

                                @else

                                    <span class="text-gray-400">
                                        Belum dijadwalkan
                                    </span>

                                @endif

                            </td>


                            {{-- JAM MASUK --}}

                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">

                                @if($attendance?->check_in_at)

                                    {{ \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') }}

                                @else

                                    <span class="text-gray-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td class="px-6 py-4">

                                @if($assignment->status === 'off')

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600">

                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                        Off

                                    </span>


                                @elseif($assignment->status === 'leave')

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1.5 text-xs font-medium text-orange-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>

                                        Izin

                                    </span>


                                @elseif($attendance?->check_in_at)

                                    @php

                                        $checkIn = \Carbon\Carbon::parse(
                                            $attendance->check_in_at
                                        );

                                        $shiftStart = $shift
                                            ? \Carbon\Carbon::parse($shift->start_time)
                                            : null;

                                        $isLate = $shiftStart
                                            && $checkIn->gt($shiftStart);

                                    @endphp


                                    @if($isLate)

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1.5 text-xs font-medium text-orange-700">

                                            <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>

                                            Terlambat

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700">

                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                            Hadir

                                        </span>

                                    @endif


                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600">

                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                        Belum Absen

                                    </span>

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="4" class="px-6 py-10 text-center">

                                <span class="material-icons text-4xl text-gray-300">
                                    event_busy
                                </span>

                                <p class="mt-2 text-sm font-medium text-gray-600">
                                    Belum ada jadwal hari ini
                                </p>

                                <p class="mt-1 text-xs text-gray-400">
                                    Silakan buat jadwal karyawan terlebih dahulu.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}

        <div class="divide-y divide-gray-100 md:hidden">

            @forelse($todayAssignments as $assignment)

                @php

                    $employee = $assignment->user;
                    $attendance = $assignment->attendance;
                    $shift = $assignment->shift;

                @endphp

                <div class="p-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 font-semibold text-red-600">

                            {{ strtoupper(substr($employee->name ?? '?', 0, 1)) }}

                        </div>


                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-gray-900">
                                {{ $employee->name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ ucfirst($employee->role) }}
                            </p>

                        </div>


                        @if($assignment->status === 'off')

                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                Off
                            </span>

                        @elseif($assignment->status === 'leave')

                            <span class="rounded-full bg-orange-50 px-2.5 py-1 text-xs font-medium text-orange-700">
                                Izin
                            </span>

                        @elseif($attendance?->check_in_at)

                            @php

                                $checkIn = \Carbon\Carbon::parse(
                                    $attendance->check_in_at
                                );

                                $shiftStart = $shift
                                    ? \Carbon\Carbon::parse($shift->start_time)
                                    : null;

                                $isLate = $shiftStart
                                    && $checkIn->gt($shiftStart);

                            @endphp

                            @if($isLate)

                                <span class="rounded-full bg-orange-50 px-2.5 py-1 text-xs font-medium text-orange-700">
                                    Terlambat
                                </span>

                            @else

                                <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                    Hadir
                                </span>

                            @endif

                        @else

                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                Belum
                            </span>

                        @endif

                    </div>


                    <div class="mt-3 grid grid-cols-2 gap-3 rounded-xl bg-gray-50 p-3">

                        <div>

                            <p class="text-xs text-gray-400">
                                Jadwal
                            </p>

                            <p class="mt-1 text-sm font-medium text-gray-700">

                                @if($assignment->status === 'off')
                                    OFF

                                @elseif($assignment->status === 'leave')
                                    IZIN

                                @elseif($shift)

                                    {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}

                                @else
                                    -
                                @endif

                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-gray-400">
                                Jam Masuk
                            </p>

                            <p class="mt-1 text-sm font-semibold text-gray-900">

                                @if($attendance?->check_in_at)

                                    {{ \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') }}

                                @else

                                    <span class="text-gray-400">
                                        Belum
                                    </span>

                                @endif

                            </p>

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-8 text-center">

                    <span class="material-icons text-4xl text-gray-300">
                        event_busy
                    </span>

                    <p class="mt-2 text-sm font-medium text-gray-600">
                        Belum ada jadwal hari ini
                    </p>

                </div>

            @endforelse

        </div>

    </section>


    {{-- =========================================================
        JADWAL MINGGU INI
    ========================================================== --}}

    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:rounded-3xl sm:p-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50 sm:rounded-2xl">

                        <span class="material-icons text-purple-600">
                            date_range
                        </span>

                    </div>

                    <div>

                        <h2 class="text-lg font-bold text-gray-900 sm:text-xl">
                            Jadwal Minggu Ini
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">

                            {{ $startOfWeek->translatedFormat('d F Y') }}
                            -
                            {{ $endOfWeek->translatedFormat('d F Y') }}

                        </p>

                    </div>

                </div>

            </div>


            <a href="{{ route('pic.schedules') }}"
               class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">

                <span class="material-icons text-lg">
                    edit_calendar
                </span>

                Kelola Jadwal

            </a>

        </div>


        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">


            {{-- TOTAL --}}

            <div class="rounded-xl bg-gray-50 p-4">

                <p class="text-xs text-gray-400">
                    Total Jadwal
                </p>

                <p class="mt-1 text-xl font-bold text-gray-900">
                    {{ $totalWeeklySchedules }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Minggu ini
                </p>

            </div>


            {{-- TERJADWAL --}}

            <div class="rounded-xl bg-green-50 p-4">

                <p class="text-xs text-green-600">
                    Terjadwal
                </p>

                <p class="mt-1 text-xl font-bold text-green-700">
                    {{ $scheduledWeekly }}
                </p>

                <p class="mt-1 text-xs text-green-600">
                    Shift kerja
                </p>

            </div>


            {{-- OFF --}}

            <div class="rounded-xl bg-gray-50 p-4">

                <p class="text-xs text-gray-500">
                    Off / Izin
                </p>

                <p class="mt-1 text-xl font-bold text-gray-700">
                    {{ $offWeekly + $leaveWeekly }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Off {{ $offWeekly }} · Izin {{ $leaveWeekly }}
                </p>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PERLU PERHATIAN
    ========================================================== --}}

    @if($belumAbsen > 0 || $izin > 0)

        <section class="rounded-2xl border border-orange-100 bg-orange-50 p-4 sm:p-5">

            <div class="flex items-start gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-100">

                    <span class="material-icons text-orange-600">
                        warning
                    </span>

                </div>

                <div class="min-w-0">

                    <h3 class="text-sm font-semibold text-orange-900">
                        Perlu Perhatian
                    </h3>

                    <p class="mt-1 text-xs leading-5 text-orange-700 sm:text-sm">

                        @if($belumAbsen > 0)

                            Ada
                            <strong>{{ $belumAbsen }}</strong>
                            karyawan yang belum melakukan absensi.

                        @endif

                        @if($izin > 0)

                            Terdapat
                            <strong>{{ $izin }}</strong>
                            karyawan dengan status izin hari ini.

                        @endif

                    </p>

                </div>

            </div>

        </section>

    @endif

</div>

@endsection