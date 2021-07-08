<?php

namespace Database\Seeders\Samples;

use Illuminate\Database\Seeder;

use Database\Seeders\AdminSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\PageSeeder;

class SampleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,

            PermissionSeeder::class,
            RoleSeeder::class,

            PageSeeder::class,

            SampleAdminSeeder::class,
            SampleUserSeeder::class,

            SampleItemSeeder::class,
            SampleItemRelationshipSeeder::class,
        ]);
    }
}
