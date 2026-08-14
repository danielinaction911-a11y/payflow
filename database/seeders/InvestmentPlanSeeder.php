<?php

namespace Database\Seeders;

use App\Models\InvestmentPlan;
use App\Enums\RoiType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvestmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter Plan',
                'description' => 'Perfect for first-time investors looking to test the waters with a low-risk, short-term plan.',
                'min_amount' => 50,
                'max_amount' => 499,
                'roi_percentage' => 1.5,
                'duration_days' => 7,
                'roi_type' => RoiType::Daily,
                'features' => [
                    '1.5% daily returns',
                    '7 days duration',
                    'Capital returned at end of plan',
                    'Instant activation',
                    '24/7 support access',
                ],
                'is_popular' => false,
                'capital_back' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Bronze Plan',
                'description' => 'A steady entry-level plan for investors ready to commit a little more for better returns.',
                'min_amount' => 500,
                'max_amount' => 1999,
                'roi_percentage' => 2.0,
                'duration_days' => 14,
                'roi_type' => RoiType::Daily,
                'features' => [
                    '2% daily returns',
                    '14 days duration',
                    'Capital returned at end of plan',
                    'Priority email support',
                    'Access to market signals',
                ],
                'is_popular' => false,
                'capital_back' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Silver Plan',
                'description' => 'Our most popular mid-tier plan, balancing solid returns with manageable investment duration.',
                'min_amount' => 2000,
                'max_amount' => 4999,
                'roi_percentage' => 2.75,
                'duration_days' => 21,
                'roi_type' => RoiType::Daily,
                'features' => [
                    '2.75% daily returns',
                    '21 days duration',
                    'Capital returned at end of plan',
                    'Priority live chat support',
                    'Access to trading signals',
                    'Dedicated account manager',
                ],
                'is_popular' => true,
                'capital_back' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Gold Plan',
                'description' => 'Designed for serious investors seeking higher returns over a committed monthly cycle.',
                'min_amount' => 5000,
                'max_amount' => 14999,
                'roi_percentage' => 35,
                'duration_days' => 30,
                'roi_type' => RoiType::Monthly,
                'features' => [
                    '35% return at month end',
                    '30 days duration',
                    'Capital returned at end of plan',
                    'Dedicated account manager',
                    'Advanced trading signal access',
                    'Early withdrawal option (fee applies)',
                ],
                'is_popular' => false,
                'capital_back' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Platinum Plan',
                'description' => 'A high-yield plan built for experienced investors ready to commit larger capital.',
                'min_amount' => 15000,
                'max_amount' => 49999,
                'roi_percentage' => 55,
                'duration_days' => 30,
                'roi_type' => RoiType::Monthly,
                'features' => [
                    '55% return at month end',
                    '30 days duration',
                    'Capital returned at end of plan',
                    'VIP account manager',
                    'Real-time trading signal alerts',
                    'Weekly portfolio review call',
                    'Priority withdrawal processing',
                ],
                'is_popular' => false,
                'capital_back' => true,
                'status' => 'active',
            ],
            [
                'name' => 'VIP Elite Plan',
                'description' => 'Our top-tier plan for high-net-worth investors seeking maximum returns and white-glove service.',
                'min_amount' => 50000,
                'max_amount' => 400000,
                'roi_percentage' => 80,
                'duration_days' => 30,
                'roi_type' => RoiType::Monthly,
                'features' => [
                    '80% return at month end',
                    '30 days duration',
                    'Capital returned at end of plan',
                    'Personal VIP account manager',
                    'Real-time trading signal alerts',
                    'Weekly 1-on-1 strategy call',
                    'Instant withdrawal processing',
                    'Exclusive access to new signal launches',
                ],
                'is_popular' => false,
                'capital_back' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Quick Flip Plan',
                'description' => 'A short, one-time payout plan for investors who want fast turnaround without a daily accrual cycle.',
                'min_amount' => 100,
                'max_amount' => 999,
                'roi_percentage' => 12,
                'duration_days' => 3,
                'roi_type' => RoiType::OneTime,
                'features' => [
                    '12% one-time return',
                    '3 days duration',
                    'Capital returned at end of plan',
                    'Fastest payout cycle available',
                ],
                'is_popular' => false,
                'capital_back' => true,
                'status' => 'active',
            ],
        ];

        foreach ($plans as $plan) {
            InvestmentPlan::updateOrCreate(
                ['slug' => Str::slug($plan['name'])],
                array_merge($plan, ['slug' => Str::slug($plan['name'])])
            );
        }
    }
}