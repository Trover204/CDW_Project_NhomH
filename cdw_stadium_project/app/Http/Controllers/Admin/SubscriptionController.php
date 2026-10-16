<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user:id,name', 'plan:id,name'])->latest()->paginate(10);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return view('admin.subscriptions.create', [
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'plans' => Plan::select('id', 'name')->get(),
        ]);
    }

    public function store(SubscriptionRequest $request)
    {
        Subscription::create($request->validated());

        return redirect()->route('admin.subscriptions.index')->with('success', 'Đã thêm subscription.');
    }

    public function show(Subscription $subscription)
    {
        return redirect()->route('admin.subscriptions.edit', $subscription);
    }

    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', [
            'subscription' => $subscription,
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'plans' => Plan::select('id', 'name')->get(),
        ]);
    }

    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->validated());

        return redirect()->route('admin.subscriptions.index')->with('success', 'Đã cập nhật subscription.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Đã xóa subscription.');
    }
}
