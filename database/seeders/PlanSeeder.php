<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'plan_name' => 'Basic Plan',
                'price' => 29.99,
                'sale_price' => null,
                'frequency' => 'monthly',
                'plan_id' => 'basic_monthly',
                'short_description' => 'Perfect for small businesses getting started',
                'description' => 'Our Basic Plan includes all essential features to get your business started. Perfect for small businesses and startups looking to establish their online presence.',
            ],
            [
                'plan_name' => 'Professional Plan',
                'price' => 79.99,
                'sale_price' => 59.99,
                'frequency' => 'monthly',
                'plan_id' => 'pro_monthly',
                'short_description' => 'Advanced features for growing businesses',
                'description' => 'The Professional Plan offers advanced features and tools designed for growing businesses. Includes priority support and advanced analytics.',
            ],
            [
                'plan_name' => 'Enterprise Plan',
                'price' => 199.99,
                'sale_price' => null,
                'frequency' => 'monthly',
                'plan_id' => 'enterprise_monthly',
                'short_description' => 'Complete solution for large organizations',
                'description' => 'Our Enterprise Plan provides a complete solution for large organizations. Includes unlimited features, dedicated support, and custom integrations.',
            ],
            [
                'plan_name' => 'Basic Annual',
                'price' => 299.99,
                'sale_price' => 249.99,
                'frequency' => 'yearly',
                'plan_id' => 'basic_yearly',
                'short_description' => 'Save 17% with annual billing',
                'description' => 'Get the same Basic Plan features but pay annually and save 17%. Perfect for businesses looking to reduce costs.',
            ],
            [
                'plan_name' => 'Professional Annual',
                'price' => 799.99,
                'sale_price' => 599.99,
                'frequency' => 'yearly',
                'plan_id' => 'pro_yearly',
                'short_description' => 'Save 25% with annual billing',
                'description' => 'Upgrade to our Professional Plan with annual billing and save 25%. Includes all monthly features plus additional savings.',
            ],
            [
                'plan_name' => 'One-Time Setup',
                'price' => 499.99,
                'sale_price' => null,
                'frequency' => 'one-time',
                'plan_id' => 'setup_one_time',
                'short_description' => 'One-time setup and configuration',
                'description' => 'Perfect for businesses that need a one-time setup and configuration service. Includes initial setup, training, and 30 days of support.',
            ],
        ];

        foreach ($plans as $planData) {
            Plan::create($planData);
        }
    }
}
