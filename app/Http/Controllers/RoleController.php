<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        Role::create([
            'uuid' => Str::uuid(),
            'nama_role' => $request->nama_role,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('roles.form', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'nama_role' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        $role->update($request->only('nama_role', 'status'));

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
