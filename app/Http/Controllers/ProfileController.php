<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        $user->load('branch');

        return view('profile.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
        ]);


        $user->update($validated);


        return back()->with(
            'success',
            'Profil berhasil diperbarui.'
        );
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);


        auth()->user()->update([
            'password' => Hash::make(
                $request->password
            ),
        ]);


        return back()->with(
            'password_success',
            'Password berhasil diubah.'
        );
    }
}