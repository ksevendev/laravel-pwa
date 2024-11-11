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
                'description' => 'A Progressive Web Application setup for Laravel projects.',
                "version" => "0.0.0",
                'background_color' => '#000',
                'theme_color' => '#F12',
                "start_url" => "/?source=pwa",
                "scope" => "/",
                "display" => "standalone",
                "display_override" => ["standalone", "fullscreen"],
                "orientation" => "portrait-primary",
                "lang" => "pt-BR",
                "dir" => "ltr",
                "icons" => [
                    [
                        "src" => "/assets/images/96x96.png",
                        "sizes" => "96x96",
                        "type" => "image/png"
                    ],
                    [
                        "src" => "/assets/images/192x192.png",
                        "sizes" => "192x192",
                        "type" => "image/png"
                    ],
                    [
                        "src" => "/assets/images/512x512.png",
                        "sizes" => "512x512",
                        "type" => "image/png"
                    ]
                ],
                "screenshots" => [
                    [
                        "src" => "/assets/images/screanshot1.jpg",
                        "sizes" => "576x1280",
                        "type" => "image/jpeg"
                    ],
                    [
                        "src" => "/assets/images/screanshot2.jpg",
                        "sizes" => "576x1280",
                        "type" => "image/jpeg"
                    ],
                    [
                        "src" => "/assets/images/screenshot-wide.jpg",
                        "sizes" => "1365x605",
                        "type" => "image/jpeg",
                        "form_factor" => "wide"
                    ]
                ],
                "categories" => ["general"],
                "shortcuts" => [
                    [
                        "name" => "KSeven",
                        "short_name" => "KSeven",
                        "description" => "Seu desenvolvedor...",
                        "url" => "/",
                        "icons" => [
                            [
                                "src" => "/assets/images/96x96.png",
                                "sizes" => "96x96",
                                "type" => "image/png"
                            ]
                        ]
                    ]
                ],
                "related_applications" => [
                    [
                        "platform" => "webapp",
                        "url" => "https://kseven.com.br/"
                    ],
                    [
                        "platform" => "play",
                        "id" => "com.kseven.app"
                    ]
                ],
                "prefer_related_applications" => false,
                "iarc_rating_id" => "e10+"
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
