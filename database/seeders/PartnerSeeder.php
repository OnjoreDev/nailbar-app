<?php
namespace Database\Seeders;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Partner profile for the Super User (User ID 1)
        $superUser = User::find(1);
        if ($superUser) {
            Partner::create([
                'user_id' => $superUser->id,
                'name' => $superUser->name, // Matches user table name[cite: 3, 5]
                'description' => 'Platform administration and global oversight partner profile.',
                'location' => 'HQ, Nairobi',
                'business_name' => 'SaaS Global Admin',
                'telNo' => '+254700000000',
                'status' => 'active',
            ]);
        }

        // 2. Create Partner profiles for the remaining 10 users
        $otherUsers = User::where('id', '>', 1)->get();

        $businesses = [
            ['business_name' => 'Glow & Grace Spa', 'location' => 'Westlands, Nairobi', 'telNo' => '+254712345671', 'description' => 'Luxury wellness and nail care studio.'],
            ['business_name' => 'Urban Glow Studio', 'location' => 'Kilimani, Nairobi', 'telNo' => '+254712345672', 'description' => 'Modern lifestyle and aesthetic lounge.'],
            ['business_name' => 'Royal Touch Parlour', 'location' => 'Karen, Nairobi', 'telNo' => '+254712345673', 'description' => 'Exclusive grooming and spa services.'],
            ['business_name' => 'Elite Grooming Hub', 'location' => 'CBD, Nairobi', 'telNo' => '+254712345674', 'description' => 'Professional barbering and manicure hub.'],
            ['business_name' => 'Blissful Nails & Beauty', 'location' => 'Lavington, Nairobi', 'telNo' => '+254712345675', 'description' => 'Specialized nail artistry and care.'],
            ['business_name' => 'Fresh Cuts & Spa', 'location' => 'Thika Road, Nairobi', 'telNo' => '+254712345676', 'description' => 'Trendy haircuts and soothing pedicures.'],
            ['business_name' => 'Velvet Lounge', 'location' => 'Parklands, Nairobi', 'telNo' => '+254712345677', 'description' => 'High-end relaxation and self-care.'],
            ['business_name' => 'Safari Barber & Spa', 'location' => 'Ngong Road, Nairobi', 'telNo' => '+254712345678', 'description' => 'Classic grooming with a modern twist.'],
            ['business_name' => 'Pure Elegance', 'location' => 'Runda, Nairobi', 'telNo' => '+254712345679', 'description' => 'Premium beauty treatments for professionals.'],
            ['business_name' => 'Urban Roots Studio', 'location' => 'South B, Nairobi', 'telNo' => '+254712345680', 'description' => 'Organic skincare and nail treatments.'],
        ];

        foreach ($otherUsers as $index => $user) {
            $biz = $businesses[$index];
            Partner::create([
                'user_id' => $user->id,
                'name' => $user->name, // Matches the corresponding user's name[cite: 3, 5]
                'description' => $biz['description'],
                'location' => $biz['location'],
                'business_name' => $biz['business_name'],
                'telNo' => $biz['telNo'],
                'status' => 'active',
            ]);
        }
    }
}