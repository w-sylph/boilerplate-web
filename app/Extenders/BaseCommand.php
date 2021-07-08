<?php

namespace App\Extenders;

use Illuminate\Console\Command;

use App\Helpers\EnvHelper;

class BaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear database and seed data';

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
        if (!EnvHelper::isDev()) {
            dd("Forbidden: Debugging mode must be set to 'true' and enviroment must be set to local to proceed");
        }

        $this->info(PHP_EOL);
        $this->info("<fg=yellow;>/*--------------------------------------------");
        $this->info("<fg=yellow;>| <fg=red;>WARNING! WARNING! WARNING! WARNING! WARNING!");
        $this->info("<fg=yellow;>|---------------------------------------------");
        $this->info("<fg=yellow;>|");
        $this->info("<fg=yellow;>| <fg=red;>{$this->description}");
        $this->info("<fg=yellow;>| <fg=yellow;>EXERCISE EXTREME CAUTION!");
        $this->info("<fg=yellow;>|");
        $this->info("<fg=yellow;>|--------------------------------------------*/");

        $force = false;

        if (isset($this->options()['force'])) {
            $force = $this->options()['force'];
        }

        if (!$force) {
            $toggle = $this->ask("Do you want to proceed? Enter the App Name <fg=red;>(" . config('app.name') . ")</>");
            /* Fetch value of user input */
            switch($toggle) {
                case config('app.name'): $toggle = 1;
                    $this->start();
                break;

                default:
                    $this->info("<fg=red;>Incorrect input!" . PHP_EOL);
                break;
            }

            if($toggle == 0) {
                dd('Aborting command!');
            }
        } else {
            $this->start();
        }
    }

    /**
     * Command to run
     * @return void
     */
    protected function start() {
        
    }
}
