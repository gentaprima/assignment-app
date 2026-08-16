@extends('components.dashboard-layout')

@section('content')

    <div class="space-y-6">

        {{-- ========================================= --}}
        {{-- HEADER --}}
        {{-- ========================================= --}}
        <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Pengajuan Izin
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    @if(auth()->user()->role === 'admin')
                        Kelola seluruh pengajuan izin karyawan.
                    @elseif(auth()->user()->role === 'pic')
                        Pantau pengajuan izin karyawan di cabang Anda.
                    @else
                        Ajukan izin dan lihat riwayat pengajuan Anda.
                    @endif
                </p>
            </div>


            {{-- BUTTON AJUKAN IZIN --}}
            @if(auth()->user()->role === 'employee')

                <button type="button" onclick="openLeaveModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700">
                    <span class="material-icons text-[20px]">
                        add
                    </span>

                    Ajukan Izin
                </button>

            @endif

        </section>


        {{-- ========================================= --}}
        {{-- FLASH MESSAGE --}}
        {{-- ========================================= --}}

        @if(session('success'))

            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">

                <div class="flex items-start gap-3">

                    <span class="material-icons text-green-600">
                        check_circle
                    </span>

                    <div>

                        <p class="font-semibold text-green-800">
                            Berhasil
                        </p>

                        <p class="mt-1 text-sm text-green-700">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        @if(session('error'))

            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">

                <div class="flex items-start gap-3">

                    <span class="material-icons text-red-600">
                        error
                    </span>

                    <div>

                        <p class="font-semibold text-red-800">
                            Gagal
                        </p>

                        <p class="mt-1 text-sm text-red-700">
                            {{ session('error') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- ========================================= --}}
        {{-- STATISTIK --}}
        {{-- ========================================= --}}

        @php

            $pendingCount = $leaveRequests
                ->where('status', 'pending')
                ->count();

            $approvedCount = $leaveRequests
                ->where('status', 'approved')
                ->count();

            $rejectedCount = $leaveRequests
                ->where('status', 'rejected')
                ->count();

        @endphp


        <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- PENDING --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Menunggu
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $pendingCount }}
                        </p>

                        <p class="mt-1 text-xs text-orange-600">
                            Menunggu diproses
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50">

                        <span class="material-icons text-orange-600">
                            pending_actions
                        </span>

                    </div>

                </div>

            </div>


            {{-- APPROVED --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Disetujui
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $approvedCount }}
                        </p>

                        <p class="mt-1 text-xs text-green-600">
                            Pengajuan disetujui
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">

                        <span class="material-icons text-green-600">
                            check_circle
                        </span>

                    </div>

                </div>

            </div>


            {{-- REJECTED --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Ditolak
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $rejectedCount }}
                        </p>

                        <p class="mt-1 text-xs text-red-600">
                            Pengajuan ditolak
                        </p>

                    </div>


                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">

                        <span class="material-icons text-red-600">
                            cancel
                        </span>

                    </div>

                </div>

            </div>

        </section>


        {{-- ========================================= --}}
        {{-- TABLE --}}
        {{-- ========================================= --}}

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            {{-- HEADER TABLE --}}
            <div class="border-b border-gray-200 p-5">

                <div>

                    <h2 class="text-lg font-bold text-gray-900">
                        Daftar Pengajuan Izin
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">

                        @if(auth()->user()->role === 'admin')

                            Seluruh pengajuan izin karyawan.

                        @elseif(auth()->user()->role === 'pic')

                            Pengajuan izin dari cabang Anda.

                        @else

                            Riwayat pengajuan izin Anda.

                        @endif

                    </p>

                </div>

            </div>


            {{-- ========================================= --}}
            {{-- DESKTOP TABLE --}}
            {{-- ========================================= --}}

            <div class="hidden overflow-x-auto md:block">

                <table class="w-full text-left">

                    <thead class="border-b border-gray-200 bg-gray-50">

                        <tr class="text-xs font-semibold uppercase tracking-wide text-gray-500">

                            @if(auth()->user()->role !== 'employee')

                                <th class="px-5 py-4">
                                    Karyawan
                                </th>

                            @endif


                            @if(auth()->user()->role === 'admin')

                                <th class="px-5 py-4">
                                    Cabang
                                </th>

                            @endif


                            <th class="px-5 py-4">
                                Tanggal
                            </th>

                            <th class="px-5 py-4">
                                Jenis
                            </th>

                            <th class="px-5 py-4">
                                Alasan
                            </th>

                            <th class="px-5 py-4">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($leaveRequests as $leaveRequest)

                            <tr class="text-sm hover:bg-gray-50">

                                {{-- KARYAWAN --}}
                                @if(auth()->user()->role !== 'employee')

                                    <td class="px-5 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50 font-bold text-red-600">

                                                {{ strtoupper(substr($leaveRequest->user?->name ?? '?', 0, 1)) }}

                                            </div>

                                            <div>

                                                <p class="font-semibold text-gray-900">
                                                    {{ $leaveRequest->user?->name ?? '-' }}
                                                </p>

                                                <p class="text-xs text-gray-500">
                                                    {{ $leaveRequest->user?->email ?? '-' }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>

                                @endif


                                {{-- CABANG --}}
                                @if(auth()->user()->role === 'admin')

                                    <td class="px-5 py-4">

                                        <div class="flex items-center gap-2">

                                            <span class="material-icons text-gray-400">
                                                store
                                            </span>

                                            <div>

                                                <p class="font-medium text-gray-900">
                                                    {{ $leaveRequest->branch?->name ?? '-' }}
                                                </p>

                                                @if($leaveRequest->branch?->code)

                                                    <p class="text-xs text-gray-500">
                                                        {{ $leaveRequest->branch->code }}
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </td>

                                @endif


                                {{-- TANGGAL --}}
                                <td class="px-5 py-4">

                                    <p class="font-medium text-gray-900">

                                        {{ $leaveRequest->start_date?->format('d M Y') }}

                                    </p>

                                    @if($leaveRequest->end_date)

                                        <p class="mt-1 text-xs text-gray-500">

                                            s/d {{ $leaveRequest->end_date->format('d M Y') }}

                                        </p>

                                    @endif

                                </td>


                                {{-- JENIS --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-gray-900">
                                        {{ $leaveRequest->leaveType?->name ?? '-' }}
                                    </span>

                                    <!-- @if($leaveRequest->leaveType?->code)
                                        <span class="block text-xs text-gray-500">
                                            {{ $leaveRequest->leaveType->code }}
                                        </span>
                                    @endif -->
                                </td>


                                {{-- ALASAN --}}
                                <td class="max-w-xs px-5 py-4">

                                    <p class="truncate text-gray-700">
                                        {{ $leaveRequest->reason ?: '-' }}
                                    </p>

                                </td>


                                {{-- STATUS --}}
                                <td class="px-5 py-4">

                                    @if($leaveRequest->status === 'pending')

                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">

                                            <span class="material-icons text-[15px]">
                                                schedule
                                            </span>

                                            Menunggu

                                        </span>

                                    @elseif($leaveRequest->status === 'approved')

                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">

                                            <span class="material-icons text-[15px]">
                                                check_circle
                                            </span>

                                            Disetujui

                                        </span>

                                    @elseif($leaveRequest->status === 'rejected')

                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">

                                            <span class="material-icons text-[15px]">
                                                cancel
                                            </span>

                                            Ditolak

                                        </span>

                                    @else

                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">

                                            {{ ucfirst($leaveRequest->status) }}

                                        </span>

                                    @endif

                                </td>


                                {{-- AKSI --}}
                                <td class="px-5 py-4">

                                    <div class="flex justify-end gap-2">

                                        {{-- DETAIL --}}
                                        <button type="button" onclick="showLeaveDetail(
                                                    {{ $leaveRequest->id }},
                                                    @js($leaveRequest->user?->name ?? auth()->user()->name),
                                                    @js($leaveRequest->branch?->name ?? '-'),
                                                    @js($leaveRequest->leaveType?->name ?? '-' ),
                                                    @js($leaveRequest->start_date?->format('d M Y')),
                                                    @js($leaveRequest->end_date?->format('d M Y')),
                                                    @js($leaveRequest->reason ?? '-'),
                                                    @js($leaveRequest->status),
                                                    @js($leaveRequest->rejection_reason ?? ''),
                                                    @js($leaveRequest->attachment ? asset('storage/' . $leaveRequest->attachment) : '')
                                                )"
                                            class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200">

                                            <span class="material-icons text-[16px]">
                                                visibility
                                            </span>

                                            Detail

                                        </button>


                                        {{-- ADMIN ACTION --}}
                                        @if(
                                                auth()->user()->role === 'admin'
                                                && $leaveRequest->status === 'pending'
                                            )

                                            {{-- APPROVE --}}
                                            <button type="button" onclick="approveLeave({{ $leaveRequest->id }})"
                                                class="inline-flex items-center gap-1 rounded-lg bg-green-50 px-3 py-2 text-xs font-semibold text-green-700 hover:bg-green-100">

                                                <span class="material-icons text-[16px]">
                                                    check
                                                </span>

                                                Setujui

                                            </button>


                                            {{-- REJECT --}}
                                            <button type="button" onclick="rejectLeave({{ $leaveRequest->id }})"
                                                class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">

                                                <span class="material-icons text-[16px]">
                                                    close
                                                </span>

                                                Tolak

                                            </button>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="10" class="px-5 py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                                            <span class="material-icons text-3xl text-gray-400">
                                                event_busy
                                            </span>

                                        </div>

                                        <h3 class="mt-4 font-semibold text-gray-900">
                                            Belum ada pengajuan izin
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Belum ada data izin yang tersedia.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ========================================= --}}
            {{-- MOBILE --}}
            {{-- ========================================= --}}

            <div class="divide-y divide-gray-100 md:hidden">

                @forelse($leaveRequests as $leaveRequest)

                    <div class="p-4">

                        {{-- HEADER CARD --}}
                        <div class="flex items-start justify-between gap-3">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50 font-bold text-red-600">

                                    {{ strtoupper(substr($leaveRequest->user?->name ?? auth()->user()->name, 0, 1)) }}

                                </div>

                                <div>

                                    @if(auth()->user()->role !== 'employee')

                                        <p class="font-semibold text-gray-900">
                                            {{ $leaveRequest->user?->name ?? '-' }}
                                        </p>

                                    @else

                                        <p class="font-semibold text-gray-900">
                                            Pengajuan Izin
                                        </p>

                                    @endif

                                    <p class="text-xs text-gray-500">
                                        {{ $leaveRequest->created_at?->format('d M Y H:i') }}
                                    </p>

                                </div>

                            </div>


                            {{-- STATUS --}}
                            @if($leaveRequest->status === 'pending')

                                <span class="shrink-0 rounded-full bg-orange-50 px-2.5 py-1 text-xs font-semibold text-orange-700">
                                    Menunggu
                                </span>

                            @elseif($leaveRequest->status === 'approved')

                                <span class="shrink-0 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                    Disetujui
                                </span>

                            @else

                                <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                    Ditolak
                                </span>

                            @endif

                        </div>


                        {{-- INFO --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">

                            {{-- TANGGAL --}}
                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Tanggal
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">

                                    {{ $leaveRequest->start_date?->format('d M Y') }}

                                </p>

                                @if(
                                        $leaveRequest->end_date
                                        && $leaveRequest->end_date->format('Y-m-d')
                                        !== $leaveRequest->start_date?->format('Y-m-d')
                                    )

                                    <p class="text-xs text-gray-500">

                                        s/d {{ $leaveRequest->end_date->format('d M Y') }}

                                    </p>

                                @endif

                            </div>


                            {{-- JENIS --}}
                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Jenis
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">

                                    {{ $typeLabels[$leaveRequest->type] ?? ucfirst($leaveRequest->type) }}

                                </p>

                            </div>


                            {{-- CABANG --}}
                            @if(auth()->user()->role !== 'employee')

                                <div class="col-span-2 rounded-xl bg-gray-50 p-3">

                                    <p class="text-xs text-gray-500">
                                        Cabang
                                    </p>

                                    <p class="mt-1 text-sm font-semibold text-gray-900">
                                        {{ $leaveRequest->branch?->name ?? '-' }}
                                    </p>

                                </div>

                            @endif


                            {{-- ALASAN --}}
                            <div class="col-span-2 rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Alasan
                                </p>

                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $leaveRequest->reason ?: '-' }}
                                </p>

                            </div>

                        </div>


                        {{-- ACTION --}}
                        <div class="mt-4 flex flex-wrap gap-2">

                            <button type="button" onclick="showLeaveDetail(
                                        {{ $leaveRequest->id }},
                                        @js($leaveRequest->user?->name ?? auth()->user()->name),
                                        @js($leaveRequest->branch?->name ?? '-'),
                                        @js($typeLabels[$leaveRequest->type] ?? ucfirst($leaveRequest->type)),
                                        @js($leaveRequest->start_date?->format('d M Y')),
                                        @js($leaveRequest->end_date?->format('d M Y')),
                                        @js($leaveRequest->reason ?? '-'),
                                        @js($leaveRequest->status),
                                        @js($leaveRequest->rejection_reason ?? ''),
                                        @js($leaveRequest->attachment ? asset('storage/' . $leaveRequest->attachment) : '')
                                    )"
                                class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl bg-gray-100 px-4 py-3 text-xs font-semibold text-gray-700">

                                <span class="material-icons text-[18px]">
                                    visibility
                                </span>

                                Detail

                            </button>


                            @if(
                                    auth()->user()->role === 'admin'
                                    && $leaveRequest->status === 'pending'
                                )

                                <button type="button" onclick="approveLeave({{ $leaveRequest->id }})"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl bg-green-50 px-4 py-3 text-xs font-semibold text-green-700">

                                    <span class="material-icons text-[18px]">
                                        check
                                    </span>

                                    Setujui

                                </button>


                                <button type="button" onclick="rejectLeave({{ $leaveRequest->id }})"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl bg-red-50 px-4 py-3 text-xs font-semibold text-red-700">

                                    <span class="material-icons text-[18px]">
                                        close
                                    </span>

                                    Tolak

                                </button>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="px-5 py-16 text-center">

                        <div class="flex flex-col items-center">

                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">

                                <span class="material-icons text-3xl text-gray-400">
                                    event_busy
                                </span>

                            </div>

                            <h3 class="mt-4 font-semibold text-gray-900">
                                Belum ada pengajuan izin
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Belum ada data izin yang tersedia.
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>


            {{-- PAGINATION --}}
            @if(method_exists($leaveRequests, 'links'))

                <div class="border-t border-gray-200 p-4">
                    {{ $leaveRequests->links() }}
                </div>

            @endif

        </section>

    </div>


    {{-- ========================================= --}}
    {{-- MODAL AJUKAN IZIN --}}
    {{-- ========================================= --}}

    @if(auth()->user()->role === 'employee')

        <div id="leaveModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">

            {{-- BACKDROP --}}
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeLeaveModal()"></div>


            {{-- MODAL --}}
            <div class="relative flex min-h-screen items-center justify-center p-4">

                <div class="relative w-full max-w-lg rounded-2xl bg-white shadow-2xl">

                    {{-- HEADER --}}
                    <div class="flex items-center justify-between border-b border-gray-200 p-5">

                        <div>

                            <h2 class="text-lg font-bold text-gray-900">
                                Ajukan Izin
                            </h2>

                            <p class="mt-1 text-xs text-gray-500">
                                Isi data pengajuan izin Anda.
                            </p>

                        </div>


                        <button type="button" onclick="closeLeaveModal()"
                            class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600">

                            <span class="material-icons">
                                close
                            </span>

                        </button>

                    </div>


                    {{-- FORM --}}
                    <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data"
                        class="space-y-5 p-5">

                        @csrf


                        {{-- JENIS --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Jenis Izin
                            </label>

                            <select name="leave_type_id" required class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                       focus:border-red-500 focus:ring-red-500">

                                <option value="">
                                    Pilih Jenis Izin
                                </option>

                                @foreach($leaveTypes as $leaveType)

                                    <option value="{{ $leaveType->id }}" {{ old('leave_type_id') == $leaveType->id ? 'selected' : '' }}>
                                        {{ $leaveType->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('leave_type_id')
                                <p class="mt-1 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>


                        {{-- TANGGAL --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div>

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Tanggal Mulai
                                </label>

                                <input type="date" name="start_date" min="{{ now()->format('Y-m-d') }}" required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">

                            </div>


                            <div>

                                <label class="mb-2 block text-sm font-medium text-gray-700">
                                    Tanggal Selesai
                                </label>

                                <input type="date" name="end_date" min="{{ now()->format('Y-m-d') }}" required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100">

                            </div>

                        </div>


                        {{-- ALASAN --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Alasan
                            </label>

                            <textarea name="reason" rows="4" required maxlength="1000"
                                placeholder="Jelaskan alasan pengajuan izin..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100"></textarea>

                        </div>


                        {{-- ATTACHMENT --}}
                        <div>

                            <label class="mb-2 block text-sm font-medium text-gray-700">
                                Lampiran
                                <span class="font-normal text-gray-400">
                                    (opsional)
                                </span>
                            </label>

                            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf"
                                class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-600 hover:file:bg-red-100">

                            <p class="mt-1 text-xs text-gray-400">
                                JPG, PNG, atau PDF. Maksimal 2 MB.
                            </p>

                        </div>


                        {{-- BUTTON --}}
                        <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">

                            <button type="button" onclick="closeLeaveModal()"
                                class="rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                Batal
                            </button>


                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">

                                <span class="material-icons text-[20px]">
                                    send
                                </span>

                                Ajukan Izin

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================= --}}
    {{-- FORM APPROVE --}}
    {{-- ========================================= --}}

    @if(auth()->user()->role === 'admin')

        <form id="approveLeaveForm" method="POST" class="hidden">
            @csrf
        </form>


        <form id="rejectLeaveForm" method="POST" class="hidden">
            @csrf

            <input type="hidden" name="rejection_reason" id="rejectionReason">

        </form>

    @endif


    {{-- ========================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================= --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | OPEN LEAVE MODAL
        |--------------------------------------------------------------------------
        */

        function openLeaveModal() {

            const modal = document.getElementById('leaveModal');

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | CLOSE LEAVE MODAL
        |--------------------------------------------------------------------------
        */

        function closeLeaveModal() {

            const modal = document.getElementById('leaveModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | DETAIL
        |--------------------------------------------------------------------------
        */

        function showLeaveDetail(
            id,
            employee,
            branch,
            type,
            startDate,
            endDate,
            reason,
            status,
            rejectionReason,
            attachment
        ) {

            let statusHtml = '';

            if (status === 'pending') {

                statusHtml = `
                    <span class="inline-flex rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                        Menunggu
                    </span>
                `;

            } else if (status === 'approved') {

                statusHtml = `
                    <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        Disetujui
                    </span>
                `;

            } else {

                statusHtml = `
                    <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                        Ditolak
                    </span>
                `;

            }


            let attachmentHtml = '';

            if (attachment) {

                attachmentHtml = `
                    <div class="mt-4 rounded-xl bg-blue-50 p-4">

                        <p class="text-xs font-semibold text-blue-700">
                            Lampiran
                        </p>

                        <a
                            href="${attachment}"
                            target="_blank"
                            class="mt-2 inline-flex items-center gap-2 rounded-lg bg-white px-3 py-2 text-sm font-semibold text-blue-600 shadow-sm"
                        >

                            <span class="material-icons text-[18px]">
                                attachment
                            </span>

                            Lihat Lampiran

                        </a>

                    </div>
                `;

            }


            let rejectionHtml = '';

            if (status === 'rejected' && rejectionReason) {

                rejectionHtml = `
                    <div class="mt-4 rounded-xl bg-red-50 p-4">

                        <p class="text-xs font-semibold text-red-700">
                            Alasan Penolakan
                        </p>

                        <p class="mt-1 text-sm text-red-800">
                            ${escapeHtml(rejectionReason)}
                        </p>

                    </div>
                `;

            }


            Swal.fire({

                title: 'Detail Pengajuan Izin',

                width: 600,

                html: `

                    <div class="text-left">

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Karyawan
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ${escapeHtml(employee)}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Cabang
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ${escapeHtml(branch)}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Jenis
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ${escapeHtml(type)}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Status
                                </p>

                                <div class="mt-1">
                                    ${statusHtml}
                                </div>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Mulai
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ${escapeHtml(startDate)}
                                </p>

                            </div>


                            <div class="rounded-xl bg-gray-50 p-3">

                                <p class="text-xs text-gray-500">
                                    Selesai
                                </p>

                                <p class="mt-1 font-semibold text-gray-900">
                                    ${escapeHtml(endDate)}
                                </p>

                            </div>

                        </div>


                        <div class="mt-4 rounded-xl bg-gray-50 p-4">

                            <p class="text-xs text-gray-500">
                                Alasan
                            </p>

                            <p class="mt-1 whitespace-pre-line text-sm text-gray-800">
                                ${escapeHtml(reason)}
                            </p>

                        </div>


                        ${attachmentHtml}

                        ${rejectionHtml}

                    </div>
                `,

                confirmButtonText: 'Tutup',

                confirmButtonColor: '#374151',

            });

        }


        /*
        |--------------------------------------------------------------------------
        | APPROVE
        |--------------------------------------------------------------------------
        */

        function approveLeave(id) {

            Swal.fire({

                title: 'Setujui Pengajuan?',

                text: 'Pengajuan izin ini akan disetujui.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonText: 'Ya, Setujui',

                cancelButtonText: 'Batal',

                confirmButtonColor: '#16a34a',

                cancelButtonColor: '#6b7280',

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                const form = document.getElementById('approveLeaveForm');

                form.action =
                    `/admin/leave-requests/${id}/approve`;

                form.submit();

            });

        }


        /*
        |--------------------------------------------------------------------------
        | REJECT
        |--------------------------------------------------------------------------
        */

        function rejectLeave(id) {

            Swal.fire({

                title: 'Tolak Pengajuan?',

                input: 'textarea',

                inputLabel: 'Alasan Penolakan',

                inputPlaceholder: 'Masukkan alasan penolakan...',

                inputAttributes: {
                    'aria-label': 'Alasan penolakan'
                },

                showCancelButton: true,

                confirmButtonText: 'Tolak Pengajuan',

                cancelButtonText: 'Batal',

                confirmButtonColor: '#dc2626',

                cancelButtonColor: '#6b7280',

                inputValidator: (value) => {

                    if (!value || !value.trim()) {

                        return 'Alasan penolakan wajib diisi.';

                    }

                }

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }

                const form =
                    document.getElementById('rejectLeaveForm');

                const reason =
                    document.getElementById('rejectionReason');

                reason.value = result.value;

                form.action =
                    `/admin/leave-requests/${id}/reject`;

                form.submit();

            });

        }


        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(value) {

            if (value === null || value === undefined) {
                return '';
            }

            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');

        }


        /*
        |--------------------------------------------------------------------------
        | DATE VALIDATION
        |--------------------------------------------------------------------------
        */

        document.addEventListener('DOMContentLoaded', function () {

            const startDate =
                document.querySelector('input[name="start_date"]');

            const endDate =
                document.querySelector('input[name="end_date"]');


            if (startDate && endDate) {

                startDate.addEventListener('change', function () {

                    endDate.min = this.value;

                    if (
                        endDate.value &&
                        endDate.value < this.value
                    ) {

                        endDate.value = this.value;

                    }

                });

            }

        });

    </script>

@endsection