<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\ProductRepository;

class ProductSeeder extends Seeder
{
    public function __construct(
        private ProductRepository $repository
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Холодильник Samsung',
                'description' => 'Холодильник для хранения продуктов на протяжении дня',
                'price' => 25000.00,
                'product_category_id' => 3,
                'specifications' => [
                    ['specification_id' => 1, 'specification_value_id' => 1],
                    ['specification_id' => 2, 'specification_value_id' => 3],
                    ['specification_id' => 3, 'specification_value_id' => 5],
                ]
            ],
            [
                'name' => 'Холодильник LG',
                'description' => 'Холодильник для хранения продуктов на протяжении дня 2',
                'price' => 35000.00,
                'product_category_id' => 3,
                'specifications' => [
                    ['specification_id' => 1, 'specification_value_id' => 2],
                    ['specification_id' => 2, 'specification_value_id' => 4],
                    ['specification_id' => 3, 'specification_value_id' => 5],
                ]
            ],
            [
                'name' => 'Ноутбук Legion',
                'description' => 'Ноутбук игровой',
                'price' => 65000.00,
                'product_category_id' => 4,
                'specifications' => [
                    ['specification_id' => 2, 'specification_value_id' => 3],
                    ['specification_id' => 3, 'specification_value_id' => 5],
                ]
            ]
        ];

        foreach ($data as $item) {
            $this->repository->create($item);
        }
    }
}
