<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Pulpen',
                'image' => '',
                'price' => 5000
            ],
            [
                'name' => 'Pensil',
                'image' => '',
                'price' => 3000
            ],
            [
                'name' => 'Buku Tulis',
                'image' => '',
                'price' => 15000
            ],
            [
                'name' => 'Penghapus',
                'image' => '',
                'price' => 2000
            ],
            [
                'name' => 'Penggaris',
                'image' => '',
                'price' => 4000
            ],
            [
                'name' => 'Staples',
                'image' => '',
                'price' => 12000
            ],
            [
                'name' => 'Klip Kertas',
                'image' => '',
                'price' => 3500
            ],
            [
                'name' => 'Kertas Tempel',
                'image' => '',
                'price' => 7000
            ],
            [
                'name' => 'Tip Ex',
                'image' => '',
                'price' => 8000
            ],
            [
                'name' => 'Stabilo',
                'image' => '',
                'price' => 6000
            ]
        ];

        foreach ($items as $item) {
            Item::create(array_merge(
                $item,
                ['code' => 'ITM-' . str_pad(Item::max('id') + 1, 5, '0', STR_PAD_LEFT)]
            ));
        }
    }
}
