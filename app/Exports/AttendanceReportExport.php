<?php

namespace App\Exports;

use App\Exports\Sheets\BranchAttendanceSheet;
use App\Models\Branch;
use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AttendanceReportExport implements Export, WithMultipleSheets
{
    public function __construct(
        protected int $userId,
        protected string $userRole,
        protected ?int $userBranchId,
        protected string $startDate,
        protected string $endDate,
        protected ?int $branchId = null,
        protected ?string $status = null,
    ) {
    }

    public function sheets(): array
    {
        $query = Branch::query()
            ->orderBy('name');

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if ($this->userRole === 'admin') {

            if ($this->branchId) {
                $query->where('id', $this->branchId);
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | NON ADMIN
            |--------------------------------------------------------------------------
            */

            $query->where('id', $this->userBranchId);
        }

        $branches = $query->get();

        $sheets = [];

        foreach ($branches as $branch) {

            $sheets[] = new BranchAttendanceSheet(
                branch: $branch,
                userId: $this->userId,
                userRole: $this->userRole,
                startDate: $this->startDate,
                endDate: $this->endDate,
                status: $this->status,
            );
        }

        return $sheets;
    }
}