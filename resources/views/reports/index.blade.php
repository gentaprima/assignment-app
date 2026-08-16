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
        <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <form method="GET" action="{{ route('reports.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- TANGGAL MULAI --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Dari Tanggal
                    </label>

                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

                </div>


                {{-- TANGGAL AKHIR --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Sampai Tanggal
                    </label>

                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

                </div>


                {{-- CABANG ADMIN --}}
                @if(auth()->user()->role === 'admin')

                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Cabang
                        </label>

                        <select name="branch_id"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

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
                    <div>

                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Cabang
                        </label>

                        <div
                            class="flex min-h-[46px] items-center rounded-xl bg-gray-50 px-4 text-sm font-medium text-gray-700">

                            {{ auth()->user()->branch?->name ?? 'Cabang belum ditentukan' }}

                        </div>

                    </div>

                @endif


                {{-- STATUS --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Status
                    </label>

                    <select name="status"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

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
                <div class="flex items-end md:col-span-2 lg:col-span-4">

                    <div class="flex w-full flex-col gap-3 sm:flex-row sm:justify-end">

                        <a href="{{ route('reports.index') }}"
                            class="rounded-xl border border-gray-300 px-5 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>

                        <button type="submit"
                            class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">
                            <span class="material-icons mr-1 align-middle text-[18px]">
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

            <div class="flex flex-col gap-3 border-b border-gray-200 p-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <h2 class="font-bold text-gray-900">
                        Detail Absensi
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
                        -
                        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                    </p>

                </div>

                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                    {{ $reports->count() }} data

                </span>

            </div>


            @if($reports->isEmpty())

                <div class="p-10 text-center">

                    <span class="material-icons text-5xl text-gray-300">
                        assessment
                    </span>

                    <p class="mt-3 font-medium text-gray-700">
                        Tidak ada data absensi
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Tidak ditemukan absensi pada periode yang dipilih.
                    </p>

                </div>

            @else

                {{-- DESKTOP TABLE --}}
                <div class="hidden overflow-x-auto md:block">

                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                            <tr>
                                <th class="px-5 py-4">
                                    Karyawan
                                </th>
                                <th class="px-5 py-4">
                                    Cabang
                                </th>
                                <th class="px-5 py-4">
                                    Tanggal
                                </th>
                                <th class="px-5 py-4">
                                    Jam Kerja
                                </th>
                                <th class="px-5 py-4">
                                    Masuk
                                </th>
                                <th class="px-5 py-4">
                                    Pulang
                                </th>
                                <th class="px-5 py-4">
                                    Foto
                                </th>
                                <th class="px-5 py-4">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($reports as $report)

                                @php
                                    $attendance = $report->attendance;
                                    $branch = $report->weeklySchedule?->branch;
                                    $shift = $report->shift;
                                @endphp

                                <tr class="hover:bg-gray-50">

                                    {{-- KARYAWAN --}}
                                    <td class="px-5 py-4">

                                        <p class="font-semibold text-gray-900">
                                            {{ $report->user?->name ?? '-' }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ $report->user?->email ?? '-' }}
                                        </p>

                                    </td>


                                    {{-- CABANG --}}
                                    <td class="px-5 py-4 text-gray-600">

                                        {{ $branch?->name ?? '-' }}

                                    </td>


                                    {{-- TANGGAL --}}
                                    <td class="px-5 py-4 text-gray-600">

                                        {{ $report->work_date?->translatedFormat('d M Y') ?? '-' }}

                                    </td>


                                    {{-- SHIFT --}}
                                    <td class="px-5 py-4 text-gray-600">

                                        @if($report->status == 'scheduled')

                                            <div class="rounded-xl bg-blue-50 px-4 py-2">
                                                <p class="font-semibold text-xs text-blue-500">
                                                    {{ \Carbon\Carbon::parse($report->start_time)->format('H:i') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($report->end_time)->format('H:i') }}
                                                </p>
                                            </div>

                                        @else

                                            <span class="text-gray-400">
                                                OFF
                                            </span>

                                        @endif

                                    </td>


                                    {{-- JAM MASUK --}}
                                    <td class="px-5 py-4 font-medium text-gray-900">

                                        {{ $attendance?->check_in_at?->format('H:i') ?? '--:--' }}

                                    </td>


                                    {{-- JAM PULANG --}}
                                    <td class="px-5 py-4 font-medium text-gray-900">

                                        {{ $attendance?->check_out_at?->format('H:i') ?? '--:--' }}

                                    </td>


                                    {{-- FOTO --}}
                                    <td class="px-5 py-4">

                                        <div class="flex flex-wrap gap-2">

                                            {{-- FOTO MASUK --}}
                                            @if($attendance?->check_in_photo)

                                                <button type="button" onclick="showAttendancePhoto(
                                                                    '{{ asset('storage/' . $attendance->check_in_photo) }}',
                                                                    'Foto Absen Masuk - {{ $report->user?->name }}'
                                                                )"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600 hover:bg-blue-100">

                                                    <span class="material-icons text-[16px]">
                                                        login
                                                    </span>

                                                    Masuk

                                                </button>

                                            @endif


                                            {{-- FOTO PULANG --}}
                                            @if($attendance?->check_out_photo)

                                                <button type="button" onclick="showAttendancePhoto(
                                                                    '{{ asset('storage/' . $attendance->check_out_photo) }}',
                                                                    'Foto Absen Pulang - {{ $report->user?->name }}'
                                                                )"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-orange-50 px-3 py-2 text-xs font-semibold text-orange-600 hover:bg-orange-100">

                                                    <span class="material-icons text-[16px]">
                                                        logout
                                                    </span>

                                                    Pulang

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

                                    </td>


                                    {{-- STATUS --}}
                                    <td class="px-5 py-4">

                                        @if($report->report_status === 'late')

                                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                                Terlambat
                                            </span>

                                        @elseif($report->report_status === 'leave')

                                            <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                                                Izin
                                            </span>

                                        @elseif($report->report_status === 'absent')

                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Tidak Hadir
                                            </span>

                                        @elseif($report->report_status === 'present')

                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                Hadir
                                            </span>

                                        @elseif($report->report_status === 'off')

                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                OFF
                                            </span>

                                        @else

                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                -

                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-5 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100">
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
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- MOBILE --}}
                <div class="divide-y divide-gray-100 md:hidden">
                    @foreach($reports as $report)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">
                                        {{ $report->user?->name ?? '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $report->branch?->name ?? '-' }}
                                    </p>
                                </div>
                                @if($report->status === 'late')
                                    <span class="shrink-0 rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                        Terlambat
                                    </span>
                                @elseif($report->status === 'leave')
                                    <span class="shrink-0 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">
                                        Izin
                                    </span>
                                @else
                                    <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        Hadir
                                    </span>
                                @endif
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">
                                        Tanggal
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ $report->check_in_at?->translatedFormat('d M Y') ?? '-' }}
                                    </p>
                                </div>
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">
                                        Jam Kerja
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($report->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($report->end_time)->format('H:i') }}
                                    </p>
                                </div
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">
                                        Masuk
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ $report->check_in_at?->format('H:i') ?? '--:--' }}
                                    </p>
                                </div>
                                <div class="rounded-xl bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">
                                        Pulang
                                    </p>
                                    <p class="mt-1 font-medium text-gray-900">
                                        {{ $report->check_out_at?->format('H:i') ?? '--:--' }}
                                    </p>
                                </div>
                                {{-- FOTO --}}
                                <div class="col-span-2 rounded-xl bg-gray-50 p-3">
                                    <p class="text-xs text-gray-500">
                                        Foto Absensi
                                    </p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        {{-- FOTO MASUK --}}
                                        @if($report->check_in_photo)
                                            <button type="button" onclick="showAttendancePhoto(
                                                                                                '{{ asset('storage/' . $report->check_in_photo) }}',
                                                                                                'Foto Absen Masuk - {{ $report->user?->name }}'
                                                                                            )"
                                                class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-600">
                                                <span class="material-icons text-[16px]">
                                                    login
                                                </span>
                                                Foto Masuk
                                            </button>
                                        @endif
                                        {{-- FOTO PULANG --}}
                                        @if($report->check_out_photo)
                                            <button type="button" onclick="showAttendancePhoto(
                                                                                                '{{ asset('storage/' . $report->check_out_photo) }}',
                                                                                                'Foto Absen Pulang - {{ $report->user?->name }}'
                                                                                            )"
                                                class="inline-flex items-center gap-1 rounded-lg bg-orange-50 px-3 py-2 text-xs font-semibold text-orange-600">
                                                <span class="material-icons text-[16px]">
                                                    logout
                                                </span>
                                                Foto Pulang
                                            </button>
                                        @endif
                                        @if(!$report->check_in_photo && !$report->check_out_photo)
                                            <span class="text-xs text-gray-400">
                                                Tidak ada foto
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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