<?php

namespace Database\Seeders;

use App\Models\AccountLetters;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(ItemsEgresos::class);
        $this->call(ItemsIngresos::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(RolePermissionSecondSeeder::class);
        // Account Letter
//        AccountLetters::create([
//            'bank_name'=> 'EFECTIVO',
//            'account_number' => 00000000,
//            'account_type' => 'Savings bank',
//            'currency_type' => 'Bs',
//            'initial_account_amount' => 0.00,
//        ]);

//        User Test
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//            'password' => bcrypt('password'),
//        ]);
    }
}
