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
            'plan' => 'Basic',
            'price' => 9.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceOneCycle();
        
        $subscription->refresh();
        
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
            'plan' => 'Premium Annual',
            'price' => 99.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceOneCycle();
        
        $subscription->refresh();
        
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
            'plan' => 'Enterprise Yearly',
            'price' => 199.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceOneCycle();
        
        $subscription->refresh();
        
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
            
            $expectedDate = $originalDate->addYear()->format('Y-m-d');
            $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'), 
                "Failed for plan: {$planName}");
            
            $subscription->delete();
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
            'Advanced Monthly',
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
            
            $expectedDate = $originalDate->addMonth()->format('Y-m-d');
            $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'), 
                "Failed for plan: {$planName}");
            
            $subscription->delete();
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
            'plan' => 'Premium Annual',
            'price' => 99.99,
            'currency' => 'USD',
            'next_billing_date' => now()->format('Y-m-d'),
            'status' => 'active'
        ]);

        $originalDate = $subscription->next_billing_date;
        
        $subscription->advanceBillingDate();
        
        $subscription->refresh();
        
        $expectedDate = $originalDate->addMonth()->format('Y-m-d');
        $this->assertEquals($expectedDate, $subscription->next_billing_date->format('Y-m-d'));
    }

    /** @test */
    public function calculate_billing_cycle_returns_monthly_for_30_day_difference()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'created_at' => now(),
            'next_billing_date' => now()->addDays(30),
        ]);

        $this->assertEquals('monthly', $subscription->calculateBillingCycle());
    }

    /** @test */
    public function calculate_billing_cycle_returns_annual_for_365_day_difference()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'created_at' => now(),
            'next_billing_date' => now()->addDays(365),
        ]);

        $this->assertEquals('annual', $subscription->calculateBillingCycle());
    }

    /** @test */
    public function calculate_billing_cycle_returns_monthly_for_invalid_dates()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $subscription = Subscription::factory()->create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'created_at' => null,
            'next_billing_date' => now()->addDays(30),
        ]);

        $this->assertEquals('monthly', $subscription->calculateBillingCycle());
    }

    /** @test */
    public function calculate_billing_cycle_handles_edge_cases()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['user_id' => $user->id]);
        
        $testCases = [
            ['days' => 25, 'expected' => 'monthly'],
            ['days' => 35, 'expected' => 'monthly'],
            ['days' => 329, 'expected' => 'monthly'],
            ['days' => 330, 'expected' => 'annual'],
            ['days' => 365, 'expected' => 'annual'],
            ['days' => 400, 'expected' => 'annual'],
        ];

        foreach ($testCases as $case) {
            $createdAt = now()->subDays($case['days'])->startOfDay();
            $nextBillingDate = now()->startOfDay();
            
            $subscription = Subscription::factory()->create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'created_at' => $createdAt,
                'next_billing_date' => $nextBillingDate,
            ]);

            $this->assertEquals(
                $case['expected'], 
                $subscription->calculateBillingCycle(),
                "Failed for {$case['days']} days difference"
            );
        }
    }
}