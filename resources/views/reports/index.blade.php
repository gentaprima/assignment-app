@extends('components.dashboard-layout')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <section class="rounded-2xl bg-gradient-to-r from-red-600 to-red-500 p-6 text-white shadow-sm sm:p-8">

            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <p class="text-sm text-red-100">
                        Laporan Absensi
                    </p>

                    <h1 class="mt-1 text-2xl font-bold sm:text-3xl">
                        Laporan Kehadiran
                    </h1>

                    <p class="mt-2 text-sm text-red-100">
                        {{ auth()->user()->role === 'admin'
        ? 'Pantau kehadiran seluruh cabang.'
        : 'Pantau kehadiran cabang Anda.'
                                                        }}
                    </p>

                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/10">

                    <span class="material-icons">
                        assessment
                    </span>

                </div>

            </div>

        </section>


        {{-- FILTER --}}
        <section class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">

            <form method="GET" action="{{ route('reports.index') }}"
                class="grid min-w-0 grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- TANGGAL MULAI --}}
                <div class="min-w-0">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Dari Tanggal
                    </label>

                    <div class="relative min-w-0">
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="block h-[46px] w-full min-w-0 max-w-full appearance-none box-border rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20 sm:px-4">
                    </div>

                </div>


                {{-- TANGGAL AKHIR --}}
                <div class="min-w-0">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Sampai Tanggal
                    </label>

                    <div class="relative min-w-0">
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="block h-[46px] w-full min-w-0 max-w-full appearance-none box-border rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20 sm:px-4">
                    </div>

                </div>


                {{-- CABANG ADMIN --}}
                @if(auth()->user()->role === 'admin')

                    <div class="min-w-0">

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Cabang
                        </label>

                        <select name="branch_id"
                            class="block h-[46px] w-full min-w-0 max-w-full box-border rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20 sm:px-4">

                            <option value="">
                                Semua Cabang
                            </option>

                            @foreach($branches as $branch)

                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->code }} - {{ $branch->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                @else

                    {{-- CABANG PIC --}}
                    <div class="min-w-0">

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Cabang
                        </label>

                        <div
                            class="flex h-[46px] min-w-0 max-w-full items-center overflow-hidden rounded-xl bg-gray-50 px-3 text-sm font-medium text-gray-700 sm:px-4">

                            <span class="truncate">
                                {{ auth()->user()->branch?->name ?? 'Cabang belum ditentukan' }}
                            </span>

                        </div>

                    </div>

                @endif


                {{-- STATUS --}}
                <div class="min-w-0">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Status
                    </label>

                    <select name="status"
                        class="block h-[46px] w-full min-w-0 max-w-full box-border rounded-xl border border-gray-300 bg-white px-3 py-3 text-sm text-gray-700 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20 sm:px-4">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>
                            Hadir
                        </option>

                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>
                            Terlambat
                        </option>

                        <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>
                            Tidak Hadir
                        </option>

                        <option value="leave" {{ request('status') === 'leave' ? 'selected' : '' }}>
                            Izin
                        </option>

                    </select>

                </div>


                {{-- BUTTON --}}
                <div class="flex min-w-0 items-end md:col-span-2 lg:col-span-4">

                    <div class="flex w-full min-w-0 flex-col gap-3 sm:flex-row sm:justify-end">

                        {{-- RESET --}}
                        <a href="{{ route('reports.index') }}"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 px-5 py-3 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-50 sm:w-auto">

                            <span class="material-icons text-[18px]">
                                restart_alt
                            </span>

                            Reset

                        </a>


                        {{-- EXPORT EXCEL --}}
                        <button type="submit" formaction="{{ route('reports.export') }}" formmethod="GET"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-700 active:scale-[0.98] sm:w-auto">

                            <span class="material-icons text-[18px]">
                                table_view
                            </span>

                            Export Excel

                        </button>


                        {{-- FILTER --}}
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700 active:scale-[0.98] sm:w-auto">

                            <span class="material-icons text-[18px]">
                                filter_alt
                            </span>

                            Tampilkan Laporan

                        </button>

                    </div>

                </div>

            </form>

        </section>



        {{-- STATISTIK --}}
        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- TOTAL --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Absensi
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $totalAttendance }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Record absensi
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">

                        <span class="material-icons text-blue-600">
                            fact_check
                        </span>

                    </div>

                </div>

            </div>


            {{-- HADIR --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Hadir
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $totalPresent }}
                        </p>

                        <p class="mt-1 text-xs text-green-600">
                            Berhasil absen
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                        <span class="material-icons text-green-600">
                            check_circle
                        </span>

                    </div>

                </div>

            </div>


            {{-- TERLAMBAT --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Terlambat
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $totalLate }}
                        </p>

                        <p class="mt-1 text-xs text-orange-600">
                            Datang terlambat
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50">

                        <span class="material-icons text-orange-600">
                            schedule
                        </span>

                    </div>

                </div>

            </div>


            {{-- IZIN --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Izin
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $totalLeave }}
                        </p>

                        <p class="mt-1 text-xs text-purple-600">
                            Pengajuan izin
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-50">

                        <span class="material-icons text-purple-600">
                            event_busy
                        </span>

                    </div>

                </div>

            </div>

        </section>


        {{-- TABLE --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            {{-- HEADER TABLE --}}
            <div class="border-b border-gray-200 p-5">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div class="min-w-0">

                        <h2 class="font-bold text-gray-900">
                            Detail Absensi
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
                            -
                            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                        </p>

                    </div>

                    <span class="w-fit shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                        {{ $reports->count() }} data
                    </span>

                </div>

            </div>


            @if($reports->isEmpty())

                {{-- EMPTY STATE --}}
                <div class="p-10 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">

                        <span class="material-icons text-2xl text-gray-400">
                            event_busy
                        </span>

                    </div>

                    <p class="mt-4 font-semibold text-gray-700">
                        Tidak ada data laporan
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Tidak ditemukan data absensi pada periode yang dipilih.
                    </p>

                </div>

            @else

                {{-- ========================================================= --}}
                {{-- DESKTOP --}}
                {{-- ========================================================= --}}

                <div class="hidden md:block">

                    <div class="w-full overflow-x-auto">

                        <table class="min-w-[1100px] w-full text-left text-sm">

                            <thead class="border-b border-gray-200 bg-gray-50">

                                <tr class="text-xs font-semibold uppercase tracking-wide text-gray-500">

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Karyawan
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Cabang
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Tanggal
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Jam Kerja
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Masuk
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Pulang
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Foto
                                    </th>

                                    <th class="whitespace-nowrap px-5 py-4">
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach($reports as $report)

                                    @php

                                        $attendance = $report->attendance;

                                        $branch = $report->weeklySchedule?->branch;

                                        $startTime = $report->start_time
                                            ? \Carbon\Carbon::parse($report->start_time)->format('H:i')
                                            : null;

                                        $endTime = $report->end_time
                                            ? \Carbon\Carbon::parse($report->end_time)->format('H:i')
                                            : null;

                                    @endphp


                                    <tr class="align-middle transition hover:bg-gray-50">

                                        {{-- KARYAWAN --}}
                                        <td class="max-w-[220px] px-5 py-4">

                                            <div class="min-w-0">

                                                <p class="truncate font-semibold text-gray-900">
                                                    {{ $report->user?->name ?? '-' }}
                                                </p>

                                                <p class="mt-1 truncate text-xs text-gray-500">
                                                    {{ $report->user?->email ?? '-' }}
                                                </p>

                                            </div>

                                        </td>


                                        {{-- CABANG --}}
                                        <td class="max-w-[180px] px-5 py-4">

                                            <p class="truncate text-gray-600">
                                                {{ $branch?->name ?? '-' }}
                                            </p>

                                        </td>


                                        {{-- TANGGAL --}}
                                        <td class="whitespace-nowrap px-5 py-4 text-gray-600">

                                            {{ $report->work_date?->translatedFormat('d M Y') ?? '-' }}

                                        </td>


                                        {{-- JAM KERJA --}}
                                        <td class="whitespace-nowrap px-5 py-4">

                                            @if($report->status === 'scheduled' && $startTime && $endTime)

                                                <div class="inline-flex rounded-xl bg-blue-50 px-3 py-2">

                                                    <span class="text-xs font-semibold text-blue-600">
                                                        {{ $startTime }} - {{ $endTime }}
                                                    </span>

                                                </div>

                                            @elseif($report->status === 'off')

                                                <span
                                                    class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                    OFF
                                                </span>

                                            @elseif($report->status === 'leave')

                                                <span
                                                    class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                                                    IZIN
                                                </span>

                                            @else

                                                <span class="text-gray-400">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- JAM MASUK --}}
                                        <td class="whitespace-nowrap px-5 py-4">

                                            <span class="font-semibold text-gray-900">
                                                {{ $attendance?->check_in_at?->format('H:i') ?? '--:--' }}
                                            </span>

                                        </td>


                                        {{-- JAM PULANG --}}
                                        <td class="whitespace-nowrap px-5 py-4">

                                            <span class="font-semibold text-gray-900">
                                                {{ $attendance?->check_out_at?->format('H:i') ?? '--:--' }}
                                            </span>

                                        </td>


                                        {{-- FOTO --}}
                                        <td class="px-5 py-4">

                                            <div class="flex flex-wrap gap-2">

                                                @if($attendance?->check_in_photo)

                                                    <button type="button" onclick="showAttendancePhoto(
                                                                                                '{{ asset('storage/' . $attendance->check_in_photo) }}',
                                                                                                'Foto Absen Masuk - {{ $report->user?->name }}'
                                                                                            )"
                                                        class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 transition hover:bg-blue-100">

                                                        <span class="material-icons text-[16px]">
                                                            login
                                                        </span>

                                                        Masuk

                                                    </button>

                                                @endif


                                                @if($attendance?->check_out_photo)

                                                    <button type="button" onclick="showAttendancePhoto(
                                                                                                '{{ asset('storage/' . $attendance->check_out_photo) }}',
                                                                                                'Foto Absen Pulang - {{ $report->user?->name }}'
                                                                                            )"
                                                        class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-orange-50 px-3 py-2 text-xs font-semibold text-orange-600 transition hover:bg-orange-100">

                                                        <span class="material-icons text-[16px]">
                                                            logout
                                                        </span>

                                                        Pulang

                                                    </button>

                                                @endif


                                                @if(
                                                        !$attendance?->check_in_photo &&
                                                        !$attendance?->check_out_photo
                                                    )

                                                    <span class="text-xs text-gray-400">
                                                        Tidak ada foto
                                                    </span>

                                                @endif

                                            </div>

                                        </td>


                                        {{-- STATUS --}}
                                        <td class="whitespace-nowrap px-5 py-4">

                                            @if($report->report_status === 'late')

                                                <span
                                                    class="inline-flex rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                                    Terlambat
                                                </span>

                                            @elseif($report->report_status === 'leave')

                                                <span
                                                    class="inline-flex rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                                                    Izin
                                                </span>

                                            @elseif($report->report_status === 'absent')

                                                <span
                                                    class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                    Tidak Hadir
                                                </span>

                                            @elseif($report->report_status === 'present')

                                                <span
                                                    class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                    Hadir
                                                </span>

                                            @elseif($report->report_status === 'off')

                                                <span
                                                    class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                    OFF
                                                </span>

                                            @else

                                                <span
                                                    class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                    -
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- ========================================================= --}}
                {{-- MOBILE --}}
                {{-- ========================================================= --}}

                <div class="block md:hidden">

                    <div class="divide-y divide-gray-100">

                        @foreach($reports as $report)

                            @php

                                $attendance = $report->attendance;

                                $branch = $report->weeklySchedule?->branch;

                                $startTime = $report->start_time
                                    ? \Carbon\Carbon::parse($report->start_time)->format('H:i')
                                    : null;

                                $endTime = $report->end_time
                                    ? \Carbon\Carbon::parse($report->end_time)->format('H:i')
                                    : null;

                            @endphp


                            {{-- CARD --}}
                            <div class="w-full min-w-0 p-4 sm:p-5">

                                {{-- HEADER CARD --}}
                                <div class="flex min-w-0 items-start gap-3">

                                    {{-- USER --}}
                                    <div class="min-w-0 flex-1">

                                        <p class="break-words font-semibold leading-5 text-gray-900">
                                            {{ $report->user?->name ?? '-' }}
                                        </p>

                                        <p class="mt-1 break-all text-xs text-gray-500">
                                            {{ $report->user?->email ?? '-' }}
                                        </p>

                                        <p class="mt-1 break-words text-xs text-gray-500">
                                            {{ $branch?->name ?? 'Cabang tidak ditemukan' }}
                                        </p>

                                    </div>


                                    {{-- STATUS --}}
                                    <div class="shrink-0">

                                        @if($report->report_status === 'late')

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full bg-orange-50 px-2.5 py-1 text-[11px] font-semibold text-orange-700">
                                                Terlambat
                                            </span>

                                        @elseif($report->report_status === 'leave')

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full bg-purple-50 px-2.5 py-1 text-[11px] font-semibold text-purple-700">
                                                Izin
                                            </span>

                                        @elseif($report->report_status === 'absent')

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full bg-red-50 px-2.5 py-1 text-[11px] font-semibold text-red-700">
                                                Tidak Hadir
                                            </span>

                                        @elseif($report->report_status === 'present')

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full bg-green-50 px-2.5 py-1 text-[11px] font-semibold text-green-700">
                                                Hadir
                                            </span>

                                        @elseif($report->report_status === 'off')

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-600">
                                                OFF
                                            </span>

                                        @else

                                            <span
                                                class="inline-flex whitespace-nowrap rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-600">
                                                -
                                            </span>

                                        @endif

                                    </div>

                                </div>


                                {{-- INFO GRID --}}
                                <div class="mt-4 grid grid-cols-2 gap-3">

                                    {{-- TANGGAL --}}
                                    <div class="min-w-0 rounded-xl bg-gray-50 p-3">

                                        <p class="text-[11px] font-medium text-gray-500">
                                            Tanggal
                                        </p>

                                        <p class="mt-1 break-words text-sm font-semibold text-gray-900">

                                            {{ $report->work_date?->translatedFormat('d M Y') ?? '-' }}

                                        </p>

                                    </div>


                                    {{-- JAM KERJA --}}
                                    <div class="min-w-0 rounded-xl bg-gray-50 p-3">

                                        <p class="text-[11px] font-medium text-gray-500">
                                            Jam Kerja
                                        </p>

                                        @if($report->status === 'scheduled' && $startTime && $endTime)

                                            <p class="mt-1 break-words text-sm font-semibold text-gray-900">
                                                {{ $startTime }} - {{ $endTime }}
                                            </p>

                                        @elseif($report->status === 'off')

                                            <p class="mt-1 text-sm font-semibold text-gray-500">
                                                OFF
                                            </p>

                                        @elseif($report->status === 'leave')

                                            <p class="mt-1 text-sm font-semibold text-purple-600">
                                                IZIN
                                            </p>

                                        @else

                                            <p class="mt-1 text-sm font-semibold text-gray-400">
                                                -
                                            </p>

                                        @endif

                                    </div>


                                    {{-- MASUK --}}
                                    <div class="min-w-0 rounded-xl bg-gray-50 p-3">

                                        <p class="text-[11px] font-medium text-gray-500">
                                            Absen Masuk
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ $attendance?->check_in_at?->format('H:i') ?? '--:--' }}
                                        </p>

                                    </div>


                                    {{-- PULANG --}}
                                    <div class="min-w-0 rounded-xl bg-gray-50 p-3">

                                        <p class="text-[11px] font-medium text-gray-500">
                                            Absen Pulang
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-gray-900">
                                            {{ $attendance?->check_out_at?->format('H:i') ?? '--:--' }}
                                        </p>

                                    </div>

                                </div>


                                {{-- FOTO --}}
                                <div class="mt-3 min-w-0 rounded-xl bg-gray-50 p-3">

                                    <p class="text-[11px] font-medium text-gray-500">
                                        Foto Absensi
                                    </p>


                                    <div class="mt-2 flex min-w-0 flex-wrap gap-2">

                                        {{-- FOTO MASUK --}}
                                        @if($attendance?->check_in_photo)

                                            <button type="button" onclick="showAttendancePhoto(
                                                                                        '{{ asset('storage/' . $attendance->check_in_photo) }}',
                                                                                        'Foto Absen Masuk - {{ $report->user?->name }}'
                                                                                    )"
                                                class="inline-flex max-w-full shrink-0 items-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600">

                                                <span class="material-icons shrink-0 text-[16px]">
                                                    login
                                                </span>

                                                <span class="truncate">
                                                    Foto Masuk
                                                </span>

                                            </button>

                                        @endif


                                        {{-- FOTO PULANG --}}
                                        @if($attendance?->check_out_photo)

                                            <button type="button" onclick="showAttendancePhoto(
                                                                                        '{{ asset('storage/' . $attendance->check_out_photo) }}',
                                                                                        'Foto Absen Pulang - {{ $report->user?->name }}'
                                                                                    )"
                                                class="inline-flex max-w-full shrink-0 items-center gap-1 rounded-lg bg-orange-50 px-3 py-2 text-xs font-semibold text-orange-600">

                                                <span class="material-icons shrink-0 text-[16px]">
                                                    logout
                                                </span>

                                                <span class="truncate">
                                                    Foto Pulang
                                                </span>

                                            </button>

                                        @endif


                                        {{-- TIDAK ADA FOTO --}}
                                        @if(
                                                !$attendance?->check_in_photo &&
                                                !$attendance?->check_out_photo
                                            )

                                            <span class="text-xs text-gray-400">
                                                Tidak ada foto
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

        </section>
    </div>
@endsection
<script>
    function showAttendancePhoto(photoUrl, title) {
        Swal.fire({
            title: title,
            html: `
            <div class="overflow-hidden rounded-xl bg-gray-100">
                <img
                    src="${photoUrl}"
                    alt="Foto Absensi"
                    class="mx-auto max-h-[70vh] w-auto max-w-full rounded-xl object-contain"
                >
            </div>
        `,
            showConfirmButton: true,
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#dc2626',
            width: '600px',
            allowOutsideClick: true,
            allowEscapeKey: true
        });

    }
</script>