<?php

namespace Kejubayer\BdAddress\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'bd-address:install
                            {--fresh : Drop all tables and re-run migrations}
                            {--seed : Run seeder after migration}';

    /**
     * The console command description.
     */
    protected $description = 'Install Bangladesh Address Manager package (migrations + seeders + setup)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Installing BD Address Manager...');

        /*
        |--------------------------------------------------------------------------
        | OPTIONAL FRESH MIGRATION
        |--------------------------------------------------------------------------
        */
        if ($this->option('fresh')) {
            $this->warn('⚠ Running fresh migration...');

            $this->call('migrate:fresh');
        }

        /*
        |--------------------------------------------------------------------------
        | PUBLISH MIGRATIONS
        |--------------------------------------------------------------------------
        */
        $this->info('📦 Publishing migrations...');

        $this->call('vendor:publish', [
            '--tag' => 'bd-address-migrations',
            '--force' => true
        ]);

        /*
        |--------------------------------------------------------------------------
        | PUBLISH SEEDERS
        |--------------------------------------------------------------------------
        */
        $this->info('🌱 Publishing seeders...');

        $this->call('vendor:publish', [
            '--tag' => 'bd-address-seeders',
            '--force' => true
        ]);

        /*
        |--------------------------------------------------------------------------
        | PUBLISH JSON DATA
        |--------------------------------------------------------------------------
        */
        $this->info('📄 Publishing data file...');

        $this->call('vendor:publish', [
            '--tag' => 'bd-address-data',
            '--force' => true
        ]);

        /*
        |--------------------------------------------------------------------------
        | RUN MIGRATIONS
        |--------------------------------------------------------------------------
        */
        $this->info('🛠 Running migrations...');

        $this->call('migrate');

        /*
        |--------------------------------------------------------------------------
        | RUN SEEDER
        |--------------------------------------------------------------------------
        */
        if ($this->option('seed')) {
            $this->info('🌱 Running seeder...');

            $this->call('db:seed', [
                '--class' => 'BdAddressSeeder'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR CACHE
        |--------------------------------------------------------------------------
        */
        $this->info('🧹 Clearing cache...');

        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');

        /*
        |--------------------------------------------------------------------------
        | DONE
        |--------------------------------------------------------------------------
        */
        $this->info('✅ BD Address Manager installed successfully!');
        $this->line('--------------------------------------------------');
        $this->info('👉 You can now use helpers like:');
        $this->line('bd_divisions()');
        $this->line('bd_districts()');
        $this->line('bd_full_address($unionId)');
        $this->line('--------------------------------------------------');
    }
}
