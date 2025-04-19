<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use App\Models\Expense;
use App\Models\ExpenseShare;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        // Get the admin user (your account)
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            // Create admin user if not exists
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('Admin@1234')
            ]);
        }

        // Get test users
        $john = User::where('email', 'john@example.com')->first();
        $jane = User::where('email', 'jane@example.com')->first();
        $bob = User::where('email', 'bob@example.com')->first();

        // Create a test group
        $group = Group::create([
            'name' => 'Weekend Trip',
            'description' => 'Expenses for our weekend trip to the beach',
            'created_by' => $admin->id
        ]);

        // Add members to the group
        $group->members()->attach([
            $admin->id,
            $john->id,
            $jane->id,
            $bob->id
        ]);

        // Create some expenses
        $expenses = [
            [
                'title' => 'Hotel Booking',
                'amount' => 1000,
                'payer_id' => $admin->id,
                'shares' => [
                    $john->id => 250,
                    $jane->id => 250,
                    $bob->id => 250,
                    $admin->id => 250
                ]
            ],
            [
                'title' => 'Dinner',
                'amount' => 400,
                'payer_id' => $jane->id,
                'shares' => [
                    $john->id => 100,
                    $jane->id => 100,
                    $bob->id => 100,
                    $admin->id => 100
                ]
            ]
        ];

        foreach ($expenses as $expenseData) {
            $expense = Expense::create([
                'group_id' => $group->id,
                'title' => $expenseData['title'],
                'amount' => $expenseData['amount'],
                'payer_id' => $expenseData['payer_id']
            ]);

            foreach ($expenseData['shares'] as $userId => $amount) {
                ExpenseShare::create([
                    'expense_id' => $expense->id,
                    'user_id' => $userId,
                    'amount' => $amount,
                    'is_paid' => false
                ]);
            }
        }

        $this->command->info('Test groups and expenses created successfully!');
    }
} 