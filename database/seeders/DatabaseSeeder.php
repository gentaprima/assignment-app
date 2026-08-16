<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. USERS
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'name' => 'Administrator',
            'employee_number' => 'ADM001',
            'email' => 'admin@tomorocoffee.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081111111111',
            'is_active' => true,
        ]);

        $manager = User::create([
            'name' => 'Manager Tomoro',
            'employee_number' => 'MGR001',
            'email' => 'manager@tomorocoffee.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'phone' => '082222222222',
            'is_active' => true,
        ]);

        $picBandung = User::create([
            'name' => 'PIC Bandung',
            'employee_number' => 'PIC001',
            'email' => 'pic.bandung@tomorocoffee.com',
            'password' => Hash::make('password'),
            'role' => 'pic',
            'phone' => '083333333333',
            'is_active' => true,
        ]);

        $picJakarta = User::create([
            'name' => 'PIC Jakarta',
            'employee_number' => 'PIC002',
            'email' => 'pic.jakarta@tomorocoffee.com',
            'password' => Hash::make('password'),
            'role' => 'pic',
            'phone' => '084444444444',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. EMPLOYEES
        |--------------------------------------------------------------------------
        */

        $employees = [];

        $employeeData = [
            [
                'name' => 'Andi Saputra',
                'employee_number' => 'EMP001',
                'email' => 'andi@tomorocoffee.com',
                'phone' => '085111111111',
            ],
            [
                'name' => 'Budi Santoso',
                'employee_number' => 'EMP002',
                'email' => 'budi@tomorocoffee.com',
                'phone' => '085222222222',
            ],
            [
                'name' => 'Citra Lestari',
                'employee_number' => 'EMP003',
                'email' => 'citra@tomorocoffee.com',
                'phone' => '085333333333',
            ],
            [
                'name' => 'Deni Kurniawan',
                'employee_number' => 'EMP004',
                'email' => 'deni@tomorocoffee.com',
                'phone' => '085444444444',
            ],
            [
                'name' => 'Eka Putri',
                'employee_number' => 'EMP005',
                'email' => 'eka@tomorocoffee.com',
                'phone' => '085555555555',
            ],
            [
                'name' => 'Fajar Ramadhan',
                'employee_number' => 'EMP006',
                'email' => 'fajar@tomorocoffee.com',
                'phone' => '085666666666',
            ],
        ];

        foreach ($employeeData as $data) {
            $employees[] = User::create([
                'name' => $data['name'],
                'employee_number' => $data['employee_number'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'phone' => $data['phone'],
                'is_active' => true,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. BRANCHES
        |--------------------------------------------------------------------------
        */

        $bandung = DB::table('branches')->insertGetId([
            'code' => 'TMR-BDG-001',
            'name' => 'Tomoro Coffee Bandung',
            'address' => 'Bandung, Jawa Barat',
            'latitude' => -6.917464,
            'longitude' => 107.619123,
            'radius' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $jakarta = DB::table('branches')->insertGetId([
            'code' => 'TMR-JKT-001',
            'name' => 'Tomoro Coffee Jakarta',
            'address' => 'Jakarta, DKI Jakarta',
            'latitude' => -6.208763,
            'longitude' => 106.845599,
            'radius' => 100,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. PIC CABANG
        |--------------------------------------------------------------------------
        */

        DB::table('branch_pic_assignments')->insert([
            [
                'branch_id' => $bandung,
                'user_id' => $picBandung->id,
                'start_date' => '2026-01-01',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'branch_id' => $jakarta,
                'user_id' => $picJakarta->id,
                'start_date' => '2026-01-01',
                'end_date' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. EMPLOYEE → BRANCH
        |--------------------------------------------------------------------------
        */

        // Bandung
        foreach (array_slice($employees, 0, 3) as $employee) {
            DB::table('employee_branches')->insert([
                'user_id' => $employee->id,
                'branch_id' => $bandung,
                'start_date' => '2026-01-01',
                'end_date' => null,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Jakarta
        foreach (array_slice($employees, 3, 3) as $employee) {
            DB::table('employee_branches')->insert([
                'user_id' => $employee->id,
                'branch_id' => $jakarta,
                'start_date' => '2026-01-01',
                'end_date' => null,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. SHIFTS
        |--------------------------------------------------------------------------
        */

        DB::table('shifts')->insert([
            [
                'code' => 'PAGI',
                'name' => 'Shift Pagi',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'break_start' => '12:00:00',
                'break_end' => '13:00:00',
                'is_overnight' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SIANG',
                'name' => 'Shift Siang',
                'start_time' => '10:00:00',
                'end_time' => '18:00:00',
                'break_start' => '13:00:00',
                'break_end' => '14:00:00',
                'is_overnight' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MALAM',
                'name' => 'Shift Malam',
                'start_time' => '15:00:00',
                'end_time' => '23:00:00',
                'break_start' => '18:00:00',
                'break_end' => '19:00:00',
                'is_overnight' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7. LEAVE TYPES
        |--------------------------------------------------------------------------
        */

        DB::table('leave_types')->insert([
            [
                'code' => 'SICK',
                'name' => 'Sakit',
                'description' => 'Izin tidak masuk karena sakit',
                'requires_attachment' => true,
                'is_paid' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ANNUAL',
                'name' => 'Cuti',
                'description' => 'Cuti karyawan',
                'requires_attachment' => false,
                'is_paid' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PERMIT',
                'name' => 'Izin',
                'description' => 'Izin tidak masuk kerja',
                'requires_attachment' => false,
                'is_paid' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EMERGENCY',
                'name' => 'Keperluan Mendesak',
                'description' => 'Izin karena keperluan mendesak',
                'requires_attachment' => false,
                'is_paid' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
