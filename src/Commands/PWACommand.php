<?php

namespace KsLaravelPwa\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class PWACommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ks:pwa-update-manifest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the manifest.json file for the PWA.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $defaultManifest = [
                'name' => 'KSeven PWA',
                'short_name' => 'KSPWA',
                'background_color' => '#000',
                'display' => 'fullscreen',
                'description' => 'A Progressive Web Application setup for KSeven projects.',
                'theme_color' => '#F15',
                'icons' => [
                    [
                        'src' => 'logo.png',
                        'sizes' => '512x512',
                        'type' => 'image/png',
                    ],
                ],
            ];

            // Load custom manifest from config, fallback to default
            $manifest = Config::get('pwa.manifest', $defaultManifest);

            if (empty($manifest['icons'])) {
                $this->error('Manifest is missing icons. Aborting operation.');

                return;
            }

            unset($manifest['start_url']);
            $icons = $manifest['icons'];
            unset($manifest['icons']);

            $arrayMergeManifest = array_merge($manifest, ['start_url' => '/'], ['icons' => $icons]);

            $jsonData = json_encode($arrayMergeManifest, JSON_PRETTY_PRINT);
            if ($jsonData === false) {
                $this->error('Failed to encode manifest array to JSON. Aborting operation.');

                return;
            }

            $jsonData = str_replace('\/', '/', $jsonData);

            $filePath = asset('/assets/manifest.json');
            if (! File::isWritable(asset('assets'))) {
                $this->error('Public directory is not writable. Check file permissions.');
                return;
            }

            File::put($filePath, $jsonData);

            $this->info('Manifest JSON updated successfully ✔');
        } catch (\Exception $e) {
            // Catch any errors and display an error message
            $this->error('An error occurred while updating the manifest: '.$e->getMessage());
        }
    }
}
