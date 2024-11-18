<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\SpecificationRepository;

class SpecificationSeeder extends Seeder
{
    public function __construct(
        private SpecificationRepository $repository
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Мощность', 'description' => 'Объем и мощность техники'],
            ['name' => 'Цвет', 'description' => 'Цветовые характеристики техники'],
            ['name' => 'Размер', 'description' => 'Размеры техники'],
        ];

        foreach ($data as $item) {
            $this->repository->create($item);
        }
    }
}
