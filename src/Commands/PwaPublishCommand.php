<?php

namespace KsLaravelPwa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class PwaPublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ks:publish-laravel-pwa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Service Worker Manifest File for a Laravel PWA Application';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Step 0: intro
        $this->info('KSeven PWA for Laravel.');

        // Step 1: Publish the pwa-config
        $this->call('vendor:publish', [
            '--tag' => 'ks:publish-pwa-config',
            '--force' => true,
        ]);
        $this->info('manifest.json file is published ✔');

        // Step 2: Publish the manifest
        $this->call('vendor:publish', [
            '--tag' => 'ks:publish-manifest',
            '--force' => true,
        ]);
        $this->info('manifest.json file is published ✔');

        // Step 3: Publish the offline page
        $this->call('vendor:publish', [
            '--tag' => 'ks:publish-offline',
            '--force' => true,
        ]);
        $this->info('offline.html file is published ✔');

        // Step 4: Publish the sw js
        $this->call('vendor:publish', [
            '--tag' => 'ks:publish-sw',
            '--force' => true,
        ]);
        $this->info('sw.js file is published ✔');

        // Step 5: Publish the sw js
        $this->call('vendor:publish', [
            '--tag' => 'ks:publish-notification',
            '--force' => true,
        ]);
        $this->info('notification.js file is published ✔');

        // Step 6: Publish the logo
        $this->call('vendor:publish', [
            '--tag' => 'ks:publish-logo',
            '--force' => true,
        ]);
        $this->info('logo is published ✔');

        // Step 7: Publish the WebPush migrations
        $this->call('vendor:publish', [
            '--provider' => 'NotificationChannels\WebPush\WebPushServiceProvider',
            '--tag' => 'migrations',
            '--force' => true,
        ]);
        $this->info('Notification is published ✔');

        // Step 8: migrate
        if (!Schema::hasTable('users')) {
            // Se a tabela não existir, executa a migração
            $this->call('migrate', [
                '--force' => true, 
            ]);
        }

        // Step 9: Publish the WebPush config
        $this->call('vendor:publish', [
            '--provider' => 'NotificationChannels\WebPush\WebPushServiceProvider',
            '--tag' => 'config',
            '--force' => true,
        ]);
        $this->info('Config WebPush is published ✔');

        // Step 10: VAPID Generate
        $this->call('webpush:vapid');
        $this->info('key VAPID generated ✔');


        $this->info('Greeting!.. Enjoy Laravel PWA 🎉');

    }
}
