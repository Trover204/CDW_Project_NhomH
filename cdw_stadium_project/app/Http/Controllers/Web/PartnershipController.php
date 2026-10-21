<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnershipRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnershipController extends Controller
{
    public function index(): View
    {
        return view('web.partnership.index');
    }

    public function checkout(): View|RedirectResponse
    {
        $user = Auth::user();

        if (in_array($user->role, ['ctv', 'pending_ctv'], true)) {
            return redirect()->route('partnership.index');
        }

        return view('web.partnership.checkout');
    }

    public function process(PartnershipRequest $request): RedirectResponse
    {
        $request->validated();

        $user = Auth::user();

        if (! in_array($user->role, ['ctv', 'pending_ctv'], true)) {
            $user->update(['role' => 'pending_ctv']);
        }

        return redirect()
            ->route('partnership.index')
            ->with('success', 'Đăng ký làm Cộng tác viên thành công. Vui lòng chờ Admin phê duyệt.');
    }
}
