<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();

        // assign admin role to the first user
        // $user = User::find();
        // Log::info('UserSeeder: Assigning admin role to the first user', ['user' => $user]);
        // $user->assignRole('admin');
    }
}
