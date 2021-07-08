<?php

namespace Database\Seeders\Samples;

use Illuminate\Database\Seeder;

use App\Models\Users\User;
use App\Models\Mutators\Address;

class SampleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(12)
            ->create();
    }
}
