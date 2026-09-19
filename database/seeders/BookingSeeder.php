<?php
namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Partner;
use App\Models\Service;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $partners = Partner::all();
        $options = ['manicure', 'pedicure', 'both'];

        $clients = [
            ['name' => 'Alice Akinyi', 'email' => 'alice@example.com', 'phone' => '+254711111111'],
            ['name' => 'Brian Ochieng', 'email' => 'brian@example.com', 'phone' => '+254722222222'],
            ['name' => 'Cynthia Mutheu', 'email' => 'cynthia@example.com', 'phone' => '+254733333333'],
            ['name' => 'Daniel Kiprono', 'email' => 'daniel@example.com', 'phone' => '+254744444444'],
            ['name' => 'Faith Njeri', 'email' => 'faith@example.com', 'phone' => '+254755555555'],
            ['name' => 'George Odhiambo', 'email' => 'george@example.com', 'phone' => '+254766666666'],
            ['name' => 'Hannah Wambui', 'email' => 'hannah@example.com', 'phone' => '+254777777777'],
            ['name' => 'Ian Koech', 'email' => 'ian@example.com', 'phone' => '+254788888888'],
            ['name' => 'Joy Wangari', 'email' => 'joy@example.com', 'phone' => '+254799999999'],
            ['name' => 'Kenneth Maina', 'email' => 'kenneth@example.com', 'phone' => '+254700000000'],
        ];

        foreach ($clients as $index => $client) {
            // Pick a random partner
            $partner = $partners->random();
            $chosenOption = $options[array_rand($options)];

            // Get services that belong specifically to this partner (or pluck from all services if preferred)
            $partnerServices = Service::where('partner_id', $partner->id)->pluck('name');

            // Fallback if a partner has no services yet, otherwise pick 1 to 3 random services and implode with a comma
            if ($partnerServices->isEmpty()) {
                $serviceString = 'General Treatment Session';
            } else {
                $randomServices = $partnerServices->random(min(2, $partnerServices->count()));
                $serviceString = $randomServices->implode(', ');
            }

            Booking::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'phone' => $client['phone'],
                'image' => 'avatars/default.jpg',
                'service' => $serviceString, // Comma-separated string of existing services
                'option' => $chosenOption,
                'partner_id' => $partner->id,
                'booking_date' => now()->addDays($index + 1)->toDateString(),
                'status' => 'confirmed',
            ]);
        }
    }
}