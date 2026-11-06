<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Merchant;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure role exists
        Role::firstOrCreate(['name' => 'merchant']);

        // 1. Create Test Merchant User
        $user = User::firstOrCreate(
            ['email' => 'tech@nailpro.test'],
            [
                'name' => 'Brenda Nails',
                'password' => Hash::make('password123'),
            ]
        );

        $user->assignRole('merchant');

        // 2. Create Merchant Studio Profile
        $merchant = Merchant::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => 'Glamour Nail Bar',
                'slug' => 'glam-nails',
                'phone' => '0712345678',
                'bio' => 'Professional acrylics, gel manicures, and custom nail art in Nairobi.',
            ]
        );

        // 3. Create Sample Services for this Merchant
        Service::firstOrCreate(
            ['merchant_id' => $merchant->id, 'name' => 'Acrylic Full Set'],
            [
                'price' => 2500.00,
                'description' => 'Long-lasting acrylic extensions with your choice of gel polish color.',
            ]
        );

        Service::firstOrCreate(
            ['merchant_id' => $merchant->id, 'name' => 'Gel Manicure & Pedicure'],
            [
                'price' => 3000.00,
                'description' => 'Complete cuticle care, exfoliation, massage, and high-shine gel finish.',
            ]
        );
    }
}
