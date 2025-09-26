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
        
        $query = Subscription::where('user_id', $user->id);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $subscriptions = $query->get();
        
        $totalExpenses = $subscriptions->sum('price');
        
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

    public function exportMyExpenses(Request $request)
    {
        $user = Auth::user();
        
        $query = Subscription::where('user_id', $user->id);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $subscriptions = $query->get();
        
        $csvContent = "Service Name,Plan,Price,Currency,Status,Next Billing Date\n";
        
        foreach ($subscriptions as $subscription) {
            $serviceName = $subscription->service->name ?? 'Unknown Service';
            $plan = $subscription->plan;
            $price = $subscription->price;
            $currency = $subscription->currency;
            $status = $subscription->status;
            $nextBillingDate = $subscription->next_billing_date;
            
            $csvContent .= "{$serviceName},{$plan},{$price},{$currency},{$status},{$nextBillingDate}\n";
        }
        
        $filename = 'expenses_' . date('Y_m_d') . '.csv';
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}