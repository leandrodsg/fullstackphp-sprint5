<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends BaseController
{
    public function myExpenses(Request $request)
    {
        $user = Auth::user();
        
        // Get user subscriptions
        $query = Subscription::where('user_id', $user->id);
        
        // Simple status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $subscriptions = $query->get();
        
        // Calculate total expenses
        $totalExpenses = $subscriptions->sum('price');
        
        // Prepare report data
        $reportData = [
            'user_name' => $user->name,
            'total_subscriptions' => $subscriptions->count(),
            'total_expenses' => $totalExpenses,
            'currency' => $subscriptions->first()->currency ?? 'USD',
            'subscriptions' => $subscriptions->map(function ($subscription) {
                return [
                    'id' => $subscription->id,
                    'service_name' => $subscription->service->name ?? 'Unknown Service',
                    'plan' => $subscription->plan,
                    'price' => $subscription->price,
                    'currency' => $subscription->currency,
                    'status' => $subscription->status,
                    'next_billing_date' => $subscription->next_billing_date,
                ];
            })
        ];
        
        return $this->responseSuccess($reportData, 'Expense report generated successfully');
    }
}