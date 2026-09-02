<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        collect(['Cleaning', 'Laundry', 'Ironing', 'Electrical', 'Plumbing', 'Beauty', 'Repairs', 'Moving'])
            ->each(fn ($name) => ServiceCategory::firstOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name]
            ));
    }
}
