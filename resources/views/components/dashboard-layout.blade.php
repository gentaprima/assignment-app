<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#dc2626">
    <title>{{ $title ?? 'Dashboard' }} - Tomoro Absensi</title>
    {{-- PWA --}}
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#e91e63">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Absensi">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    {{-- Google Material Icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{-- Tailwind CDN untuk sementara --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#dc2626',
                    }
                }
            }
        }
    </script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script src="{{ asset('js/sweetalert.js') }}"></script>
</head>

<body>

    <div class="min-h-screen">
        {{-- MOBILE OVERLAY --}}
        <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 bg-black/40" onclick="toggleSidebar()"></div>
        {{-- SIDEBAR --}}
        <aside id="sidebar"
            class="sidebar fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-gray-200 bg-white">
            {{-- LOGO --}}
            <div class="flex h-20 shrink-0 items-center border-b border-gray-200 px-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-600 text-white">
                    <span class="material-icons">
                        fingerprint
                    </span>
                </div>
                <div class="ml-3">
                    <div class="text-lg font-bold text-gray-900">
                        TOMORO
                    </div>
                    <div class="text-sm text-gray-500">
                        Absensi
                    </div>
                </div>
            </div>
            {{-- MENU --}}
            <nav class="flex-1 overflow-y-auto px-4 py-5">
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Menu
                </p>
                {{-- Dashboard --}}
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3
                {{ request()->routeIs(auth()->user()->role . '.dashboard')
    ? 'bg-red-50 text-red-600'
    : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="material-icons text-xl">
                        dashboard
                    </span>
                    <span class="font-medium">
                        Dashboard
                    </span>
                </a>
                @if(auth()->user()->role === 'admin')
                            {{-- Karyawan --}}
                            <a href="/admin/employees" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                    {{ request()->routeIs('admin.employees')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons text-xl">
                                    people
                                </span>

                                <span class="font-medium">
                                    Karyawan
                                </span>
                            </a>
                            {{-- Cabang --}}
                            <a href="/admin/branches" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                     {{ request()->routeIs('admin.branches')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons text-xl">
                                    store
                                </span>

                                <span class="font-medium">
                                    Cabang
                                </span>
                            </a>
                            {{-- Absensi --}}
                            <!-- <a href="#" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50">
                                            <span class="material-icons text-xl">
                                                how_to_reg
                                            </span>

                                            <span class="font-medium">
                                                Absensi
                                            </span>
                                        </a> -->
                            {{-- Jadwal --}}
                            <a href="/admin/schedules" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                    {{ request()->routeIs('admin.schedules')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons text-xl">
                                    calendar_month
                                </span>

                                <span class="font-medium">
                                    Jadwal
                                </span>
                            </a>
                            {{-- Izin --}}
                            <a href="{{ route('admin.leave-requests.index') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                    {{ request()->routeIs('admin.leave-requests.*')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons text-xl">
                                    event_busy
                                </span>

                                <span class="font-medium">
                                    Izin
                                </span>
                            </a>
                            {{-- Perbaikan --}}
                            <!-- <a href="#" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                    {{ request()->routeIs('admin.corrections')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons text-xl">
                                    edit_note
                                </span>

                                <span class="font-medium">
                                    Perbaikan Absensi
                                </span>
                            </a> -->
                            {{-- Laporan --}}
                            <a href="{{ route('reports.index') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                    {{ request()->routeIs('reports.index')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons">assessment</span>
                                <span>Laporan</span>
                            </a>

                @endif
                {{-- ========================= --}}
                {{-- MENU PIC --}}
                {{-- ========================= --}}
                @if(auth()->user()->role === 'pic')

                            <a href="{{ route('my.schedule') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                                                       {{ request()->routeIs('my.schedule')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">

                                <span class="material-icons">
                                    calendar_month
                                </span>

                                <span>Jadwal Saya</span>

                            </a>

                            <a href="{{ route('pic.schedules') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                            {{ request()->routeIs('pic.schedules')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons">calendar_month</span>
                                <span>Jadwal</span>
                            </a>

                            <a href="{{ route('attendance') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                            {{ request()->routeIs('attendance')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons">
                                    location_on
                                </span>
                                <span>Absensi</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50 {{ request()->routeIs('reports.index')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">
                                <span class="material-icons">assessment</span>
                                <span>Laporan</span>
                            </a>

                @endif
                {{-- ========================= --}}
                {{-- MENU KARYAWAN --}}
                {{-- ========================= --}}
                @if(auth()->user()->role === 'employee')

                            <a href="{{ route('my.schedule') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                                                    {{ request()->routeIs('my.schedule')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}">

                                <span class="material-icons">
                                    calendar_month
                                </span>

                                <span>Jadwal Saya</span>

                            </a>

                            <a href="{{ route('attendance') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                                                                                                {{ request()->routeIs('attendance')
                    ? 'bg-red-50 text-red-600'
                    : 'text-gray-600 hover:bg-gray-50' }}
                                                                                            ">
                                <span class="material-icons">location_on</span>
                                <span>Absensi</span>
                            </a>

                            <!-- <a href="#" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50">
                                <span class="material-icons">edit_note</span>
                                <span>Perbaikan Absensi</span>
                            </a> -->
                @endif
                {{-- ========================= --}}
                {{-- MENU KARYAWAN & PIC--}}
                {{-- ========================= --}}   
                @if(in_array(auth()->user()->role, ['employee', 'pic']))

                                <a href="{{ route('leave-requests.index') }}" class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50
                       {{ request()->routeIs('leave-requests.*')
                        ? 'bg-red-50 text-red-600'
                        : 'text-gray-600 hover:bg-gray-50' }}">

                                    <span class="material-icons">
                                        event_busy
                                    </span>

                                    <span>
                                        Izin
                                    </span>

                                </a>

                @endif
                {{-- Divider --}}
                <div class="my-5 border-t border-gray-100"></div>
                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Akun
                </p>
                {{-- Profile --}}
                <a href="{{ route('profile.edit') }}"
                    class="mb-1 flex items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">
                        person
                    </span>

                    <span class="font-medium">
                        Profil
                    </span>
                </a>
            </nav>
            {{-- LOGOUT --}}
            <div class="shrink-0 border-t border-gray-200 p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-gray-600 hover:bg-red-50 hover:text-red-600">
                        <span class="material-icons">
                            logout
                        </span>
                        <span class="font-medium">
                            Keluar
                        </span>
                    </button>
                </form>
            </div>
        </aside>
        {{-- MAIN --}}
        <div class="min-h-screen lg:ml-64">
            {{-- HEADER --}}
            <header class="sticky top-0 z-30 h-20 border-b border-gray-200 bg-white/95 backdrop-blur">
                <div class="flex h-full items-center justify-between px-4 sm:px-6 lg:px-8">
                    {{-- LEFT --}}
                    <div class="flex items-center gap-3">
                        {{-- Mobile menu --}}
                        <button type="button" onclick="toggleSidebar()"
                            class="flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100 lg:hidden">
                            <span class="material-icons">
                                menu
                            </span>
                        </button>
                        <div>
                            <h1 class="text-lg font-bold text-gray-900 sm:text-xl">
                                {{ $title ?? 'Dashboard' }}
                            </h1>
                            <p class="hidden text-sm text-gray-500 sm:block">
                                Sistem Absensi Karyawan
                            </p>
                        </div>
                    </div>
                    {{-- RIGHT --}}
                    <div class="flex items-center gap-2 sm:gap-4">
                        {{-- Notification --}}
                        <button type="button"
                            class="relative flex h-10 w-10 items-center justify-center rounded-xl text-gray-600 hover:bg-gray-100">
                            <span class="material-icons">
                                notifications
                            </span>
                            <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-600"></span>
                        </button>
                        {{-- User --}}
                        <div class="hidden items-center gap-3 sm:flex">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ auth()->user()->name ?? 'User' }}
                                </p>
                                <p class="text-xs capitalize text-gray-500">
                                    {{ auth()->user()->role ?? 'User' }}
                                </p>
                            </div>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 font-bold text-red-600">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            {{-- CONTENT --}}
            <main class="min-h-[calc(100vh-5rem)] p-4 sm:p-6 lg:p-8">
                <div class="mx-auto w-full max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>