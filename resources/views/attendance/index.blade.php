@extends('components.dashboard-layout')

@section('content')

    <div class="space-y-6">

        {{-- HEADER --}}
        <section class="rounded-2xl bg-gradient-to-r from-red-600 to-red-500 p-6 text-white shadow-sm sm:p-8">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-sm text-red-100">
                        Absensi Karyawan
                    </p>

                    <h2 class="mt-1 text-2xl font-bold sm:text-3xl">
                        Absensi Hari Ini
                    </h2>

                    <p class="mt-2 text-sm text-red-100">
                        Silakan lakukan absensi sesuai jadwal kerja Anda.
                    </p>

                </div>

                <div class="rounded-xl bg-white/10 px-5 py-3">

                    <p class="text-xs text-red-100">
                        Hari ini
                    </p>

                    <p class="mt-1 font-semibold">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>

                </div>

            </div>

        </section>


        {{-- JIKA BELUM ADA JADWAL --}}
        @if(!$schedule)

            <section class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6">

                <div class="flex items-start gap-4">

                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-yellow-100">

                        <span class="material-icons text-yellow-600">
                            event_busy
                        </span>

                    </div>

                    <div>

                        <h3 class="font-bold text-yellow-800">
                            Tidak Ada Jadwal
                        </h3>

                        <p class="mt-1 text-sm text-yellow-700">
                            Anda tidak memiliki jadwal kerja untuk hari ini.
                        </p>

                    </div>

                </div>

            </section>

        @else

            {{-- INFORMASI JADWAL --}}
            <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 p-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50">

                            <span class="material-icons text-blue-600">
                                schedule
                            </span>

                        </div>

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Jadwal Hari Ini
                            </h3>

                            <p class="text-sm text-gray-500">
                                Informasi jadwal kerja Anda
                            </p>

                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">

                    {{-- CABANG --}}
                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Cabang
                        </p>

                        <div class="mt-2 flex items-center gap-2">

                            <span class="material-icons text-gray-500">
                                store
                            </span>

                            <p class="font-semibold text-gray-900">
                                {{ $schedule->weeklySchedule?->branch?->name ?? '-' }}
                            </p>

                        </div>

                    </div>


                    {{-- TANGGAL --}}
                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Tanggal
                        </p>

                        <div class="mt-2 flex items-center gap-2">

                            <span class="material-icons text-gray-500">
                                calendar_month
                            </span>

                            <p class="font-semibold text-gray-900">
                                {{ $schedule->work_date->translatedFormat('d F Y') }}
                            </p>

                        </div>

                    </div>


                    {{-- SHIFT --}}
                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Shift
                        </p>

                        <div class="mt-2 flex items-center gap-2">

                            <span class="material-icons text-gray-500">
                                access_time
                            </span>

                            <p class="font-semibold text-gray-900">

                                @if($schedule->status === 'off')

                                    Off

                                @elseif($schedule->status === 'leave')

                                    Izin

                                @elseif($schedule->shift)

                                    {{ $schedule->shift->name }}

                                @else

                                    Jadwal Kerja

                                @endif

                            </p>

                        </div>

                    </div>

                    {{-- JAM KERJA --}}
                    <div class="rounded-xl bg-gray-50 p-4">

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                            Jam Kerja
                        </p>

                        <div class="mt-2 flex items-center gap-2">

                            <span class="material-icons text-gray-500">
                                schedule
                            </span>

                            <p class="font-semibold text-gray-900">

                                @if($schedule->start_time && $schedule->end_time)

                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}

                                @elseif($schedule->shift)

                                    {{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i') }}

                                @else

                                    -

                                @endif

                            </p>

                        </div>

                    </div>
                </div>

            </section>


            {{-- STATUS ABSENSI --}}
            <section class="rounded-2xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 p-5">

                    <h3 class="font-bold text-gray-900">
                        Status Absensi
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Status kehadiran Anda hari ini
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2">

                    {{-- CHECK IN --}}
                    <div class="rounded-2xl border border-gray-200 p-5">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-50">

                                    <span class="material-icons text-green-600">
                                        login
                                    </span>

                                </div>

                                <div>

                                    <p class="font-semibold text-gray-900">
                                        Absen Masuk
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Jam masuk
                                    </p>

                                </div>

                            </div>


                            @if($attendance?->check_in_at)
                                @if ($attendance?->status == "late")
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                        Berhasil
                                    </span>
                                @endif
                            @else

                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                    Belum
                                </span>

                            @endif

                        </div>


                        <div class="mt-5">

                            @if($attendance?->check_in_at)

                                <p class="text-3xl font-bold text-gray-900">
                                    {{ $attendance->check_in_at->format('H:i') }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $attendance->check_in_at->translatedFormat('d F Y') }}
                                </p>

                            @else

                                <p class="text-3xl font-bold text-gray-300">
                                    --:--
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Belum melakukan absen masuk
                                </p>

                            @endif

                        </div>

                    </div>


                    {{-- CHECK OUT --}}
                    <div class="rounded-2xl border border-gray-200 p-5">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-orange-50">

                                    <span class="material-icons text-orange-600">
                                        logout
                                    </span>

                                </div>

                                <div>

                                    <p class="font-semibold text-gray-900">
                                        Absen Pulang
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Jam pulang
                                    </p>

                                </div>

                            </div>


                            @if($attendance?->check_out_at)

                                <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                    Berhasil
                                </span>

                            @else

                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                    Belum
                                </span>

                            @endif

                        </div>


                        <div class="mt-5">

                            @if($attendance?->check_out_at)

                                <p class="text-3xl font-bold text-gray-900">
                                    {{ $attendance->check_out_at->format('H:i') }}
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{ $attendance->check_out_at->translatedFormat('d F Y') }}
                                </p>

                            @else

                                <p class="text-3xl font-bold text-gray-300">
                                    --:--
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Belum melakukan absen pulang
                                </p>

                            @endif

                        </div>

                    </div>

                </div>

            </section>


            {{-- ABSENSI --}}
            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">

                <div class="rounded-2xl bg-gray-50 p-5">

                    <div class="flex items-start gap-4">

                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-50">

                            <span class="material-icons text-red-600">
                                location_on
                            </span>

                        </div>

                        <div>

                            <h3 class="font-bold text-gray-900">
                                Verifikasi Lokasi
                            </h3>

                            <p class="mt-1 text-sm text-gray-500">
                                Pastikan Anda berada di area cabang sebelum melakukan absensi.
                            </p>

                        </div>

                    </div>


                    <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">

                        <div class="rounded-xl bg-white p-4">

                            <p class="text-xs text-gray-500">
                                Lokasi Cabang
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $schedule->weeklySchedule?->branch?->name ?? '-' }}
                            </p>

                        </div>


                        <div class="rounded-xl bg-white p-4">

                            <p class="text-xs text-gray-500">
                                Status Lokasi
                            </p>

                            <div class="mt-1 flex items-center gap-2">

                                <span id="locationIndicator" class="h-3 w-3 rounded-full bg-gray-400"></span>

                                <span id="locationStatus" class="font-semibold text-gray-600">
                                    Mengecek lokasi...
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- CAMERA SELFIE --}}
                <div id="cameraContainer" class="mt-5 hidden">
                    <div class="overflow-hidden rounded-2xl bg-black">
                        <div class="relative overflow-hidden">
                            <video id="cameraPreview" class="hidden" autoplay playsinline muted>
                            </video>

                            <canvas id="cameraLivePreview" class="h-auto max-h-[500px] w-full object-cover">
                            </canvas>
                        </div>
                    </div>

                    <canvas id="cameraCanvas" class="hidden"></canvas>

                    <div class="mt-3 flex gap-3">
                        <button type="button" onclick="takeSelfie()"
                            class="flex-1 rounded-xl bg-red-600 px-4 py-3 font-semibold text-white hover:bg-red-700">
                            <span class="material-icons align-middle">camera_alt</span>
                            Ambil Foto
                        </button>

                        <button type="button" onclick="closeCamera()"
                            class="rounded-xl border border-gray-300 px-5 py-3 font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                    </div>
                </div>

                {{-- PREVIEW SELFIE --}}
                <div id="photoPreviewContainer" class="mt-5 hidden">

                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-gray-100">
                        <img id="photoPreview" class="h-auto max-h-[500px] w-full object-cover" alt="Preview selfie">
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-3">

                        <button type="button" onclick="retakeSelfie()"
                            class="rounded-xl border border-gray-300 px-4 py-3 font-semibold text-gray-700 hover:bg-gray-50">
                            Foto Ulang
                        </button>

                        <button type="button" id="confirmAttendanceButton" onclick="confirmAttendance()"
                            class="rounded-xl bg-red-600 px-4 py-3 font-semibold text-white hover:bg-red-700">
                            Konfirmasi Absen
                        </button>

                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="mt-5">

                    @if($schedule->status === 'off')

                        <div class="rounded-xl bg-gray-100 p-4 text-center">

                            <p class="font-semibold text-gray-700">
                                Hari ini Anda mendapatkan jadwal OFF.
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                Anda tidak perlu melakukan absensi.
                            </p>

                        </div>

                    @elseif(!$attendance?->check_in_at)

                        <!-- <button type="button" onclick="getLocationAndCheckIn()"
                                                                                                                                                                                                                                                                                        class="flex h-14 w-full items-center justify-center gap-3 rounded-xl bg-red-600 font-semibold text-white shadow-sm transition hover:bg-red-700 active:bg-red-800">

                                                                                                                                                                                                                                                                                        <span class="material-icons">
                                                                                                                                                                                                                                                                                            fingerprint
                                                                                                                                                                                                                                                                                        </span>

                                                                                                                                                                                                                                                                                        ABEN MASUK

                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                     -->

                        <button type="button" onclick="startAttendance('check-in')"
                            class="flex h-14 w-full items-center justify-center gap-3 rounded-xl bg-red-600 font-semibold text-white shadow-sm transition hover:bg-red-700 active:bg-red-800">
                            <span class="material-icons">
                                camera_alt
                            </span>

                            ABSEN MASUK
                        </button>

                    @elseif(!$attendance?->check_out_at)

                        <!-- <button type="button" onclick="getLocationAndCheckOut()"
                                                                                                                                                                                                                                                                            class="flex h-14 w-full items-center justify-center gap-3 rounded-xl bg-orange-500 font-semibold text-white shadow-sm transition hover:bg-orange-600 active:bg-orange-700">

                                                                                                                                                                                                                                                                            <span class="material-icons">
                                                                                                                                                                                                                                                                                fingerprint
                                                                                                                                                                                                                                                                            </span>

                                                                                                                                                                                                                                                                            ABSEN PULANG

                                                                                                                                                                                                                                                                        </button> -->

                        <button type="button" onclick="startAttendance('check-out')"
                            class="flex h-14 w-full items-center justify-center gap-3 rounded-xl bg-orange-500 font-semibold text-white shadow-sm transition hover:bg-orange-600 active:bg-orange-700">
                            <span class="material-icons">
                                camera_alt
                            </span>

                            ABSEN PULANG
                        </button>

                    @else

                        <div class="rounded-xl bg-green-50 p-4 text-center">

                            <div class="flex items-center justify-center gap-2">

                                <span class="material-icons text-green-600">
                                    check_circle
                                </span>

                                <p class="font-semibold text-green-700">
                                    Absensi hari ini sudah lengkap
                                </p>

                            </div>

                        </div>

                    @endif

                </div>

            </section>

        @endif

    </div>


    <script>

        let cameraStream = null;
        let attendanceType = null;
        let selfieData = null;
        let livePreviewAnimation = null;

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');


        /*
        |--------------------------------------------------------------------------
        | START ATTENDANCE
        |--------------------------------------------------------------------------
        */

        async function startAttendance(type) {

            attendanceType = type;
            selfieData = null;

            try {

                await openCamera();

            } catch (error) {

                await Swal.fire({
                    icon: 'error',
                    title: 'Kamera Tidak Bisa Dibuka',
                    text: error.message,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#dc2626',
                    allowOutsideClick: false
                });

            }
        }


        /*
        |--------------------------------------------------------------------------
        | OPEN CAMERA
        |--------------------------------------------------------------------------
        */

        async function openCamera() {

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {

                throw new Error(
                    'Browser Anda tidak mendukung akses kamera.'
                );

            }

            cameraStream = await navigator.mediaDevices.getUserMedia({

                video: {
                    facingMode: {
                        ideal: 'user'
                    },

                    width: {
                        ideal: 1280
                    },

                    height: {
                        ideal: 720
                    }
                },

                audio: false
            });

            const video = document.getElementById('cameraPreview');
            const canvas = document.getElementById('cameraLivePreview');

            video.srcObject = cameraStream;

            await video.play();

            /*
            |--------------------------------------------------------------------------
            | SET CANVAS
            |--------------------------------------------------------------------------
            */

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            /*
            |--------------------------------------------------------------------------
            | START LIVE PREVIEW
            |--------------------------------------------------------------------------
            */

            startLivePreview();

            document
                .getElementById('cameraContainer')
                .classList.remove('hidden');

            document
                .getElementById('photoPreviewContainer')
                .classList.add('hidden');
        }


        /*
        |--------------------------------------------------------------------------
        | TAKE SELFIE
        |--------------------------------------------------------------------------
        */

        function takeSelfie() {

            const video = document.getElementById('cameraPreview');
            const canvas = document.getElementById('cameraCanvas');

            if (!video.videoWidth || !video.videoHeight) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Kamera Belum Siap',
                    text: 'Tunggu sebentar kemudian coba lagi.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626'
                });

                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const context = canvas.getContext('2d');

            /*
            |--------------------------------------------------------------------------
            | AMBIL FOTO TANPA MIRROR
            |--------------------------------------------------------------------------
            */

            context.save();

            context.translate(canvas.width, 0);
            context.scale(-1, 1);

            context.drawImage(
                video,
                0,
                0,
                canvas.width,
                canvas.height
            );

            context.restore();

            /*
            |--------------------------------------------------------------------------
            | CONVERT KE BASE64
            |--------------------------------------------------------------------------
            */

            selfieData = canvas.toDataURL(
                'image/jpeg',
                0.8
            );

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN PREVIEW FOTO
            |--------------------------------------------------------------------------
            */

            const photoPreview = document.getElementById('photoPreview');

            photoPreview.src = selfieData;

            // Jangan mirror hasil foto
            photoPreview.style.transform = 'none';
            photoPreview.style.webkitTransform = 'none';

            document
                .getElementById('cameraContainer')
                .classList.add('hidden');

            document
                .getElementById('photoPreviewContainer')
                .classList.remove('hidden');

            stopCamera();
        }


        /*
        |--------------------------------------------------------------------------
        | RETAKE
        |--------------------------------------------------------------------------
        */

        function retakeSelfie() {
            selfieData = null;
            document
                .getElementById('photoPreviewContainer')
                .classList.add('hidden');
            openCamera();
        }
        /*
        |--------------------------------------------------------------------------
        | CLOSE CAMERA
        |--------------------------------------------------------------------------
        */
        function closeCamera() {
            stopCamera();
            document
                .getElementById('cameraContainer')
                .classList.add('hidden');
            document
                .getElementById('photoPreviewContainer')
                .classList.add('hidden');

            selfieData = null;
            attendanceType = null;
        }

        /*
        |--------------------------------------------------------------------------
        | STOP CAMERA
        |--------------------------------------------------------------------------
        */
        function stopCamera() {

            /*
            |--------------------------------------------------------------------------
            | STOP LIVE PREVIEW
            |--------------------------------------------------------------------------
            */

            if (livePreviewAnimation) {

                cancelAnimationFrame(
                    livePreviewAnimation
                );

                livePreviewAnimation = null;
            }

            /*
            |--------------------------------------------------------------------------
            | STOP CAMERA STREAM
            |--------------------------------------------------------------------------
            */

            if (cameraStream) {

                cameraStream
                    .getTracks()
                    .forEach(track => track.stop());

                cameraStream = null;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CONFIRM ATTENDANCE
        |--------------------------------------------------------------------------
        */

        async function confirmAttendance() {
            if (!selfieData) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Foto Belum Ada',
                    text: 'Silakan ambil foto selfie terlebih dahulu.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626'
                });
                return;
            }
            const button = document.getElementById(
                'confirmAttendanceButton'
            );
            button.disabled = true;
            button.innerHTML = `
                                                                                    <span class="material-icons animate-spin align-middle">
                                                                                        refresh
                                                                                    </span>
                                                                                    Memproses...
                                                                                `;
            try {
                updateLocationStatus(
                    'Mengambil lokasi GPS...',
                    'loading'
                );
                const location = await getLocation();
                updateLocationStatus(
                    'Memverifikasi lokasi...',
                    'loading'
                );

                const url = attendanceType === 'check-in'
                    ? "{{ route('attendance.check-in') }}"
                    : "{{ route('attendance.check-out') }}";

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        latitude: location.latitude,
                        longitude: location.longitude,
                        accuracy: location.accuracy,
                        photo: selfieData
                    })
                });


                // const data = await response.json();
                const responseText = await response.text();
                let data;

                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    throw new Error(
                        `Server mengembalikan response bukan JSON. HTTP ${response.status}. Cek console untuk detail error Laravel.`
                    );
                }


                if (!response.ok || !data.success) {

                    throw new Error(
                        data.message ||
                        'Absensi gagal.'
                    );
                }


                updateLocationStatus(
                    'Lokasi terverifikasi.',
                    'success'
                );


                /*
                |--------------------------------------------------------------------------
                | ABSEN MASUK
                |--------------------------------------------------------------------------
                */

                if (attendanceType === 'check-in') {

                    if (data.attendance.status === 'late') {

                        await Swal.fire({

                            icon: 'warning',

                            title: 'Absen Berhasil',

                            html: `
                                                                                                    <div class="text-center">

                                                                                                        <p class="text-gray-600">
                                                                                                            Anda berhasil melakukan absen masuk.
                                                                                                        </p>

                                                                                                        <div class="mt-4 rounded-xl bg-orange-50 p-4">

                                                                                                            <p class="text-sm text-gray-500">
                                                                                                                Status
                                                                                                            </p>

                                                                                                            <p class="mt-1 font-semibold text-orange-600">
                                                                                                                Terlambat
                                                                                                            </p>

                                                                                                            <p class="mt-2 text-sm text-gray-600">
                                                                                                                ${data.attendance.notes ?? ''}
                                                                                                            </p>

                                                                                                        </div>

                                                                                                        <div class="mt-4 space-y-1 text-sm text-gray-500">

                                                                                                            <p>
                                                                                                                Jam masuk:
                                                                                                                <strong class="text-gray-900">
                                                                                                                    ${data.attendance.check_in_at}
                                                                                                                </strong>
                                                                                                            </p>

                                                                                                            <p>
                                                                                                                Jarak:
                                                                                                                <strong class="text-gray-900">
                                                                                                                    ${data.attendance.distance} meter
                                                                                                                </strong>
                                                                                                            </p>

                                                                                                        </div>

                                                                                                    </div>
                                                                                                `,

                            showConfirmButton: true,

                            confirmButtonText: 'OK',

                            confirmButtonColor: '#dc2626',

                            allowOutsideClick: false,

                            allowEscapeKey: false
                        });

                    } else {

                        await Swal.fire({

                            icon: 'success',

                            title: 'Absen Berhasil',

                            html: `
                                                                                                    <p class="text-gray-600">
                                                                                                        Absen masuk berhasil dicatat.
                                                                                                    </p>

                                                                                                    <div class="mt-4 space-y-2 text-sm text-gray-500">

                                                                                                        <p>
                                                                                                            Jam masuk:
                                                                                                            <strong class="text-gray-900">
                                                                                                                ${data.attendance.check_in_at}
                                                                                                            </strong>
                                                                                                        </p>

                                                                                                        <p>
                                                                                                            Jarak:
                                                                                                            <strong class="text-gray-900">
                                                                                                                ${data.attendance.distance} meter
                                                                                                            </strong>
                                                                                                        </p>

                                                                                                    </div>
                                                                                                `,

                            showConfirmButton: true,

                            confirmButtonText: 'OK',

                            confirmButtonColor: '#dc2626',

                            allowOutsideClick: false
                        });

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | ABSEN PULANG
                |--------------------------------------------------------------------------
                */

                else {

                    await Swal.fire({

                        icon: 'success',

                        title: 'Absen Pulang Berhasil',

                        html: `
                                                                                                <p class="text-gray-600">
                                                                                                    Absen pulang berhasil dicatat.
                                                                                                </p>

                                                                                                <div class="mt-4 space-y-2 text-sm text-gray-500">

                                                                                                    <p>
                                                                                                        Jam pulang:
                                                                                                        <strong class="text-gray-900">
                                                                                                            ${data.attendance.check_out_at}
                                                                                                        </strong>
                                                                                                    </p>

                                                                                                    <p>
                                                                                                        Jarak:
                                                                                                        <strong class="text-gray-900">
                                                                                                            ${data.attendance.distance} meter
                                                                                                        </strong>
                                                                                                    </p>

                                                                                                </div>
                                                                                            `,

                        showConfirmButton: true,

                        confirmButtonText: 'OK',

                        confirmButtonColor: '#dc2626',

                        allowOutsideClick: false
                    });

                }


                window.location.reload();


            } catch (error) {

                updateLocationStatus(
                    error.message,
                    'error'
                );

                await Swal.fire({

                    icon: 'error',

                    title: 'Absen Gagal',

                    text: error.message,

                    confirmButtonText: 'Mengerti',

                    confirmButtonColor: '#dc2626',

                    allowOutsideClick: false
                });


            } finally {

                button.disabled = false;

                button.innerHTML = `
                                                                                        Konfirmasi Absen
                                                                                    `;

            }
        }


        /*
        |--------------------------------------------------------------------------
        | GET LOCATION
        |--------------------------------------------------------------------------
        */

        function getLocation() {

            return new Promise((resolve, reject) => {

                if (!navigator.geolocation) {

                    reject(
                        new Error(
                            'Browser tidak mendukung GPS.'
                        )
                    );

                    return;
                }


                navigator.geolocation.getCurrentPosition(

                    position => {

                        resolve({

                            latitude:
                                position.coords.latitude,

                            longitude:
                                position.coords.longitude,

                            accuracy:
                                position.coords.accuracy

                        });

                    },

                    error => {

                        let message =
                            'Gagal mendapatkan lokasi.';


                        if (error.code === 1) {

                            message =
                                'Izin lokasi ditolak. Silakan izinkan akses lokasi.';
                        }


                        if (error.code === 2) {

                            message =
                                'Lokasi tidak tersedia.';
                        }


                        if (error.code === 3) {

                            message =
                                'Pengambilan lokasi terlalu lama.';
                        }


                        reject(
                            new Error(message)
                        );

                    },

                    {

                        enableHighAccuracy: true,

                        timeout: 15000,

                        maximumAge: 0

                    }

                );

            });
        }

        function updateLocationStatus(message, type = 'loading') {

            const statusElement = document.getElementById('locationStatus');

            if (!statusElement) {
                return;
            }

            let icon = 'location_searching';
            let className = 'text-gray-500';

            if (type === 'loading') {
                icon = 'sync';
                className = 'text-blue-600';
            }

            if (type === 'success') {
                icon = 'location_on';
                className = 'text-green-600';
            }

            if (type === 'error') {
                icon = 'location_off';
                className = 'text-red-600';
            }

            statusElement.innerHTML = `
                                                                                    <span class="material-icons ${className}">
                                                                                        ${icon}
                                                                                    </span>

                                                                                    <span class="${className}">
                                                                                        ${message}
                                                                                    </span>
                                                                                `;
        }


        function startLivePreview() {

            const video = document.getElementById('cameraPreview');
            const canvas = document.getElementById('cameraLivePreview');

            const context = canvas.getContext('2d');

            function drawFrame() {

                if (!cameraStream) {
                    return;
                }

                if (!video.videoWidth || !video.videoHeight) {

                    livePreviewAnimation =
                        requestAnimationFrame(drawFrame);

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | SESUAIKAN CANVAS DENGAN VIDEO
                |--------------------------------------------------------------------------
                */

                if (
                    canvas.width !== video.videoWidth ||
                    canvas.height !== video.videoHeight
                ) {

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                }

                /*
                |--------------------------------------------------------------------------
                | BALIK HORIZONTAL
                |--------------------------------------------------------------------------
                |
                | Karena kamera depan kamu menghasilkan preview mirror,
                | kita balik frame sebelum ditampilkan.
                |
                */

                context.save();

                context.translate(canvas.width, 0);
                context.scale(-1, 1);

                context.drawImage(
                    video,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                context.restore();

                livePreviewAnimation =
                    requestAnimationFrame(drawFrame);
            }

            drawFrame();
        }
    </script>

@endsection