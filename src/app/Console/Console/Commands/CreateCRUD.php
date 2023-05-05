<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateCRUD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:crud';
    protected $signature = 'make:crud {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate crud controller with request validation';

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
     * @return int
     */
    public function handle()
    {
        $this->call('make:crud-controller', ['name' => $this->argument('model') . 'Controller', 'modelName' => $this->argument('model')]);
        $this->call('make:crud-request', ['name' => $this->argument('model') . 'Request', 'modelName' => $this->argument('model')]);

        $this->comment('Logs have been cleared!');

        return Command::SUCCESS;
    }
}
