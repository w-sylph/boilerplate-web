<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SearchableRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tnt:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Refresh all object's searchable array value";

    protected $models = [
        'App\Models\ActivityLogs\ActivityLog',
        'App\Models\Samples\SampleItem',
        'App\Models\Articles\Article',
        'App\Models\Users\Admin',
        'App\Models\Users\User',
        'App\Models\Pages\Page',
        'App\Models\Pages\PageItem',
        'App\Models\Roles\Role',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(PHP_EOL . "Refreshing searchable array values" . PHP_EOL);

        $models = $this->models;

        /* Loop through each php files */
        foreach ($models as $key => $model) {

            $this->info('Refreshing ' . $model);

            $this->call('tnt:import', [
                'model' => $model
            ]);

        }

    }
}
