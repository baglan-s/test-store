<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProductCategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\SpecificationSeeder;
use Database\Seeders\SpecificationValueSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(SpecificationSeeder::class);
        $this->call(SpecificationValueSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
