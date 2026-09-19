<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $partners = Partner::all();

        $serviceTemplates = [
            ['name' => 'Classic Manicure', 'price' => 1200],
            ['name' => 'Deluxe Pedicure', 'price' => 1800],
            ['name' => 'Full Manicure & Pedicure Combo', 'price' => 2800],
            ['name' => 'Gel Polish Application', 'price' => 1500],
            ['name' => 'Exfoliating Foot Scrub', 'price' => 1000],
        ];

        // Ensure at least 10 services are created across the partners
        foreach ($partners as $partner) {
            // Assign 2 services per partner (totaling 20 records)
            foreach (array_rand($serviceTemplates, 2) as $index) {
                $template = $serviceTemplates[$index];
                Service::create([
                    'name' => $template['name'] . ' - ' . $partner->business_name,
                    'price' => $template['price'],
                    'partner_id' => $partner->id,
                ]);
            }
        }
    }
}