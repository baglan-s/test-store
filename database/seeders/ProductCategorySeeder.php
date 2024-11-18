<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\ProductCategoryRepository;

class ProductCategorySeeder extends Seeder
{
    public function __construct(
        private ProductCategoryRepository $repository
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Бытовая техника'],
            ['name' => 'Компьютеры и ноутбуки'],
            ['name' => 'Холодильники', 'parent_id' => 1],
            ['name' => 'Ноутбуки', 'parent_id' => 2],
        ];

        foreach ($data as $item) {
            $this->repository->create($item);
        }
    }
}
