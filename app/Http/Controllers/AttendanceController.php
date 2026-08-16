<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ScheduleAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    /**
     * Halaman absensi employee
     */
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        $schedule = ScheduleAssignment::with([
            'shift',
            'weeklySchedule.branch'
        ])
            ->where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in_at', $today)
            ->first();

        return view('attendance.index', compact(
            'schedule',
            'attendance'
        ));
    }


    /**
     * Absen masuk
     */
    public function checkIn(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
            'photo' => ['required', 'string'],
        ]);

        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | CARI JADWAL HARI INI
        |--------------------------------------------------------------------------
        */

        $schedule = ScheduleAssignment::with([
            'weeklySchedule.branch'
        ])
            ->where('user_id', $user->id)
            ->whereDate('work_date', $today)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki jadwal kerja hari ini.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS OFF
        |--------------------------------------------------------------------------
        */

        if ($schedule->status === 'off') {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini Anda mendapatkan jadwal OFF.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS IZIN
        |--------------------------------------------------------------------------
        */

        if ($schedule->status === 'leave') {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini Anda sedang mendapatkan jadwal izin.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK JAM MASUK
        |--------------------------------------------------------------------------
        */

        // if (empty($schedule->start_time)) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Jam masuk pada jadwal belum ditentukan.'
        //     ], 422);
        // }


        /*
        |--------------------------------------------------------------------------
        | CEK CABANG
        |--------------------------------------------------------------------------
        */

        $branch = $schedule->weeklySchedule?->branch;

        if (!$branch) {
            return response()->json([
                'success' => false,
                'message' => 'Cabang pada jadwal tidak ditemukan.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK ABSEN MASUK YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in_at', $today)
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen masuk hari ini.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | GPS
        |--------------------------------------------------------------------------
        */

        $latitude = (float) $request->latitude;

        $longitude = (float) $request->longitude;

        $accuracy = $request->filled('accuracy')
            ? (float) $request->accuracy
            : null;


        /*
        |--------------------------------------------------------------------------
        | HITUNG JARAK
        |--------------------------------------------------------------------------
        */

        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            (float) $branch->latitude,
            (float) $branch->longitude
        );


        /*
        |--------------------------------------------------------------------------
        | CEK RADIUS
        |--------------------------------------------------------------------------
        */

        $radius = (float) $branch->radius;

        if ($distance > $radius) {
            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar area cabang.',
                'distance' => round($distance, 2),
                'radius' => $radius,
            ], 422);
        }

        $checkInTime = now();

        $startTime = $schedule->start_time;

        if (!$startTime) {
            return response()->json([
                'success' => false,
                'message' => 'Jam masuk pada jadwal belum ditentukan.'
            ], 422);
        }

        try {

            // Jika start_time berupa datetime lengkap
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', (string) $startTime)) {

                $scheduledStart = Carbon::parse(
                    $startTime,
                    config('app.timezone')
                );

            } else {

                // Jika start_time hanya HH:MM atau HH:MM:SS
                $scheduledStart = Carbon::parse(
                    $today->format('Y-m-d') . ' ' . $startTime,
                    config('app.timezone')
                );
            }

            // Samakan sampai level MENIT
            $checkInMinute = $checkInTime->copy()->startOfMinute();
            $scheduledMinute = $scheduledStart->copy()->startOfMinute();

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Format jam masuk pada jadwal tidak valid.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS ABSENSI
        |--------------------------------------------------------------------------
        */

        $status = 'present';
        $notes = null;

        if ($checkInMinute->greaterThan($scheduledMinute)) {

            $status = 'late';

            $lateMinutes = $scheduledMinute->diffInMinutes($checkInMinute);

            $hours = intdiv($lateMinutes, 60);
            $minutes = $lateMinutes % 60;

            if ($hours > 0) {

                $notes = "Terlambat {$hours} jam {$minutes} menit.";

            } else {

                $notes = "Terlambat {$minutes} menit.";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PROSES FOTO
        |--------------------------------------------------------------------------
        */

        $photo = $request->photo;

        if (
            !preg_match(
                '/^data:image\/(\w+);base64,/',
                $photo,
                $type
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Format foto tidak valid.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | DECODE FOTO
        |--------------------------------------------------------------------------
        */

        $photo = substr(
            $photo,
            strpos($photo, ',') + 1
        );

        $photo = base64_decode($photo);

        if ($photo === false) {
            return response()->json([
                'success' => false,
                'message' => 'Foto tidak dapat diproses.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO
        |--------------------------------------------------------------------------
        */

        $filename = 'attendance_' . Str::uuid() . '.jpg';

        $path = 'attendance/'
            . now()->format('Y/m/d')
            . '/'
            . $filename;

        Storage::disk('public')->put(
            $path,
            $photo
        );


        /*
        |--------------------------------------------------------------------------
        | SIMPAN ATTENDANCE
        |--------------------------------------------------------------------------
        */

        $attendance = Attendance::create([
            'user_id' => $user->id,

            'branch_id' => $branch->id,

            'schedule_assignment_id' => $schedule->id,

            'check_in_at' => $checkInTime,

            'check_in_latitude' => $latitude,

            'check_in_longitude' => $longitude,

            'check_in_accuracy' => $accuracy,

            'check_in_distance' => $distance,

            'status' => $status,

            'notes' => $notes,

            'check_in_photo' => $path,
        ]);


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' => $status === 'late'
                ? 'Absen masuk berhasil, tetapi Anda terlambat.'
                : 'Absen masuk berhasil.',

            'attendance' => [
                'id' => $attendance->id,

                'check_in_at' => Carbon::parse(
                    $attendance->check_in_at
                )->format('H:i'),

                'distance' => round($distance, 2),

                'status' => $status,

                'notes' => $notes,
            ],
        ]);
    }


    /**
     * Absen pulang
     */
    public function checkOut(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'accuracy' => ['nullable', 'numeric'],
            'photo' => ['required', 'string'],
        ]);

        $today = Carbon::today();


        /*
        |--------------------------------------------------------------------------
        | Cari attendance hari ini
        |--------------------------------------------------------------------------
        */

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('check_in_at', $today)
            ->first();

        if (!$attendance) {

            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absen masuk.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Cek sudah checkout
        |--------------------------------------------------------------------------
        */

        if ($attendance->check_out_at) {

            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen pulang hari ini.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Cari branch
        |--------------------------------------------------------------------------
        */

        $branch = $attendance->branch;

        if (!$branch) {

            return response()->json([
                'success' => false,
                'message' => 'Cabang absensi tidak ditemukan.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | GPS
        |--------------------------------------------------------------------------
        */

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $accuracy = $request->accuracy
            ? (float) $request->accuracy
            : null;


        /*
        |--------------------------------------------------------------------------
        | Hitung jarak
        |--------------------------------------------------------------------------
        */

        $distance = $this->calculateDistance(
            $latitude,
            $longitude,
            (float) $branch->latitude,
            (float) $branch->longitude
        );


        /*
        |--------------------------------------------------------------------------
        | Validasi radius
        |--------------------------------------------------------------------------
        */

        $radius = (float) $branch->radius;

        if ($distance > $radius) {

            return response()->json([
                'success' => false,
                'message' => 'Anda berada di luar area cabang.',
                'distance' => round($distance, 2),
                'radius' => $radius,
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan checkout
        |--------------------------------------------------------------------------
        */

        $photo = $request->photo;

        if (!preg_match('/^data:image\/(\w+);base64,/', $photo, $type)) {
            return response()->json([
                'success' => false,
                'message' => 'Format foto tidak valid.',
            ], 422);
        }

        $photo = substr($photo, strpos($photo, ',') + 1);

        $photo = base64_decode($photo);

        if ($photo === false) {
            return response()->json([
                'success' => false,
                'message' => 'Foto tidak dapat diproses.',
            ], 422);
        }

        $filename = 'attendance_' . Str::uuid() . '.jpg';

        $path = 'attendance/' . now()->format('Y/m/d') . '/' . $filename;

        Storage::disk('public')->put($path, $photo);


        $attendance->update([
            'check_out_at' => now(),

            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
            'check_out_accuracy' => $accuracy,
            'check_out_distance' => $distance,
            'check_out_photo' => $path,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil.',
            'attendance' => [
                'check_out_at' => $attendance->check_out_at->format('H:i'),
                'distance' => round($distance, 2),
            ],
        ]);
    }


    /**
     * Hitung jarak dua koordinat menggunakan Haversine.
     *
     * Hasil dalam meter.
     */
    private function calculateDistance(
        float $latitude1,
        float $longitude1,
        float $latitude2,
        float $longitude2
    ): float {

        $earthRadius = 6371000;

        $lat1 = deg2rad($latitude1);
        $lat2 = deg2rad($latitude2);

        $deltaLatitude = deg2rad(
            $latitude2 - $latitude1
        );

        $deltaLongitude = deg2rad(
            $longitude2 - $longitude1
        );

        $a =
            sin($deltaLatitude / 2) ** 2
            +
            cos($lat1)
            * cos($lat2)
            * sin($deltaLongitude / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        return $earthRadius * $c;
    }
}