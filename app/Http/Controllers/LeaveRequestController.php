<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\ScheduleAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = LeaveRequest::with([
            'user',
            'branch',
            'approver',
        ]);

        if ($user->role === 'employee') {

            $query->where(
                'user_id',
                $user->id
            );

        } elseif ($user->role === 'pic') {

            $query->where(
                'branch_id',
                $user->branch_id
            );

        }

        $leaveRequests = $query
            ->latest()
            ->paginate(15);

        $leaveTypes = LeaveType::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'leave-requests.index',
            compact('leaveRequests', 'leaveTypes')
        );
    }
    public function adminIndex()
    {
        $permissions = LeaveRequest::with([
            'user',
            'leaveType',
            'branch',
            'approver',
        ])
            ->latest()
            ->get();

        return view('admin.permissions', compact('permissions'));
    }
    public function approve(LeaveRequest $permission)
    {
        if ($permission->status !== 'pending') {
            return redirect()
                ->back()
                ->with('error', 'Pengajuan ini sudah diproses.');
        }

        DB::transaction(function () use ($permission) {

            // Approve pengajuan
            $permission->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            // Cari jadwal karyawan pada periode izin
            $assignments = ScheduleAssignment::where(
                'user_id',
                $permission->user_id
            )
                ->whereBetween('work_date', [
                    $permission->start_date,
                    $permission->end_date,
                ])
                ->get();

            foreach ($assignments as $assignment) {

                $assignment->update([
                    'status' => 'leave',
                    'notes' => $permission->reason,
                ]);

                // Kalau sudah ada attendance,
                // ubah status menjadi leave
                if ($assignment->attendance) {

                    $assignment->attendance->update([
                        'status' => 'leave',
                        'notes' => $permission->reason,
                    ]);

                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Pengajuan izin berhasil disetujui.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        abort_unless(
            auth()->user()->role === 'admin',
            403
        );

        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        if ($leaveRequest->status !== 'pending') {
            return back()->with(
                'error',
                'Pengajuan izin sudah diproses.'
            );
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with(
            'success',
            'Pengajuan izin ditolak.'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = auth()->user();

        $attachmentPath = null;

        // Upload attachment
        if ($request->hasFile('attachment')) {

            $attachmentPath = $request
                ->file('attachment')
                ->store('leave-attachments', 'public');
        }

        LeaveRequest::create([
            'user_id' => $user->id,

            'leave_type_id' => $request->leave_type_id,

            'branch_id' => $user->branch_id,

            'start_date' => $request->start_date,

            'end_date' => $request->end_date,

            'reason' => $request->reason,

            'attachment' => $attachmentPath,

            'status' => 'pending',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}
