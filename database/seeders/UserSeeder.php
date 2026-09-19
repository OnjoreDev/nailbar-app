<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the Super User account (Guaranteed ID 1)
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@saasplatform.co.ke',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create the other 10 regular users
        $users = [
            ['name' => 'Sarah Jenkins', 'email' => 'sarah@glowspa.co.ke'],
            ['name' => 'Michael Mwangi', 'email' => 'michael@urbanglow.co.ke'],
            ['name' => 'Amina Otieno', 'email' => 'amina@royaltouch.co.ke'],
            ['name' => 'David Kimani', 'email' => 'david@elitegrooming.co.ke'],
            ['name' => 'Grace Wanjiku', 'email' => 'grace@blissfulnails.co.ke'],
            ['name' => 'Kevin Ochieng', 'email' => 'kevin@freshcuts.co.ke'],
            ['name' => 'Brenda Chebet', 'email' => 'brenda@velvetlounge.co.ke'],
            ['name' => 'Brian Juma', 'email' => 'brian@safaribarber.co.ke'],
            ['name' => 'Esther Akinyi', 'email' => 'esther@pureelegance.co.ke'],
            ['name' => 'Daniel Mutua', 'email' => 'daniel@urbanroots.co.ke'],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password123'),
            ]);
        }
    }
}