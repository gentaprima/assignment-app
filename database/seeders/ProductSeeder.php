<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Tomoro Aren Latte',
                'category' => 'Coffee',
                'variants' => [
                    ['cup_size' => 'Small', 'price' => 18000],
                    ['cup_size' => 'Medium', 'price' => 22000],
                ]
            ],
            [
                'name' => 'Caffe Latte',
                'category' => 'Coffee',
                'variants' => [
                    ['cup_size' => 'Small', 'price' => 20000],
                    ['cup_size' => 'Medium', 'price' => 24000],
                ]
            ],
            [
                'name' => 'Americano',
                'category' => 'Coffee',
                'variants' => [
                    ['cup_size' => 'Small', 'price' => 15000],
                    ['cup_size' => 'Medium', 'price' => 18000],
                ]
            ],
            [
                'name' => 'Matcha Latte',
                'category' => 'Non-Coffee',
                'variants' => [
                    ['cup_size' => 'Small', 'price' => 22000],
                    ['cup_size' => 'Medium', 'price' => 26000],
                ]
            ],
        ];

        foreach ($products as $item) {
            $product = Product::create([
                'name' => $item['name'],
                'category' => $item['category'],
            ]);

            foreach ($item['variants'] as $variant) {
                $product->variants()->create($variant);
            }
        }
    }
}
