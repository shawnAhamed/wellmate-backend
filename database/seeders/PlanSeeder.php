<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'billing_interval' => 'none',
                'monthly_question_limit' => 3,
                'consultation_access' => false,
            ],
            [
                'name' => 'Monthly',
                'slug' => 'monthly',
                'price' => 9.99,
                'billing_interval' => 'monthly',
                'monthly_question_limit' => null,
                'consultation_access' => true,
            ],
            [
                'name' => 'Yearly',
                'slug' => 'yearly',
                'price' => 99.99,
                'billing_interval' => 'yearly',
                'monthly_question_limit' => null,
                'consultation_access' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
