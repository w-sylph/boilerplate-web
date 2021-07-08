<?php

namespace Database\Seeders\Samples;

use Illuminate\Database\Seeder;

use App\Models\Samples\SampleItem;
use App\Models\Users\User;

class SampleItemRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = SampleItem::get();

        foreach ($items as $item) {
        	$item->user_id = User::inRandomOrder()->first()->id;
        	$item->data = User::inRandomOrder()->take(3)->pluck('id')->toArray();
        	$item->save();
        }
    }
}
