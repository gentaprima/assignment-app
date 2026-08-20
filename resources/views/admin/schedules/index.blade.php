@extends('components.dashboard-layout')

@section('content')

    <div class="space-y-6">

        {{-- ======================================== --}}
        {{-- HEADER --}}
        {{-- ======================================== --}}

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Jadwal Kerja
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Kelola jadwal kerja karyawan per minggu.
                </p>
            </div>


            <button type="button" onclick="openScheduleModal()"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">

                <span class="material-icons text-lg">
                    add
                </span>

                Buat Jadwal

            </button>

        </div>


        {{-- ======================================== --}}
        {{-- FILTER --}}
        {{-- ======================================== --}}

        <form method="GET" action="{{ route('admin.schedules') }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">


                {{-- MINGGU --}}
                <div class="w-full min-w-0">

                    <label for="week" class="mb-2 block text-sm font-medium text-gray-700">
                        Minggu
                    </label>

                    <input type="date" name="week" id="week" value="{{ $weekStart->format('Y-m-d') }}"
                        class="block h-12 w-full min-w-0 appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm leading-5 text-gray-900 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">

                </div>


                {{-- CABANG --}}

                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Cabang
                    </label>

                    <select name="branch_id"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                        {{ auth()->user()->role === 'pic' ? 'disabled' : '' }}>

                        @foreach($branches as $branch)

                            <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                {{ $branch->code }} - {{ $branch->name }}
                            </option>

                        @endforeach

                    </select>

                    @if(auth()->user()->role === 'pic')
                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    @endif

                </div>


                {{-- BUTTON --}}

                <div class="flex items-end">

                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800">

                        <span class="material-icons text-lg">
                            filter_alt
                        </span>

                        Tampilkan

                    </button>

                </div>

            </div>

        </form>


        {{-- ======================================== --}}
        {{-- INFO MINGGU --}}
        {{-- ======================================== --}}

        <!-- <div class="rounded-2xl border border-red-100 bg-red-50 p-5">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm font-medium text-red-600">
                        Periode Jadwal
                    </p>

                    <h2 class="mt-1 text-lg font-bold text-gray-900">

                        {{ $weekStart->translatedFormat('d F Y') }}

                        -

                        {{ $weekEnd->translatedFormat('d F Y') }}

                    </h2>

                </div>


                <div class="flex flex-wrap items-center gap-2">

                    @if($weeklySchedule)

                        @if($weeklySchedule->status === 'published')

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 text-xs font-semibold text-green-700">

                                <span class="material-icons text-sm">
                                    check_circle
                                </span>

                                Dipublikasikan

                            </span>

                        @else

                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-4 py-2 text-xs font-semibold text-yellow-700">

                                <span class="material-icons text-sm">
                                    edit
                                </span>

                                Draft

                            </span>


                            <form method="POST" action="{{ route('admin.schedules.publish', $weeklySchedule) }}"
                                onsubmit="return confirm('Publikasikan jadwal minggu ini? Setelah dipublikasikan, jadwal akan dapat digunakan oleh karyawan.')">

                                @csrf

                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2 text-xs font-semibold text-white hover:bg-green-700">

                                    <span class="material-icons text-sm">
                                        publish
                                    </span>

                                    Publikasikan

                                </button>

                            </form>

                        @endif

                    @else

                        <span class="inline-flex rounded-full bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-600">
                            Belum dibuat
                        </span>

                    @endif

                </div>

            </div>

        </div> -->


        {{-- ======================================== --}}
        {{-- JADWAL --}}
        {{-- ======================================== --}}

        <div class="space-y-4">
            @foreach($days as $day)
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    {{-- DAY HEADER --}}
                    <div
                        class="flex flex-col gap-3 border-b border-gray-200 bg-gray-50 p-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                                {{ $day['date']->translatedFormat('l') }}
                            </p>
                            <h3 class="mt-1 text-lg font-bold text-gray-900">
                                {{ $day['date']->translatedFormat('d F Y') }}
                            </h3>
                        </div>
                        <button type="button" onclick="openScheduleModal('{{ $day['date']->format('Y-m-d') }}')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <span class="material-icons text-lg">
                                add
                            </span>
                            Tambah
                        </button>

                    </div>
                    {{-- ASSIGNMENTS --}}
                    @if($day['assignments']->count())
                        <div class="divide-y divide-gray-100">
                            @foreach($day['assignments'] as $assignment)
                                <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex min-w-0 items-center gap-4">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50">
                                            <span class="material-icons text-red-600">
                                                person
                                            </span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-gray-900">
                                                {{ $assignment->user->name }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ $assignment->user->email }}
                                            </p>
                                        </div>

                                    </div>


                                    <div class="flex flex-wrap items-center gap-3">

                                        {{-- SCHEDULED --}}
                                        @if($assignment->status === 'scheduled')

                                                <div class="rounded-xl bg-blue-50 px-4 py-2">

                                                    <p class="text-xs text-blue-500">
                                                        Jam Kerja
                                                    </p>

                                                    <p class="font-semibold text-blue-700">

                                                        {{ $assignment->start_time
                                            ? \Carbon\Carbon::parse($assignment->start_time)->format('H:i')
                                            : '--:--'
                                                                                                                                                                                            }}

                                                        -

                                                        {{ $assignment->end_time
                                            ? \Carbon\Carbon::parse($assignment->end_time)->format('H:i')
                                            : '--:--'
                                                                                                                                                                                            }}

                                                    </p>

                                                </div>


                                                {{-- OFF --}}
                                        @elseif($assignment->status === 'off')

                                            <span class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600">

                                                <span class="material-icons mr-1 align-middle text-sm">
                                                    event_busy
                                                </span>

                                                OFF

                                            </span>


                                            {{-- LEAVE --}}
                                        @elseif($assignment->status === 'leave')

                                            <span class="rounded-xl bg-purple-50 px-4 py-2 text-sm font-medium text-purple-600">

                                                <span class="material-icons mr-1 align-middle text-sm">
                                                    event_available
                                                </span>

                                                IZIN

                                            </span>


                                            {{-- STATUS LAIN --}}
                                        @else

                                            <span class="rounded-xl bg-gray-100 px-4 py-2 text-sm font-medium text-gray-600">
                                                {{ ucfirst($assignment->status ?? '-') }}
                                            </span>

                                        @endif


                                        {{-- DELETE --}}
                                        <form method="POST" action="{{ route('admin.schedules.destroy', $assignment) }}"
                                            onsubmit="return confirm('Hapus jadwal ini?')">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="flex h-10 w-10 items-center justify-center rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-600">

                                                <span class="material-icons">
                                                    delete
                                                </span>

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="p-8 text-center">

                            <span class="material-icons text-4xl text-gray-300">
                                event_busy
                            </span>

                            <p class="mt-2 text-sm font-medium text-gray-500">
                                Belum ada jadwal
                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                Belum ada karyawan yang dijadwalkan pada hari ini.
                            </p>

                        </div>

                    @endif

                </div>

            @endforeach

        </div>

    </div>


    {{-- ======================================== --}}
    {{-- MODAL TAMBAH JADWAL --}}
    {{-- ======================================== --}}

    <div id="scheduleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="flex max-h-[90vh] w-full max-w-lg flex-col overflow-hidden rounded-2xl bg-white shadow-xl"
            onclick="event.stopPropagation()">
            {{-- HEADER --}}
            <div class="flex shrink-0 items-center justify-between border-b border-gray-200 p-5">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">
                        Tambah Jadwal
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Tambahkan jadwal kerja karyawan.
                    </p>
                </div>
                <button type="button" onclick="closeScheduleModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <span class="material-icons">
                        close
                    </span>
                </button>
            </div>
            {{-- FORM --}}
            <form method="POST" action="{{ auth()->user()->role === 'pic'
        ? route('pic.schedules.store')
        : route('admin.schedules.store') }}" class="flex min-h-0 flex-1 flex-col">
                @csrf
                {{-- AREA SCROLL --}}
                <div class="min-h-0 flex-1 overflow-y-auto p-5">
                    <div class="space-y-5">
                        {{-- CABANG --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Cabang
                            </label>
                            <select name="branch_id" id="modalBranch" required
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                                {{ auth()->user()->role === 'pic' ? 'disabled' : '' }}>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->code }} - {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if(auth()->user()->role === 'pic')
                                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                            @endif
                        </div>
                        {{-- KARYAWAN --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Karyawan
                            </label>

                            <select name="user_id" required
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

                                <option value="">
                                    Pilih Karyawan
                                </option>

                                @foreach($employees as $employee)

                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>
                        {{-- TANGGAL --}}
                        <div class="w-full">

                            <label for="workDate" class="mb-2 block text-sm font-medium text-gray-700">
                                Tanggal Kerja
                            </label>

                            <input type="date" name="work_date" id="workDate" value="{{ old('work_date') }}" required
                                class="block h-12 w-full min-w-0 appearance-none rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-500/20">

                        </div>
                        {{-- JAM MASUK --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Jam Masuk
                            </label>
                            <input type="text" name="start_time" id="startTime" placeholder="07:00" maxlength="5"
                                autocomplete="off"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">
                            <p class="mt-1 text-xs text-gray-400">
                                Format 24 jam, contoh: 07:00
                            </p>
                        </div>
                        {{-- JAM PULANG --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Jam Pulang
                            </label>

                            <input type="text" name="end_time" id="endTime" placeholder="15:00" maxlength="5"
                                autocomplete="off"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

                            <p class="mt-1 text-xs text-gray-400">
                                Format 24 jam, contoh: 15:00
                            </p>
                        </div>
                        {{-- STATUS --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Status
                            </label>
                            <select name="status" required
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500">

                                <option value="scheduled">
                                    Terjadwal
                                </option>
                                <option value="off">
                                    Off
                                </option>
                            </select>

                        </div>
                        {{-- CATATAN --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Catatan
                            </label>

                            <textarea name="notes" rows="3" placeholder="Opsional..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"></textarea>

                        </div>

                    </div>

                </div>
                {{-- BUTTON --}}
                <div class="shrink-0 border-t border-gray-200 bg-white p-5">
                    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button" onclick="closeScheduleModal()"
                            class="rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">
                            Simpan Jadwal
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>


    {{-- ======================================== --}}
    {{-- JAVASCRIPT MODAL --}}
    {{-- ======================================== --}}

    <script>

        function toggleShiftRequired() {
            const status = document.querySelector('[name="status"]');
            const shift = document.querySelector('#modalShift');

            if (!status || !shift) {
                return;
            }

            if (status.value === 'scheduled') {
                shift.required = true;
                shift.disabled = false;
            } else {
                shift.required = false;
                shift.disabled = true;
                shift.value = '';
            }

        }

        document.addEventListener('DOMContentLoaded', function () {
            const status = document.querySelector('[name="status"]');
            if (status) {
                status.addEventListener('change', toggleShiftRequired);
                toggleShiftRequired();
            }

        });

        function openScheduleModal(date = null) {
            const modal = document.getElementById('scheduleModal');
            const dateInput = document.getElementById('workDate');
            if (date) {
                dateInput.value = date;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }


        function closeScheduleModal() {
            const modal = document.getElementById('scheduleModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }


        document.getElementById('scheduleModal').addEventListener('click', function (event) {
            if (event.target === this) {
                closeScheduleModal();
            }
        });


        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeScheduleModal();
            }
        });

    </script>

@endsection