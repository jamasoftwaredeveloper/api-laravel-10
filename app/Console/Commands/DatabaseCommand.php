<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:databases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the normal and test databases.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Run the normal database.
        $this->call('migrate:fresh');
        $this->call('db:seed');

        // Run the test database.
        $this->call('migrate:fresh --database=apirestjefrimartinez_test');
        $this->call('db:seed --database=apirestjefrimartinez_test');

        return 0;
    }
}
