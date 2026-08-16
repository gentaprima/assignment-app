@extends('components.dashboard-layout')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Persetujuan Izin
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Kelola dan tinjau pengajuan izin seluruh karyawan.
            </p>
        </div>

    </div>


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        {{-- Pending --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Menunggu Persetujuan
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $permissions->where('status', 'pending')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-50">
                    <span class="material-icons text-yellow-600">
                        pending_actions
                    </span>
                </div>

            </div>

        </div>


        {{-- Approved --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Disetujui
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $permissions->where('status', 'approved')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50">
                    <span class="material-icons text-green-600">
                        check_circle
                    </span>
                </div>

            </div>

        </div>


        {{-- Rejected --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-gray-500">
                        Ditolak
                    </p>

                    <p class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $permissions->where('status', 'rejected')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50">
                    <span class="material-icons text-red-600">
                        cancel
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- HEADER TABLE --}}
        <div class="border-b border-gray-200 p-5">

            <h2 class="font-bold text-gray-900">
                Daftar Pengajuan Izin
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Seluruh pengajuan izin karyawan.
            </p>

        </div>


        {{-- DESKTOP TABLE --}}
        <div class="hidden overflow-x-auto md:block">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Karyawan
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Cabang
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Jenis Izin
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Tanggal
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($permissions as $permission)

                        <tr class="hover:bg-gray-50">

                            {{-- KARYAWAN --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $permission->user?->name ?? '-' }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $permission->user?->email ?? '-' }}
                                </div>

                            </td>


                            {{-- CABANG --}}
                            <td class="px-6 py-4">

                                <div class="text-sm font-medium text-gray-900">
                                    {{ $permission->branch?->name ?? '-' }}
                                </div>

                                @if($permission->branch?->code)
                                    <div class="text-xs text-gray-500">
                                        {{ $permission->branch->code }}
                                    </div>
                                @endif

                            </td>


                            {{-- JENIS --}}
                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $permission->leaveType?->name ?? '-' }}
                                </div>

                                @if($permission->leaveType?->code)
                                    <div class="text-xs text-gray-500">
                                        {{ $permission->leaveType->code }}
                                    </div>
                                @endif

                            </td>


                            {{-- TANGGAL --}}
                            <td class="px-6 py-4 text-sm text-gray-700">

                                {{ $permission->start_date?->format('d M Y') }}

                                @if(
                                    $permission->start_date &&
                                    $permission->end_date &&
                                    $permission->start_date->ne($permission->end_date)
                                )

                                    <div class="text-xs text-gray-500">
                                        s/d {{ $permission->end_date->format('d M Y') }}
                                    </div>

                                @endif

                            </td>


                            {{-- STATUS --}}
                            <td class="px-6 py-4">

                                @if($permission->status === 'pending')

                                    <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Menunggu
                                    </span>

                                @elseif($permission->status === 'approved')

                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        Disetujui
                                    </span>

                                @elseif($permission->status === 'rejected')

                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                        Ditolak
                                    </span>

                                @else

                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                        {{ ucfirst($permission->status) }}
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-right">

                                <button
                                    type="button"
                                    onclick="openPermissionModal({{ $permission->id }})"
                                    class="rounded-lg px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50"
                                >
                                    Detail
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-12 text-center"
                            >

                                <span class="material-icons text-4xl text-gray-300">
                                    event_busy
                                </span>

                                <p class="mt-3 text-sm font-medium text-gray-900">
                                    Belum ada pengajuan izin
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Pengajuan izin karyawan akan muncul di sini.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- MOBILE --}}
        <div class="divide-y divide-gray-100 md:hidden">

            @forelse($permissions as $permission)

                <div class="space-y-4 p-5">

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="font-semibold text-gray-900">
                                {{ $permission->user?->name ?? '-' }}
                            </p>

                            <p class="text-sm text-gray-500">
                                {{ $permission->branch?->name ?? '-' }}
                            </p>

                        </div>


                        @if($permission->status === 'pending')

                            <span class="shrink-0 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700">
                                Menunggu
                            </span>

                        @elseif($permission->status === 'approved')

                            <span class="shrink-0 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                Disetujui
                            </span>

                        @elseif($permission->status === 'rejected')

                            <span class="shrink-0 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                Ditolak
                            </span>

                        @endif

                    </div>


                    <div>

                        <p class="text-xs text-gray-500">
                            Jenis Izin
                        </p>

                        <p class="font-medium text-gray-900">
                            {{ $permission->leaveType?->name ?? '-' }}
                        </p>

                    </div>


                    <div>

                        <p class="text-xs text-gray-500">
                            Tanggal
                        </p>

                        <p class="text-sm text-gray-900">

                            {{ $permission->start_date?->format('d M Y') }}

                            @if(
                                $permission->start_date &&
                                $permission->end_date &&
                                $permission->start_date->ne($permission->end_date)
                            )
                                - {{ $permission->end_date->format('d M Y') }}
                            @endif

                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="openPermissionModal({{ $permission->id }})"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                    >
                        Lihat Detail
                    </button>

                </div>

            @empty

                <div class="p-10 text-center text-sm text-gray-500">
                    Belum ada pengajuan izin.
                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- MODAL DETAIL --}}
