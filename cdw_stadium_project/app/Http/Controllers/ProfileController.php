<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Hiển thị màn hình hồ sơ cá nhân người dùng.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Lấy gói thành viên đang hoạt động (nếu có)
        $activeSubscription = $user->subscriptions()
            ->with('plan')
            ->where('status', true)
            ->where('expires_at', '>=', now())
            ->latest('id')
            ->first();

        // Thống kê nhanh hoạt động
        $favoritesCount = $user->favoriteCourts()->count();
        $bookingsCount = $user->bookings()->count();

        return view('web.profile', compact('user', 'activeSubscription', 'favoritesCount', 'bookingsCount'));
    }

    /**
     * Cập nhật thông tin hồ sơ cá nhân.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Cập nhật thông tin cá nhân thành công!')
            ->with('active_tab', 'profile');
    }

    /**
     * Đổi mật khẩu tài khoản cá nhân.
     */
    public function updatePassword(ProfilePasswordUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Đổi mật khẩu thành công!')
            ->with('active_tab', 'password');
    }
}
