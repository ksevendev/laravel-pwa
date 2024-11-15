<?php

namespace KsLaravelPwa;

use KsLaravelPwa\Commands\PWACommand;
use KsLaravelPwa\Commands\PwaPublishCommand;
use KsLaravelPwa\Services\PWAService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class KsLaravelPwaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PWAService::class, function ($app) {
            return new PWAService;
        });

        $this->commands([
            PwaPublishCommand::class,
            PWACommand::class,
        ]);

        $this->publishes([
            __DIR__.'/Stubs/pwa.stub' => config_path('pwa.php'),
        ], 'ks:publish-pwa-config');

        $this->publishes([
            __DIR__.'/Stubs/manifest.stub' => base_path('assets/manifest.json'),
        ], 'ks:publish-manifest');

        $this->publishes([
            __DIR__.'/Stubs/offline.stub' => base_path('assets/offline.html'),
        ], 'ks:publish-offline');

        $this->publishes([
            __DIR__.'/Stubs/sw.stub' => base_path('assets/sw.js'),
        ], 'ks:publish-sw');

        $this->publishes([
            __DIR__.'/Stubs/notification.stub' => base_path('assets/notification.js'),
        ], 'ks:publish-notification');

        $this->publishes([
            __DIR__.'/Stubs/logo.png' => base_path('assets/logo.png'),
        ], 'ks:publish-logo');

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Blade::directive('PwaHead', function () {
            return '<?php echo app(\\KsLaravelPwa\\Services\\PWAService::class)->headTag(); ?>';
        });

        Blade::directive('RegisterServiceWorkerScript', function () {
            return '<?php echo app(\\KsLaravelPwa\\Services\\PWAService::class)->registerServiceWorkerScript(); ?>';
        });
    }
}
