<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Helpers\GeneratorHelper;
use App\Models\Users\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'first_name' => 'App',
                'last_name' => 'Admin',
                'email' => 'admin@app.com',
                'password' => 'password',
            ],
        ];

        foreach($items as $item) {
            $item['password'] = Hash::make($item['password']);

            Admin::updateOrCreate([
                'email' => $item['email'],
            ], $item);
        }
    }
}
