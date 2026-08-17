@extends('components.dashboard-layout')

@section('content')

    <div class="space-y-6">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <section
            class="overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 to-red-500 p-6 text-white shadow-sm sm:p-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm text-red-100">
                        Jadwal Kerja
                    </p>

                    <h2 class="mt-1 text-2xl font-bold sm:text-3xl">
                        Jadwal Saya
                    </h2>

                    <p class="mt-2 text-sm text-red-100">
                        Lihat jadwal kerja Anda untuk minggu ini.
                    </p>

                </div>


                <div class="rounded-xl bg-white/10 px-5 py-3">

                    <p class="text-xs text-red-100">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold">

                        {{ $startDate->translatedFormat('d M') }}

                        -

                        {{ $endDate->translatedFormat('d M Y') }}

                    </p>

                </div>

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- FILTER MINGGU --}}
        {{-- ========================================================= --}}

        <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <form method="GET" action="{{ route('employee.schedules') }}"
                class="flex flex-col gap-4 sm:flex-row sm:items-end">

                <div class="flex-1">

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Pilih Minggu
                    </label>

                    <input type="date" name="week_start" value="{{ $startDate->format('Y-m-d') }}"
                        class="block h-12 w-full min-w-0 max-w-full appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700 outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500">

                </div>


                <button type="submit"
                    class="flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">

                    <span class="material-icons text-[20px]">
                        search
                    </span>

                    Lihat Jadwal

                </button>

            </form>

        </section>


        {{-- ========================================================= --}}
        {{-- RINGKASAN --}}
        {{-- ========================================================= --}}

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">


            {{-- TOTAL JADWAL --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Jadwal
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $schedules->count() }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Minggu ini
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">

                        <span class="material-icons text-blue-600">
                            calendar_month
                        </span>

                    </div>

                </div>

            </div>


            {{-- HARI KERJA --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Hari Kerja
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">

                            {{ $schedules->where('status', 'scheduled')->count() }}

                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Hari terjadwal
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                        <span class="material-icons text-green-600">
                            work
                        </span>

                    </div>

                </div>

            </div>


            {{-- OFF --}}

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Hari Libur
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">

                            {{ $schedules->where('status', 'off')->count() }}

                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Jadwal off
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">

                        <span class="material-icons text-gray-500">
                            event_available
                        </span>

                    </div>

                </div>

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- JADWAL --}}
        {{-- ========================================================= --}}

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">


            {{-- HEADER TABLE --}}

            <div class="border-b border-gray-200 p-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50">

                        <span class="material-icons text-red-600">
                            calendar_month
                        </span>

                    </div>


                    <div>

                        <h3 class="font-bold text-gray-900">
                            Jadwal Minggu Ini
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">

                            {{ $startDate->translatedFormat('d F Y') }}

                            -

                            {{ $endDate->translatedFormat('d F Y') }}

                        </p>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- EMPTY --}}
            {{-- ===================================================== --}}

            @if($schedules->isEmpty())

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                        <span class="material-icons text-3xl text-gray-400">
                            event_busy
                        </span>

                    </div>


                    <h3 class="mt-4 text-lg font-semibold text-gray-900">
                        Belum ada jadwal
                    </h3>


                    <p class="mx-auto mt-2 max-w-md text-sm text-gray-500">

                        Belum ada jadwal kerja yang diberikan untuk Anda pada periode ini.

                    </p>

                </div>


            @else


                {{-- ================================================= --}}
                {{-- DESKTOP TABLE --}}
                {{-- ================================================= --}}

                <div class="hidden overflow-x-auto md:block">

                    <table class="w-full text-left">


                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Hari / Tanggal
                                </th>


                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Cabang
                                </th>


                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jam Kerja
                                </th>


                                <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">


                            @foreach($schedules as $schedule)

                                <tr class="transition hover:bg-gray-50">


                                    {{-- ================================================= --}}
                                    {{-- TANGGAL --}}
                                    {{-- ================================================= --}}

                                    <td class="px-6 py-4">

                                        <p class="font-semibold text-gray-900">

                                            {{ $schedule->work_date->translatedFormat('l') }}

                                        </p>


                                        <p class="mt-1 text-sm text-gray-500">

                                            {{ $schedule->work_date->translatedFormat('d F Y') }}

                                        </p>

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- CABANG --}}
                                    {{-- ================================================= --}}

                                    <td class="px-6 py-4">

                                        <p class="font-medium text-gray-900">

                                            {{ $schedule->weeklySchedule?->branch?->name ?? '-' }}

                                        </p>


                                        <p class="mt-1 text-xs text-gray-500">

                                            {{ $schedule->weeklySchedule?->branch?->code ?? '-' }}

                                        </p>

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- JAM KERJA --}}
                                    {{-- ================================================= --}}

                                    <td class="px-6 py-4">

                                        @if(
                                                $schedule->status === 'scheduled' &&
                                                $schedule->start_time &&
                                                $schedule->end_time
                                            )

                                            <div class="flex items-center gap-2 text-sm font-medium text-gray-700">

                                                <span class="material-icons text-[18px] text-gray-400">
                                                    schedule
                                                </span>


                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}

                                                <span class="text-gray-400">
                                                    -
                                                </span>

                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}

                                            </div>


                                        @elseif($schedule->status === 'off')

                                            <span
                                                class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-600">

                                                <span class="material-icons text-[17px]">
                                                    event_available
                                                </span>

                                                OFF

                                            </span>


                                        @elseif($schedule->status === 'leave')

                                            <span
                                                class="inline-flex items-center gap-1 rounded-lg bg-orange-50 px-3 py-2 text-sm font-medium text-orange-600">

                                                <span class="material-icons text-[17px]">
                                                    event_busy
                                                </span>

                                                IZIN

                                            </span>


                                        @else

                                            <span class="text-sm text-gray-400">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ================================================= --}}
                                    {{-- STATUS --}}
                                    {{-- ================================================= --}}

                                    <td class="px-6 py-4">


                                        @if($schedule->status === 'scheduled')

                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                                                Terjadwal

                                            </span>


                                        @elseif($schedule->status === 'off')

                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                                Off

                                            </span>


                                        @elseif($schedule->status === 'leave')

                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>

                                                Izin

                                            </span>


                                        @else

                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                                                {{ ucfirst($schedule->status) }}

                                            </span>

                                        @endif


                                    </td>

                                </tr>

                            @endforeach


                        </tbody>

                    </table>

                </div>


                {{-- ================================================= --}}
                {{-- MOBILE CARDS --}}
                {{-- ================================================= --}}

                <div class="divide-y divide-gray-100 md:hidden">


                    @foreach($schedules as $schedule)


                        <div class="p-5">


                            {{-- HEADER CARD --}}

                            <div class="flex items-start justify-between gap-4">


                                <div>

                                    <p class="font-bold text-gray-900">

                                        {{ $schedule->work_date->translatedFormat('l') }}

                                    </p>


                                    <p class="mt-1 text-sm text-gray-500">

                                        {{ $schedule->work_date->translatedFormat('d F Y') }}

                                    </p>

                                </div>


                                {{-- STATUS --}}

                                @if($schedule->status === 'scheduled')

                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                        Terjadwal

                                    </span>


                                @elseif($schedule->status === 'off')

                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                                        Off

                                    </span>


                                @elseif($schedule->status === 'leave')

                                    <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">

                                        Izin

                                    </span>


                                @else

                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                                        {{ ucfirst($schedule->status) }}

                                    </span>

                                @endif


                            </div>


                            {{-- DETAIL --}}

                            <div class="mt-5 grid grid-cols-1 gap-3">


                                {{-- ================================================= --}}
                                {{-- CABANG --}}
                                {{-- ================================================= --}}

                                <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-3">

                                    <span class="material-icons text-gray-500">
                                        store
                                    </span>


                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Cabang
                                        </p>


                                        <p class="text-sm font-semibold text-gray-900">

                                            {{ $schedule->weeklySchedule?->branch?->name ?? '-' }}

                                        </p>


                                        @if($schedule->weeklySchedule?->branch?->code)

                                            <p class="text-xs text-gray-500">

                                                {{ $schedule->weeklySchedule->branch->code }}

                                            </p>

                                        @endif

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- JAM KERJA --}}
                                {{-- ================================================= --}}

                                <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-3">

                                    <span class="material-icons text-gray-500">
                                        schedule
                                    </span>


                                    <div>

                                        <p class="text-xs text-gray-500">
                                            Jam Kerja
                                        </p>


                                        @if(
                                                $schedule->status === 'scheduled' &&
                                                $schedule->start_time &&
                                                $schedule->end_time
                                            )

                                            <p class="text-sm font-semibold text-gray-900">

                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}

                                                -

                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}

                                            </p>


                                        @elseif($schedule->status === 'off')

                                            <p class="text-sm font-semibold text-gray-500">
                                                OFF
                                            </p>


                                        @elseif($schedule->status === 'leave')

                                            <p class="text-sm font-semibold text-orange-600">
                                                IZIN
                                            </p>


                                        @else

                                            <p class="text-sm text-gray-400">
                                                -
                                            </p>

                                        @endif

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- CATATAN --}}
                                {{-- ================================================= --}}

                                @if($schedule->notes)

                                    <div class="flex items-start gap-3 rounded-xl bg-blue-50 p-3">

                                        <span class="material-icons text-blue-500">
                                            notes
                                        </span>


                                        <div>

                                            <p class="text-xs text-blue-600">
                                                Catatan
                                            </p>


                                            <p class="text-sm text-gray-700">

                                                {{ $schedule->notes }}

                                            </p>

                                        </div>

                                    </div>

                                @endif


                            </div>

                        </div>


                    @endforeach


                </div>


            @endif


        </section>

    </div>

@endsection