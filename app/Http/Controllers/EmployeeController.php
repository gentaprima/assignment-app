<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar karyawan.
     */
    public function index(Request $request)
    {
        $query = User::with('branch')
            ->orderBy('name');

        // Search nama / email
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");

            });
        }

        $employees = $query->paginate(10);

        $totalEmployees = User::whereIn('role', ['employee','pic'])->count();

        $activeEmployees = User::where('is_active', true)
                                ->whereIn('role', ['employee','pic'])
                                ->count();

        $picCount = User::where('role', 'pic')->count();

        $branchCount = Branch::count();

        $branches = Branch::orderBy('name')->get();

        return view('employees.index', compact(
            'employees',
            'activeEmployees',
            'picCount',
            'branchCount',
            'branches',
            'totalEmployees'
        ));
    }


    /**
     * Simpan karyawan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'manager',
                    'pic',
                    'employee',
                ]),
            ],

            'branch_id' => [
                'nullable',
                'exists:branches,id',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);


        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'branch_id' => $validated['branch_id'] ?? null,
            'is_active' => $validated['is_active'],
        ]);


        return redirect()
            ->route('admin.employees')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }


    /**
     * Update karyawan.
     */
    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($employee->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'manager',
                    'pic',
                    'employee',
                ]),
            ],

            'branch_id' => [
                'nullable',
                'exists:branches,id',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);


        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->role = $validated['role'];
        $employee->branch_id = $validated['branch_id'] ?? null;
        $employee->is_active = $validated['is_active'];


        // Password hanya diubah kalau diisi
        if (!empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }


        $employee->save();


        return redirect()
            ->route('admin.employees')
            ->with('success', 'Data karyawan berhasil diperbarui.');
    }


    /**
     * Hapus karyawan.
     */
    public function destroy(User $employee)
    {
        // Jangan izinkan admin menghapus dirinya sendiri
        if ($employee->id === auth()->id()) {

            return redirect()
                ->route('admin.employees')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }


        $employee->delete();


        return redirect()
            ->route('admin.employees')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}