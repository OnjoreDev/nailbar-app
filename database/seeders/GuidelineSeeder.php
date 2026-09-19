<?php
namespace Database\Seeders;

use App\Models\Guideline;
use App\Models\Partner;
use Illuminate\Database\Seeder;

class GuidelineSeeder extends Seeder
{
    public function run(): void
    {
        $partners = Partner::all();

        $guidelineTexts = [
            'Please arrive at least 10 minutes prior to your scheduled booking slot.',
            'Cancellations must be communicated at least 3 hours before appointment time.',
            'All clients are required to sanitize hands upon entry to maintain hygiene standards.',
            'Children must be supervised at all times within the lounge premises.',
            'Please notify your technician of any skin sensitivities prior to treatment commencement.',
            'Rescheduling is permitted up to two times per booking reference.',
            'Mask usage is optional but encouraged during peak hours.',
            'Payment via mobile money or approved cards is required immediately after service.',
            'Kindly keep mobile phones on silent mode while inside the treatment rooms.',
            'Feedback forms are available at the front desk after every session.',
        ];

        // Create 10 guidelines distributed across partners
        foreach ($guidelineTexts as $index => $text) {
            $partner = $partners[$index % $partners->count()];
            Guideline::create([
                'description' => $text,
                'partner_id' => $partner->id,
            ]);
        }
    }
}