<?php

namespace App\Console\Commands;

use App\Extenders\BaseCommand as Command;

use Storage;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {--prod}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear database seed data and delete all images in public/tmp';

    /**
     * Command to run
     * @return void
     */
    protected function start()
    {
        if (!file_exists('public/storage')) {
            $this->call('storage:link');
        }

        $this->info('Deleting images in public/tmp');
        $tmpFiles = Storage::allFiles('public/tmp');
        $gitIndex = array_search('public/tmp/.gitignore', $tmpFiles);
        if ($gitIndex >= 0) {
            unset($tmpFiles[$gitIndex]);
        }

        Storage::delete($tmpFiles);
        $this->info('Sample images deleted public/tmp');

        if (!config('app.key')) {
            $this->call('key:generate');
        }

        $this->call('migrate:fresh');

        if ($this->option('prod')) {
            $this->call('db:seed');
        } else {
            $this->call('db:seed', [
                '--class' => 'Database\Seeders\Samples\SampleDatabaseSeeder',
            ]);
        }

        $this->call('tnt:refresh');

        $this->call('jwt:secret');
    }
}
