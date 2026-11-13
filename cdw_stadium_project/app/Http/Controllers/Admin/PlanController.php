<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(10);

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(PlanRequest $request)
    {
        $data = $request->validated();
        $data['discount_percent'] = (int) ($data['discount_percent'] ?? 0);
        $data['status'] = $request->has('status') ? $request->boolean('status') : true;

        Plan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Đã thêm plan.');
    }

    public function show(Plan $plan)
    {
        return redirect()->route('admin.plans.edit', $plan);
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(PlanRequest $request, Plan $plan)
    {
        $data = $request->validated();
        $data['discount_percent'] = (int) ($data['discount_percent'] ?? 0);
        $data['status'] = $request->has('status') ? $request->boolean('status') : (bool) $plan->status;

        $affectedRows = $plan->updateWithOptimisticLock($data);

        if ($affectedRows === 0) {
            return redirect()->back()->with('error', Plan::getOptimisticLockErrorMessage());
        }

        return redirect()->route('admin.plans.index')->with('success', 'Đã cập nhật plan.');
    }

    public function destroy(Plan $plan)
    {
        // Check for related subscriptions before deleting
        if ($plan->subscriptions()->exists()) {
            return redirect()->route('admin.plans.index')->with('error', 'Không thể xóa plan vì đã có subscription liên kết.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Đã xóa plan.');
    }
}
