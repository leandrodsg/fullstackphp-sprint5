<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index() {
        $subscriptions = Subscription::all();
        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create() {
        return view('subscriptions.create');
    }

    public function store(Request $request) {
        Subscription::create($request->all());
        return redirect()->route('subscriptions.index');
    }

    public function show(string $id) {
        $subscription = Subscription::find($id);
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit(string $id) {
        $subscription = Subscription::find($id);
        return view('subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, string $id) {
        $subscription = Subscription::find($id);
        $subscription->update($request->all());
        return redirect()->route('subscriptions.index');
    }

    public function destroy(string $id) {
        Subscription::find($id)->delete();
        return redirect()->route('subscriptions.index');
    }
}