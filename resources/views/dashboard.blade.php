<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tomoro Absensi') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons"
        rel="stylesheet"
    >
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <div
        x-data="{ sidebarOpen: false }"
        class="min-h-screen"
    >

        {{-- Mobile Overlay --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        ></div>

        {{-- Sidebar --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >

            {{-- Logo --}}
            <div class="h-20 flex items-center px-6 border-b border-gray-100">

                <div class="flex items-center gap-3">

                    <div class="w-11 h-11 rounded-xl bg-red-500 flex items-center justify-center shadow-sm">
                        <span class="material-icons text-white">
                            coffee
                        </span>
                    </div>

                    <div>
                        <div class="font-bold text-gray-900 text-lg">
                            TOMORO
                        </div>

                        <div class="text-xs text-gray-500">
                            Absensi
                        </div>
                    </div>

                </div>

            </div>


            {{-- Navigation --}}
            <nav class="p-4 space-y-1">

                <a
                    href="{{ route('employee.dashboard') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl bg-red-50 text-red-600 font-medium"
                >
                    <span class="material-icons text-[22px]">
                        dashboard
                    </span>

                    <span>
                        Dashboard
                    </span>
                </a>


                <a
                    href="#"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                >
                    <span class="material-icons text-[22px]">
                        calendar_month
                    </span>

                    <span>
                        Jadwal Saya
                    </span>
                </a>


                <a
                    href="#"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                >
                    <span class="material-icons text-[22px]">
                        location_on
                    </span>

                    <span>
                        Absensi
                    </span>
                </a>


                <a
                    href="#"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                >
                    <span class="material-icons text-[22px]">
                        event_busy
                    </span>

                    <span>
                        Izin
                    </span>
                </a>


                <a
                    href="#"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                >
                    <span class="material-icons text-[22px]">
                        edit_note
                    </span>

                    <span>
                        Perbaikan Absen
                    </span>
                </a>


                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-100 transition"
                >
                    <span class="material-icons text-[22px]">
                        person
                    </span>

                    <span>
                        Profil
                    </span>
                </a>

            </nav>


            {{-- Logout --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100">

                <form method="POST" action="{{ route('logout') }}">

                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition"
                    >

                        <span class="material-icons text-[22px]">
                            logout
                        </span>

                        <span>
                            Keluar
                        </span>

                    </button>

                </form>

            </div>

        </aside>


        {{-- Main --}}
        <div class="lg:pl-64">

            {{-- Topbar --}}
            <header class="sticky top-0 z-30 h-20 bg-white border-b border-gray-200">

                <div class="h-full px-4 sm:px-6 flex items-center justify-between">

                    {{-- Mobile Menu --}}
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden w-11 h-11 rounded-xl hover:bg-gray-100 flex items-center justify-center"
                    >

                        <span class="material-icons">
                            menu
                        </span>

                    </button>


                    {{-- Desktop Title --}}
                    <div class="hidden lg:block">

                        <h1 class="text-xl font-semibold text-gray-900">
                            Dashboard
                        </h1>

                        <p class="text-sm text-gray-500">
                            Sistem Absensi Karyawan
                        </p>

                    </div>


                    {{-- User --}}
                    <div class="flex items-center gap-3">

                        <button
                            class="w-11 h-11 rounded-full hover:bg-gray-100 flex items-center justify-center relative"
                        >

                            <span class="material-icons text-gray-600">
                                notifications
                            </span>

                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>

                        </button>


                        <div class="hidden sm:block text-right">

                            <div class="font-medium text-sm text-gray-900">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-xs text-gray-500 capitalize">
                                {{ auth()->user()->role }}
                            </div>

                        </div>


                        <div class="w-11 h-11 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                    </div>

                </div>

            </header>


            {{-- Content --}}
            <main class="p-4 sm:p-6 lg:p-8 pb-24 lg:pb-8">

                {{ $slot }}

            </main>

        </div>


        {{-- Mobile Bottom Navigation --}}
        <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 lg:hidden">

            <div class="grid grid-cols-4 h-16">

                <a
                    href="{{ route('employee.dashboard') }}"
                    class="flex flex-col items-center justify-center text-red-500"
                >

                    <span class="material-icons text-[22px]">
                        home
                    </span>

                    <span class="text-[10px] mt-1">
                        Home
                    </span>

                </a>


                <a
                    href="#"
                    class="flex flex-col items-center justify-center text-gray-500"
                >

                    <span class="material-icons text-[22px]">
                        calendar_month
                    </span>

                    <span class="text-[10px] mt-1">
                        Jadwal
                    </span>

                </a>


                <a
                    href="#"
                    class="flex flex-col items-center justify-center text-gray-500"
                >

                    <span class="material-icons text-[22px]">
                        location_on
                    </span>

                    <span class="text-[10px] mt-1">
                        Absensi
                    </span>

                </a>


                <a
                    href="{{ route('profile.edit') }}"
                    class="flex flex-col items-center justify-center text-gray-500"
                >

                    <span class="material-icons text-[22px]">
                        person
                    </span>

                    <span class="text-[10px] mt-1">
                        Profil
                    </span>

                </a>

            </div>

        </nav>

    </div>

</body>

</html>