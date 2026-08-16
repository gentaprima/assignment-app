<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::withCount('users')
            ->orderBy('name');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");

            });
        }

        $branches = $query->paginate(10);

        $totalBranches = Branch::count();

        $activeBranches = Branch::where('is_active', true)->count();

        $inactiveBranches = Branch::where('is_active', false)->count();

        $totalEmployees = Branch::withCount('users')
            ->get()
            ->sum('users_count');

        return view('admin.branches.index', compact(
            'branches',
            'totalBranches',
            'activeBranches',
            'inactiveBranches',
            'totalEmployees'
        ));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:10',
                'unique:branches,code',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'radius' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);


        Branch::create($validated);


        return redirect()
            ->route('admin.branches')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }


    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'radius' => [
                'required',
                'integer',
                'min:10',
                'max:1000',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);


        $branch->update($validated);


        return redirect()
            ->route('admin.branches')
            ->with('success', 'Cabang berhasil diperbarui.');
    }


    public function destroy(Branch $branch)
    {
        if ($branch->users()->exists()) {

            return redirect()
                ->route('admin.branches')
                ->with(
                    'error',
                    'Cabang tidak dapat dihapus karena masih memiliki karyawan.'
                );
        }


        $branch->delete();


        return redirect()
            ->route('admin.branches')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}