@extends('components.dashboard-layout')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            Profil Saya
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            Kelola informasi akun dan keamanan profil Anda.
        </p>
    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4">

            <span class="material-icons text-green-600">
                check_circle
            </span>

            <div>

                <p class="font-semibold text-green-800">
                    Berhasil
                </p>

                <p class="text-sm text-green-700">
                    {{ session('success') }}
                </p>

            </div>

        </div>

    @endif


    {{-- PASSWORD SUCCESS --}}
    @if(session('password_success'))

        <div class="flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4">

            <span class="material-icons text-green-600">
                check_circle
            </span>

            <div>

                <p class="font-semibold text-green-800">
                    Berhasil
                </p>

                <p class="text-sm text-green-700">
                    {{ session('password_success') }}
                </p>

            </div>

        </div>

    @endif


    {{-- VALIDATION ERROR --}}
    @if($errors->any())

        <div class="rounded-2xl border border-red-200 bg-red-50 p-4">

            <div class="flex gap-3">

                <span class="material-icons text-red-600">
                    error
                </span>

                <div>

                    <p class="font-semibold text-red-800">
                        Terjadi kesalahan
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-red-700">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- PROFILE INFORMATION --}}
    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- PROFILE HEADER --}}
        <div class="border-b border-gray-200 bg-gradient-to-r from-red-600 to-red-500 px-6 py-8 text-white sm:px-8">

            <div class="flex flex-col items-center gap-5 sm:flex-row">

                {{-- AVATAR --}}
                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full border-4 border-white/30 bg-white/20">

                    <span class="text-3xl font-bold">

                        {{ strtoupper(substr($user->name, 0, 1)) }}

                    </span>

                </div>


                {{-- USER INFO --}}
                <div class="text-center sm:text-left">

                    <h2 class="text-2xl font-bold">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-1 text-sm text-red-100">
                        {{ $user->email }}
                    </p>


                    <div class="mt-3 flex flex-wrap justify-center gap-2 sm:justify-start">

                        {{-- ROLE --}}
                        <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">

                            {{ ucfirst($user->role) }}

                        </span>


                        {{-- BRANCH --}}
                        @if($user->branch)

                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">

                                {{ $user->branch->name }}

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- PROFILE FORM --}}
        <div class="p-6 sm:p-8">

            <div class="mb-6">

                <h3 class="text-lg font-bold text-gray-900">
                    Informasi Pribadi
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi dasar akun Anda.
                </p>

            </div>


            <form
                method="POST"
                action="{{ route('profile.update') }}"
                class="space-y-5"
            >

                @csrf
                @method('PATCH')


                {{-- NAMA --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Lengkap
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            person
                        </span>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            placeholder="Nama lengkap"
                        >

                    </div>

                    @error('name')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- EMAIL --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            email
                        </span>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            placeholder="Email"
                        >

                    </div>

                    @error('email')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- PHONE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nomor HP
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            phone
                        </span>

                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            placeholder="08xxxxxxxxxx"
                        >

                    </div>

                    @error('phone')

                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ROLE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Role
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            badge
                        </span>

                        <input
                            type="text"
                            value="{{ ucfirst($user->role) }}"
                            readonly
                            class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-500"
                        >

                    </div>

                </div>


                {{-- CABANG --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Cabang
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            store
                        </span>

                        <input
                            type="text"
                            value="{{ $user->branch?->name ?? 'Tidak memiliki cabang' }}"
                            readonly
                            class="w-full cursor-not-allowed rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-500"
                        >

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="flex justify-end border-t border-gray-100 pt-5">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700 active:bg-red-800"
                    >

                        <span class="material-icons text-[20px]">
                            save
                        </span>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- PASSWORD --}}
    <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 p-6 sm:p-8">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50">

                    <span class="material-icons text-red-600">
                        lock
                    </span>

                </div>

                <div>

                    <h3 class="font-bold text-gray-900">
                        Keamanan Akun
                    </h3>

                    <p class="text-sm text-gray-500">
                        Ubah password untuk menjaga keamanan akun.
                    </p>

                </div>

            </div>

        </div>


        <div class="p-6 sm:p-8">

            <form
                method="POST"
                action="{{ route('profile.password.update') }}"
                class="space-y-5"
            >

                @csrf
                @method('PUT')


                {{-- PASSWORD LAMA --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Password Saat Ini
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            lock
                        </span>

                        <input
                            type="password"
                            name="current_password"
                            required
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            placeholder="Password saat ini"
                        >

                    </div>

                </div>


                {{-- PASSWORD BARU --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Password Baru
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            lock_reset
                        </span>

                        <input
                            type="password"
                            name="password"
                            required
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            placeholder="Password baru"
                        >

                    </div>

                </div>


                {{-- KONFIRMASI --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Konfirmasi Password Baru
                    </label>

                    <div class="relative">

                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            lock_reset
                        </span>

                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full rounded-xl border border-gray-300 py-3 pl-11 pr-4 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
                            placeholder="Ulangi password baru"
                        >

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="flex justify-end border-t border-gray-100 pt-5">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-gray-800"
                    >

                        <span class="material-icons text-[20px]">
                            lock
                        </span>

                        Ubah Password

                    </button>

                </div>

            </form>

        </div>

    </section>


    {{-- ACCOUNT INFO --}}
    <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="p-6 sm:p-8">

            <h3 class="font-bold text-gray-900">
                Informasi Akun
            </h3>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-500">
                        ID Pengguna
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        #{{ $user->id }}
                    </p>

                </div>


                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-500">
                        Bergabung Sejak
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">

                        {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}

                    </p>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection