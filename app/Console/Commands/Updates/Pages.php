<?php

namespace App\Console\Commands\Updates;

use App\Extenders\BaseCommand as Command;

class Pages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'page:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute PagesTableSeeder';

    /**
     * Command to run
     * @return void
     */
    protected function start()
    {
        $this->call('db:seed', [
            '--class' => 'PagesTableSeeder',
        ]);
    }
}
