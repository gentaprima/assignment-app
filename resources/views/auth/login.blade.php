<x-guest-layout>
    <div class="relative flex h-screen w-full items-center justify-center overflow-hidden bg-gray-50 px-4">
        {{-- Background Decoration --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-32 -top-32 h-80 w-80 rounded-full bg-red-100/70 blur-3xl">
            </div>
            <div class="absolute -bottom-32 -right-32 h-80 w-80 rounded-full bg-red-100/70 blur-3xl">
            </div>
        </div>
        {{-- LOGIN CARD --}}
        <div class="relative z-10 w-full max-w-md">
            {{-- Logo / Brand --}}
            <div class="mb-6 text-center">
                <div
                    class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-red-600 shadow-lg shadow-red-600/20">
                    <span class="material-icons text-3xl text-white">
                        schedule
                    </span>
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">
                    Absensi Karyawan
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Silakan masuk untuk melanjutkan
                </p>
            </div>
            {{-- CARD --}}
            <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xl shadow-gray-200/50 sm:p-8">
                {{-- Session Status --}}
                <x-auth-session-status class="mb-5" :status="session('status')" />
                {{-- Error --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                        <div class="flex items-start gap-3">
                            <span class="material-icons mt-0.5 text-xl text-red-500">
                                error_outline
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-red-700">
                                    Login gagal
                                </p>
                                <p class="mt-1 text-xs text-red-600">
                                    Email atau password yang Anda masukkan tidak sesuai.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    {{-- EMAIL --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">
                            Email
                        </label>
                        <div class="relative">
                            <span
                                class="material-icons pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[20px] text-gray-400">
                                email
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                autocomplete="username" placeholder="Masukkan email Anda"
                                class="h-12 w-full rounded-xl border border-gray-300 bg-gray-50 pl-12 pr-4 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </div>
                    {{-- PASSWORD --}}
                    <div class="mt-5">
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                Password
                            </label>
                        </div>
                        <div class="relative">
                            <span
                                class="material-icons pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[20px] text-gray-400">
                                lock
                            </span>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="Masukkan password"
                                class="h-12 w-full rounded-xl border border-gray-300 bg-gray-50 pl-12 pr-12 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-red-500 focus:bg-white focus:ring-4 focus:ring-red-500/10">
                            {{-- SHOW PASSWORD --}}
                            <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                                <span id="passwordIcon" class="material-icons text-[20px]">
                                    visibility
                                </span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    {{-- REMEMBER --}}
                    <div class="mt-5 flex items-center justify-between">

                        <label class="flex cursor-pointer items-center gap-3">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">

                            <span class="text-sm text-gray-600">
                                Ingat saya
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-red-600 transition hover:text-red-700 hover:underline">
                                Lupa password?
                            </a>
                        @endif

                    </div>
                    {{-- BUTTON --}}
                    <button type="submit"
                        class="mt-6 flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 text-sm font-bold text-white shadow-lg shadow-red-600/20 transition duration-200 hover:bg-red-700 hover:shadow-red-600/30 active:scale-[0.98]">
                        <span class="material-icons text-[20px]">
                            login
                        </span>
                        Masuk
                    </button>
                </form>
            </div>
            {{-- FOOTER --}}
            <p class="mt-5 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Absensi Karyawan.
                Semua hak dilindungi.
            </p>
        </div>
    </div>


    {{-- PASSWORD TOGGLE --}}
    <script>

        function togglePassword() {

            const password = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');

            if (password.type === 'password') {

                password.type = 'text';

                icon.textContent = 'visibility_off';

            } else {

                password.type = 'password';

                icon.textContent = 'visibility';

            }

        }

    </script>

</x-guest-layout>