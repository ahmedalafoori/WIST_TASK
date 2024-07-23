<?php
// app/Http/Controllers/UserProfileController.php
// app/Http/Controllers/UserProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    // عرض صفحة التعديل
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // تحديث البيانات الشخصية
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update($request->only('name', 'email')); // استخدام only لضمان تحديث الحقول فقط

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }


    // تحديث كلمة المرور
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password', // تحقق من أن كلمة المرور الجديدة مختلفة عن الحالية
        ]);

        $user = Auth::user();

        // تحقق من أن كلمة المرور الحالية صحيحة
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        // تحديث كلمة المرور الجديدة
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

}