@foreach($permissions as $permission)

    <div
        id="permissionModal{{ $permission->id }}"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
    >

        <div
            class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-2xl bg-white shadow-xl"
        >

            {{-- HEADER --}}
            <div class="flex items-center justify-between border-b border-gray-200 p-5">

                <div>

                    <h3 class="font-bold text-gray-900">
                        Detail Pengajuan
                    </h3>

                    <p class="text-sm text-gray-500">
                        {{ $permission->user?->name ?? '-' }}
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closePermissionModal({{ $permission->id }})"
                    class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"
                >
                    <span class="material-icons">
                        close
                    </span>
                </button>

            </div>


            {{-- CONTENT --}}
            <div class="space-y-5 p-5">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p class="text-xs text-gray-500">
                            Jenis Izin
                        </p>

                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $permission->leaveType?->name ?? '-' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-500">
                            Status
                        </p>

                        <p class="mt-1 font-semibold text-gray-900">
                            {{ ucfirst($permission->status) }}
                        </p>
                    </div>

                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Cabang
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $permission->branch?->name ?? '-' }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-medium text-gray-900">
                        {{ $permission->start_date?->format('d M Y') }}
                        -
                        {{ $permission->end_date?->format('d M Y') }}
                    </p>
                </div>


                <div>
                    <p class="text-xs text-gray-500">
                        Alasan
                    </p>

                    <p class="mt-1 whitespace-pre-line text-sm text-gray-700">
                        {{ $permission->reason ?: '-' }}
                    </p>
                </div>


                @if($permission->attachment)

                    <div>

                        <p class="text-xs text-gray-500">
                            Lampiran
                        </p>

                        <a
                            href="{{ asset('storage/' . $permission->attachment) }}"
                            target="_blank"
                            class="mt-2 inline-flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-600 hover:bg-blue-100"
                        >
                            <span class="material-icons text-sm">
                                attachment
                            </span>

                            Lihat Lampiran

                        </a>

                    </div>

                @endif


                @if($permission->rejection_reason)

                    <div class="rounded-xl bg-red-50 p-4">

                        <p class="text-xs font-semibold text-red-700">
                            Alasan Penolakan
                        </p>

                        <p class="mt-1 text-sm text-red-600">
                            {{ $permission->rejection_reason }}
                        </p>

                    </div>

                @endif

            </div>


            {{-- FOOTER --}}
            <div class="flex flex-col gap-3 border-t border-gray-200 p-5 sm:flex-row sm:justify-end">

                @if($permission->status === 'pending')

                    <form
                        method="POST"
                        action="{{ route('admin.leave-requests.reject', $permission) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl border border-red-200 px-5 py-3 text-sm font-semibold text-red-600 hover:bg-red-50 sm:w-auto"
                        >
                            Tolak
                        </button>

                    </form>


                    <form
                        method="POST"
                        action="{{ route('admin.leave-requests.approve', $permission) }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 sm:w-auto"
                        >
                            Setujui
                        </button>

                    </form>

                @endif


                <button
                    type="button"
                    onclick="closePermissionModal({{ $permission->id }})"
                    class="rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

@endforeach


<script>

function openPermissionModal(id)
{
    const modal = document.getElementById('permissionModal' + id);

    if (!modal) return;

    modal.classList.remove('hidden');

    modal.classList.add('flex');

    document.body.classList.add('overflow-hidden');
}


function closePermissionModal(id)
{
    const modal = document.getElementById('permissionModal' + id);

    if (!modal) return;

    modal.classList.add('hidden');

    modal.classList.remove('flex');

    document.body.classList.remove('overflow-hidden');
}


// Klik background untuk menutup
document.addEventListener('click', function(event)
{
    if (
        event.target.id &&
        event.target.id.startsWith('permissionModal')
    ) {

        const id = event.target.id.replace(
            'permissionModal',
            ''
        );

        closePermissionModal(id);
    }
});

</script>

@endsection