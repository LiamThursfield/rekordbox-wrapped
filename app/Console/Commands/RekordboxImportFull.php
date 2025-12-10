<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RekordboxImportFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rekordbox:import-full {--skip-canonical}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs all of the Rekordbox import commands';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Importing Artists');
        $this->call('rekordbox:import-artists', ['--skip-canonical' => $this->option('skip-canonical')]);

        $this->line('');

        $this->info('Importing Tracks');
        $this->call('rekordbox:import-tracks', ['--skip-canonical' => $this->option('skip-canonical')]);

        return self::SUCCESS;
    }
}
