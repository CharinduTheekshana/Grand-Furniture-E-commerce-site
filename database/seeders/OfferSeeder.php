<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $offers = [
            [
                'offer_badge'      => 'Flash Sale',
                'offer_type'       => 'flash_sale',
                'offer_start_date' => Carbon::now(),
                'offer_end_date'   => Carbon::now()->addDays(2),
                'offer_status'     => true,
            ],
            [
                'offer_badge'      => 'Free Delivery',
                'offer_type'       => 'free_delivery',
                'offer_start_date' => Carbon::now(),
                'offer_end_date'   => Carbon::now()->addDays(7),
                'offer_status'     => true,
            ],
            [
                'offer_badge'      => 'Buy 1 Get 1',
                'offer_type'       => 'bogo',
                'offer_start_date' => Carbon::now(),
                'offer_end_date'   => Carbon::now()->addDays(3),
                'offer_status'     => true,
            ],
            [
                'offer_badge'      => 'Weekend Deal',
                'offer_type'       => 'weekend',
                'offer_start_date' => Carbon::now(),
                'offer_end_date'   => Carbon::now()->addDays(5),
                'offer_status'     => true,
            ],
        ];

        $products = Product::where('is_active', true)->take(4)->get();

        foreach ($products as $i => $product) {
            if (isset($offers[$i])) {
                $product->update($offers[$i]);
                $this->command->info("✓ Offer added to: {$product->name}");
            }
        }

        $this->command->info('Offer seeder completed!');
    }
}
