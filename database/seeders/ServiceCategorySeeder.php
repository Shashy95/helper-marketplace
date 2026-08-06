<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        collect(['Cleaning', 'Laundry', 'Ironing', 'Deep Cleaning', 'Dishwashing'])
            ->each(fn ($name) => ServiceCategory::firstOrCreate(
                ['slug' => str($name)->slug()],
                ['name' => $name]
            ));
    }
}
