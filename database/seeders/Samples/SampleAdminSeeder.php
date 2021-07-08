<?php

namespace Database\Seeders\Samples;

use Illuminate\Database\Seeder;
use App\Models\Users\Admin;

class SampleAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::factory()
            ->count(12)
            ->create();
    }
}
