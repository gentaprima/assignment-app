<?php

namespace App\Exports\Sheets;

use App\Models\Branch;
use App\Models\ScheduleAssignment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BranchAttendanceSheet implements
    FromCollection,
    WithTitle,
    WithEvents
{
    public function __construct(
        protected Branch $branch,
        protected int $userId,
        protected string $userRole,
        protected string $startDate,
        protected string $endDate,
        protected ?string $status = null,
    ) {
    }


    /*
    |--------------------------------------------------------------------------
    | DATA EXCEL
    |--------------------------------------------------------------------------
    */

    public function collection(): Collection
    {
        $reports = ScheduleAssignment::query()

            ->with([
                'user',
                'attendance',
                'weeklySchedule.branch',
            ])

            ->whereBetween(
                'work_date',
                [
                    $this->startDate,
                    $this->endDate
                ]
            )

            /*
            |--------------------------------------------------------------------------
            | FILTER CABANG
            |--------------------------------------------------------------------------
            */

            ->whereHas(
                'weeklySchedule',
                function ($query) {

                    $query->where(
                        'branch_id',
                        $this->branch->id
                    );

                }
            )

            /*
            |--------------------------------------------------------------------------
            | EMPLOYEE HANYA DATA SENDIRI
            |--------------------------------------------------------------------------
            */

            ->when(
                $this->userRole === 'employee',
                function ($query) {

                    $query->where(
                        'user_id',
                        $this->userId
                    );

                }
            )

            ->orderBy('work_date')

            ->orderBy('user_id')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($this->status) {

            $reports = $reports
                ->filter(function ($report) {

                    return $this->getReportStatus($report)
                        === $this->status;

                })
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $rows = collect([
            [
                'No',
                'Nama Karyawan',
                'Email',
                'Tanggal',
                'Cabang',

                'Jadwal Masuk',
                'Aktual Masuk',
                'Terlambat (Menit)',

                'Jadwal Pulang',
                'Aktual Pulang',

                'Radius Cabang (m)',

                'Jarak Masuk (m)',
                'Akurasi GPS Masuk (m)',

                'Jarak Pulang (m)',
                'Akurasi GPS Pulang (m)',

                'Latitude Masuk',
                'Longitude Masuk',

                'Latitude Pulang',
                'Longitude Pulang',

                'Status',
            ]
        ]);


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        foreach ($reports as $index => $report) {

            $attendance = $report->attendance;


            /*
            |--------------------------------------------------------------------------
            | JAM JADWAL
            |--------------------------------------------------------------------------
            */

            $scheduleStart = $report->start_time
                ? Carbon::parse($report->start_time)
                    ->format('H:i')
                : null;

            $scheduleEnd = $report->end_time
                ? Carbon::parse($report->end_time)
                    ->format('H:i')
                : null;


            /*
            |--------------------------------------------------------------------------
            | JAM AKTUAL
            |--------------------------------------------------------------------------
            */

            $actualCheckIn = $attendance?->check_in_at
                ? Carbon::parse($attendance->check_in_at)
                : null;

            $actualCheckOut = $attendance?->check_out_at
                ? Carbon::parse($attendance->check_out_at)
                : null;


            /*
            |--------------------------------------------------------------------------
            | HITUNG KETERLAMBATAN
            |--------------------------------------------------------------------------
            */

            $lateMinutes = null;

            if (
                $scheduleStart &&
                $actualCheckIn
            ) {

                /*
                |--------------------------------------------------------------------------
                | Ambil tanggal work_date + HANYA jam schedule
                |--------------------------------------------------------------------------
                |
                | Ini sengaja dibuat seperti ini supaya tidak terkena
                | error "Double date specification" seperti sebelumnya.
                |
                */

                $scheduledStartDateTime = Carbon::parse(
                    Carbon::parse($report->work_date)
                        ->format('Y-m-d')
                    . ' '
                    . Carbon::parse($report->start_time)
                        ->format('H:i:s')
                );


                if (
                    $actualCheckIn->greaterThan(
                        $scheduledStartDateTime
                    )
                ) {

                    $lateMinutes = (int)
                        $scheduledStartDateTime
                            ->diffInMinutes(
                                $actualCheckIn
                            );

                } else {

                    $lateMinutes = 0;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $status = $this->getReportStatus(
                $report
            );


            /*
            |--------------------------------------------------------------------------
            | TAMBAH ROW
            |--------------------------------------------------------------------------
            */

            $rows->push([

                $index + 1,

                $report->user?->name ?? '-',

                $report->user?->email ?? '-',

                Carbon::parse($report->work_date)
                    ->format('d-m-Y'),

                $this->branch->name,


                /*
                |--------------------------------------------------------------------------
                | MASUK
                |--------------------------------------------------------------------------
                */

                $scheduleStart ?? '-',

                $actualCheckIn
                    ? $actualCheckIn->format('H:i:s')
                    : '-',

                $lateMinutes ?? '-',


                /*
                |--------------------------------------------------------------------------
                | PULANG
                |--------------------------------------------------------------------------
                */

                $scheduleEnd ?? '-',

                $actualCheckOut
                    ? $actualCheckOut->format('H:i:s')
                    : '-',


                /*
                |--------------------------------------------------------------------------
                | RADIUS CABANG
                |--------------------------------------------------------------------------
                */

                $this->branch->radius ?? '-',


                /*
                |--------------------------------------------------------------------------
                | GPS MASUK
                |--------------------------------------------------------------------------
                */

                $attendance?->check_in_distance !== null
                    ? round(
                        $attendance->check_in_distance,
                        2
                    )
                    : '-',

                $attendance?->check_in_accuracy !== null
                    ? round(
                        $attendance->check_in_accuracy,
                        2
                    )
                    : '-',


                /*
                |--------------------------------------------------------------------------
                | GPS PULANG
                |--------------------------------------------------------------------------
                */

                $attendance?->check_out_distance !== null
                    ? round(
                        $attendance->check_out_distance,
                        2
                    )
                    : '-',

                $attendance?->check_out_accuracy !== null
                    ? round(
                        $attendance->check_out_accuracy,
                        2
                    )
                    : '-',


                /*
                |--------------------------------------------------------------------------
                | KOORDINAT MASUK
                |--------------------------------------------------------------------------
                */

                $attendance?->check_in_latitude
                    ?? '-',

                $attendance?->check_in_longitude
                    ?? '-',


                /*
                |--------------------------------------------------------------------------
                | KOORDINAT PULANG
                |--------------------------------------------------------------------------
                */

                $attendance?->check_out_latitude
                    ?? '-',

                $attendance?->check_out_longitude
                    ?? '-',


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $this->statusLabel(
                    $status
                ),
            ]);
        }


        return $rows;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS REPORT
    |--------------------------------------------------------------------------
    */

    protected function getReportStatus(
        $report
    ): string {

        /*
        |--------------------------------------------------------------------------
        | OFF
        |--------------------------------------------------------------------------
        */

        if ($report->status === 'off') {
            return 'off';
        }


        /*
        |--------------------------------------------------------------------------
        | IZIN
        |--------------------------------------------------------------------------
        */

        if ($report->status === 'leave') {
            return 'leave';
        }


        /*
        |--------------------------------------------------------------------------
        | SUDAH ABSEN
        |--------------------------------------------------------------------------
        */

        if ($report->attendance?->check_in_at) {

            if (
                $report->attendance?->status === 'late'
            ) {
                return 'late';
            }

            return 'present';
        }


        /*
        |--------------------------------------------------------------------------
        | TIDAK HADIR - HARI SEBELUMNYA
        |--------------------------------------------------------------------------
        */

        $workDate = Carbon::parse(
            $report->work_date
        );

        if (
            $workDate
                ->copy()
                ->startOfDay()
                ->lt(Carbon::today())
        ) {
            return 'absent';
        }


        /*
        |--------------------------------------------------------------------------
        | TIDAK HADIR - HARI INI SUDAH LEWAT JAM PULANG
        |--------------------------------------------------------------------------
        */

        if (
            $workDate->isToday() &&
            $report->end_time
        ) {

            $scheduleEnd = Carbon::parse(

                $workDate->format('Y-m-d')
                . ' '
                . Carbon::parse(
                    $report->end_time
                )->format('H:i:s')

            );


            if (now()->greaterThan($scheduleEnd)) {
                return 'absent';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | MASIH TERJADWAL
        |--------------------------------------------------------------------------
        */

        return 'scheduled';
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS
    |--------------------------------------------------------------------------
    */

    protected function statusLabel(
        string $status
    ): string {

        return match ($status) {

            'present' => 'Hadir',

            'late' => 'Terlambat',

            'absent' => 'Tidak Hadir',

            'leave' => 'Izin',

            'off' => 'OFF',

            'scheduled' => 'Terjadwal',

            default => '-',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | NAMA SHEET
    |--------------------------------------------------------------------------
    */

    public function title(): string
    {
        /*
        |--------------------------------------------------------------------------
        | Pakai ID supaya nama sheet tidak duplicate
        |--------------------------------------------------------------------------
        */

        $title =
            $this->branch->id
            . ' - '
            . $this->branch->name;


        /*
        |--------------------------------------------------------------------------
        | Karakter terlarang nama sheet Excel
        |--------------------------------------------------------------------------
        */

        $title = preg_replace(
            '/[\\\\\/\?\*\[\]\:]/',
            '-',
            $title
        );


        /*
        |--------------------------------------------------------------------------
        | Maksimal 31 karakter
        |--------------------------------------------------------------------------
        */

        return mb_substr(
            $title,
            0,
            31
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STYLE EXCEL
    |--------------------------------------------------------------------------
    */

    public function registerEvents(): array
    {
        return [

            AfterSheet::class =>
                function (AfterSheet $event): void {

                    $sheet =
                        $event->sheet
                            ->getDelegate();

                    $highestRow =
                        $sheet->getHighestRow();


                    /*
                    |--------------------------------------------------------------------------
                    | FREEZE HEADER
                    |--------------------------------------------------------------------------
                    */

                    $sheet->freezePane('A2');


                    /*
                    |--------------------------------------------------------------------------
                    | AUTO FILTER
                    |--------------------------------------------------------------------------
                    */

                    $sheet->setAutoFilter(
                        'A1:T' . $highestRow
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | HEADER
                    |--------------------------------------------------------------------------
                    */

                    $headerStyle =
                        $sheet->getStyle(
                            'A1:T1'
                        );

                    $headerStyle
                        ->getFont()
                        ->setBold(true)
                        ->getColor()
                        ->setARGB('FFFFFFFF');

                    $headerStyle
                        ->getFill()
                        ->setFillType(
                            Fill::FILL_SOLID
                        );

                    $headerStyle
                        ->getFill()
                        ->getStartColor()
                        ->setARGB(
                            'FFDC2626'
                        );

                    $headerStyle
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $headerStyle
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | HEIGHT HEADER
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getRowDimension(1)
                        ->setRowHeight(30);


                    /*
                    |--------------------------------------------------------------------------
                    | ALIGNMENT DATA
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A2:T' . $highestRow
                        )
                        ->getAlignment()
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | CENTER KOLOM TERTENTU
                    |--------------------------------------------------------------------------
                    */

                    $sheet
                        ->getStyle(
                            'A2:A' . $highestRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $sheet
                        ->getStyle(
                            'D2:T' . $highestRow
                        )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | LEBAR KOLOM
                    |--------------------------------------------------------------------------
                    */

                    $widths = [

                        'A' => 6,

                        'B' => 24,

                        'C' => 30,

                        'D' => 14,

                        'E' => 25,

                        'F' => 14,

                        'G' => 14,

                        'H' => 18,

                        'I' => 14,

                        'J' => 14,

                        'K' => 20,

                        'L' => 18,

                        'M' => 24,

                        'N' => 18,

                        'O' => 24,

                        'P' => 18,

                        'Q' => 18,

                        'R' => 18,

                        'S' => 18,

                        'T' => 16,

                    ];


                    foreach (
                        $widths as $column => $width
                    ) {

                        $sheet
                            ->getColumnDimension(
                                $column
                            )
                            ->setWidth(
                                $width
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | WARNA STATUS
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = 2;
                        $row <= $highestRow;
                        $row++
                    ) {

                        $cell =
                            $sheet->getCell(
                                'T' . $row
                            );

                        $status =
                            $cell->getValue();


                        if ($status === 'Hadir') {

                            $cell
                                ->getStyle()
                                ->getFont()
                                ->getColor()
                                ->setARGB(
                                    'FF15803D'
                                );

                        } elseif (
                            $status === 'Terlambat'
                        ) {

                            $cell
                                ->getStyle()
                                ->getFont()
                                ->getColor()
                                ->setARGB(
                                    'FFEA580C'
                                );

                        } elseif (
                            $status === 'Tidak Hadir'
                        ) {

                            $cell
                                ->getStyle()
                                ->getFont()
                                ->getColor()
                                ->setARGB(
                                    'FFDC2626'
                                );

                        }

                        $cell
                            ->getStyle()
                            ->getFont()
                            ->setBold(true);
                    }

                },

        ];
    }
}