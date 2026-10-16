<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // R - Danh sách
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // C - Form thêm
    public function create()
    {
        return view('admin.users.create');
    }

    // C - Lưu
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        // Handle avatar upload if necessary later

        User::create($validated);
        return redirect()->route('admin.users.index')->with('success', 'Đã thêm người dùng.');
    }

    // R - Xem chi tiết (không dùng view riêng, quay về danh sách)
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    // U - Form sửa
    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    // U - Cập nhật
    public function update(UserRequest $request, User $user)
    {
        $validated = $request->validated();

        if (isset($validated['password']) && !empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle avatar upload if necessary later

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật người dùng.');
    }

    // D - Xóa
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng.');
    }
}
