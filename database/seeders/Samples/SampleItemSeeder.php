<?php

namespace Database\Seeders\Samples;

use Illuminate\Database\Seeder;

use App\Models\Samples\SampleItem;
use App\Models\Mutators\FileRecord;

class SampleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SampleItem::factory()
            ->count(12)
            ->create();
    }
}
