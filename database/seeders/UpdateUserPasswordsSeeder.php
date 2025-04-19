<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateUserPasswordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing users' passwords
        User::query()->update([
            'password' => Hash::make('Admin@1234')
        ]);

        $this->command->info('All user passwords have been updated to Admin@1234');
    }
} 