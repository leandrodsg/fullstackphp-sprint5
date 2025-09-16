<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Subscription;
use App\Events\SubscriptionBillingAdvanced;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionBillingCycleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function advance_one_cycle_adds_one_month_for_monthly_plans()
    {
        Event::fake();
        
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Basic', // Monthly plan
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceOneCycle();
        
        $subscription->refresh();
        
        // Should advance by 1 month for monthly plans
        $expectedDate = $originalDate->addMonth()->format('Y-m-d');
        $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'));
        
        Event::assertDispatched(SubscriptionBillingAdvanced::class);
    }

    /** @test */
    public function advance_one_cycle_adds_one_year_for_annual_plans()
    {
        Event::fake();
        
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Premium Annual', // Annual plan
            'price' => 99.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceOneCycle();
        
        $subscription->refresh();
        
        // Should advance by 1 year for annual plans
        $expectedDate = $originalDate->addYear()->format('Y-m-d');
        $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'));
        
        Event::assertDispatched(SubscriptionBillingAdvanced::class);
    }

    /** @test */  
    public function advance_one_cycle_adds_one_year_for_yearly_plans()
    {
        Event::fake();
        
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Enterprise Yearly', // Yearly plan
            'price' => 199.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceOneCycle();
        
        $subscription->refresh();
        
        // Should advance by 1 year for yearly plans
        $expectedDate = $originalDate->addYear()->format('Y-m-d');
        $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'));
        
        Event::assertDispatched(SubscriptionBillingAdvanced::class);
    }

    /** @test */
    public function advance_one_cycle_is_case_insensitive_for_annual_detection()
    {
        Event::fake();
        
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $testCases = [
            'Premium ANNUAL',
            'basic annual',
            'Pro Annual',
            'Enterprise YEARLY',
            'starter yearly'
        ];

        foreach ($testCases as $planName) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'plan' => $planName,
                'price' => 99.99,
                'currency' => 'USD',
                'next_billing_date' => now()->format('Y-m-d'),
                'status' => 'active'
            ]);

            $originalDate = $subscription->next_billing_date;
            
            $subscription->advanceOneCycle();
            $subscription->refresh();
            
            // All should advance by 1 year regardless of case
            $expectedDate = $originalDate->addYear()->format('Y-m-d');
            $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'), 
                "Failed for plan: {$planName}");
            
            $subscription->delete(); // Clean up for next iteration
        }
    }

    /** @test */
    public function advance_one_cycle_defaults_to_monthly_for_other_plan_names()
    {
        Event::fake();
        
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $monthlyPlanNames = [
            'Basic',
            'Premium',
            'Pro',
            'Enterprise',
            'Starter',
            'Advanced Monthly', // Has "monthly" but not "annual"/"yearly"
            'Custom Plan'
        ];

        foreach ($monthlyPlanNames as $planName) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'plan' => $planName,
                'price' => 9.99,
                'currency' => 'USD',
                'next_billing_date' => now()->format('Y-m-d'),
                'status' => 'active'
            ]);

            $originalDate = $subscription->next_billing_date;
            
            $subscription->advanceOneCycle();
            $subscription->refresh();
            
            // All should default to 1 month
            $expectedDate = $originalDate->addMonth()->format('Y-m-d');
            $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'), 
                "Failed for plan: {$planName}");
            
            $subscription->delete(); // Clean up for next iteration
        }
    }

    /** @test */
    public function advance_billing_date_method_still_works_for_backward_compatibility()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'plan' => 'Premium Annual', // Annual, but advanceBillingDate always adds 1 month
            'price' => 99.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceBillingDate();
        
        $subscription->refresh();
        
        // advanceBillingDate should ALWAYS add 1 month (backward compatibility)
        $expectedDate = $originalDate->addMonth()->format('Y-m-d');
        $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'));
    }
}