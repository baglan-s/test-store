<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Repositories\SpecificationValueRepository;

class SpecificationValueSeeder extends Seeder
{
    public function __construct(
        private SpecificationValueRepository $repository
    ) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['specification_id' => 1, 'name' => '1200 л.'],
            ['specification_id' => 1, 'name' => '1500 л.'],
            ['specification_id' => 2, 'name' => 'Черный'],
            ['specification_id' => 2, 'name' => 'Серый'],
            ['specification_id' => 3, 'name' => '18x18x20 см'],
            ['specification_id' => 3, 'name' => '15x19x26 см'],
        ];

        foreach ($data as $item) {
            $this->repository->create($item);
        }
    }
}
