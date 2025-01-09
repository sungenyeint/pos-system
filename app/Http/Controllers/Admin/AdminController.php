<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminStoreRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $admins = Admin::orderBy('updated_at', 'DESC');

        if ($request->filled('name')) {
            $admins->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $admins->where('email', 'like', '%' . $request->email . '%');
        }

        $admins = $admins->paginate(config('const.default_paginate_number'));

        return view('admin.admins.index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminStoreRequest $request)
    {
        Admin::create($request->validated());

        return redirect()
            ->route('admin.admins.index')
            ->with('alert.success', '管理者を作成しました。');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', [
            'admin' => $admin
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateRequest $request, Admin $admin)
    {
        $admin->fill($request->validated())->save();

        return redirect()
            ->route('admin.admins.index')
            ->with('alert.success', '管理者を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return back()->with('alert.success', '管理者を削除しました。');
    }
}
