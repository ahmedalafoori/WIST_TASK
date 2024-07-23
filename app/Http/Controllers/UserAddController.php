<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;

class UserAddController extends Controller
{
    // عرض قائمة المستخدمين
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // عرض نموذج إضافة مستخدم جديد
    public function create()
    {
        $roles = Role::all(); // احصل على جميع الصلاحيات
        return view('users.create', compact('roles')); // مرر الصلاحيات إلى العرض
    }

    // حفظ مستخدم جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // تخزين المستخدم الجديد
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->attach($validated['roles']);

        return redirect()->route('users.index')->with('success', 'User added successfully.');
    }



    // عرض نموذج تعديل مستخدم
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // احصل على جميع الصلاحيات
        $userRoles = $user->roles->pluck('id')->toArray(); // احصل على صلاحيات المستخدم الحالية
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    // تحديث بيانات مستخدم
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    // حذف مستخدم
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    // إدارة الصلاحيات
    public function managePermissions($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // احصل على جميع الصلاحيات
        return view('users.permissions', compact('user', 'roles'));
    }

    // تحديث الصلاحيات
    public function updatePermissions(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->roles()->sync([$request->role]); // قم بتحديث صلاحية واحدة

        return redirect()->route('users.index')->with('success', 'Permissions updated successfully.');
    }

}
