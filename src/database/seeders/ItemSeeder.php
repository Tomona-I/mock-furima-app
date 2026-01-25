<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'product_image' => 'items/item_sample_01.png',
                'category_ids' => [1, 12],
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'product_image' => 'items/item_sample_02.png',
                'category_ids' => [2],
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => '',
                'description' => '新鮮な玉ねぎの3束セット',
                'condition' => 'やや傷や汚れあり',
                'product_image' => 'items/item_sample_03.png',
                'category_ids' => [10],
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'description' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
                'product_image' => 'items/item_sample_04.png',
                'category_ids' => [1, 5],
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'description' => '高性能なノートパソコン',
                'condition' => '良好',
                'product_image' => 'items/item_sample_05.png',
                'category_ids' => [2],
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'brand' => '',
                'description' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'product_image' => 'items/item_sample_06.png',
                'category_ids' => [2],
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'product_image' => 'items/item_sample_07.png',
                'category_ids' => [1, 4],
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => '',
                'description' => '使いやすいタンブラー',
                'condition' => '状態が悪い',
                'product_image' => 'items/item_sample_08.png',
                'category_ids' => [10],
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'condition' => '良好',
                'product_image' => 'items/item_sample_09.png',
                'category_ids' => [10],
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'description' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'product_image' => 'items/item_sample_10.png',
                'category_ids' => [6], 
            ],
        ];

        foreach ($items as $itemData) {
            $categoryIds = $itemData['category_ids'];
            unset($itemData['category_ids']);
            
            $item = Item::factory()->create([
                'user_id' => User::inRandomOrder()->first()->id,
                'is_sold' => false,
                ...$itemData,
            ]);
            
            $item->categories()->attach($categoryIds);
        }
    }
}
