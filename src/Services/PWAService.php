<?php
namespace KsLaravelPwa\Services;

class PWAService
{
    public function HeadTag(): string
    {
        $manifest = e(asset('/assets/manifest.json'));
        $themeColor = e(config('pwa.manifest.theme_color', '#6777ef'));
        $icon = e(asset(config('pwa.manifest.icons.src', 'assets/images/96x96.png')));
        $installButton = config('pwa.install-button', false);
        $style = self::getInstallButtonStyle($installButton);

        return <<<HTML
        <!-- PWA  -->
        <meta name="theme-color" content="{$themeColor}"/>
        <link rel="apple-touch-icon" sizes="96x96" href="{$icon}">
        <link rel="manifest" href="{$manifest}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="pwa-version" content="1.0">
        <!-- PWA end -->
        {$style}
        HTML;
    }

    public function RegisterServiceWorkerScript(): string
    {
        $swPath = e(asset('/assets/sw.js'));
        $isDebug = config('pwa.debug', false);
        $consoleLog = $isDebug ? 'console.log' : '//';
        $vapidPublic = config('pwa.vapid_public');
        $iconButton = e(asset('/assets/images/app.png'));
        $installButton = config('pwa.install-button', false);
    
        $installApp = self::getInstallAppHtml($installButton, $iconButton);
        $installButtonJs = $installButton ? self::installButtonJs() : '';
    
        return <<<HTML
            {$installApp}
            <!-- PWA scripts -->
            <script src="{$swPath}" defer></script>
            <script>
                
    
                // Verificar se o navegador suporta Service Worker e Push Manager
                if ('serviceWorker' in navigator && 'PushManager' in window) {
                    navigator.serviceWorker.register('{$swPath}').then(registration => {
                        console.log('Service Worker registrado com sucesso:', registration);
                        const vapidPublicKey = '{$vapidPublic}';
                        if (vapidPublicKey) {
                            Notification.requestPermission().then(permission => {
                                if (permission === 'granted') {
                                    subscribeUserToPush(registration);
                                } else {
                                    console.log('Permissão para notificações negada');
                                }
                            });
                        }
                    }).catch(error => console.error('Erro ao registrar o Service Worker:', error));
                }
    
                const vapidPublicKey = '{$vapidPublic}';
    
                if (vapidPublicKey) {
                    function subscribeUserToPush(registration) {
                        const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);
                        registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: convertedVapidKey
                        }).then(subscription => {
                            sendSubscriptionToBackend(subscription);
                        }).catch(error => console.error('Falha ao se inscrever para notificações push:', error));
                    }
                    
                    function sendSubscriptionToBackend(subscription) {
                        fetch('/api/subscribe', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({subscription})
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Inscrição enviada com sucesso:', data);
                            } else {
                                console.error('Erro ao enviar inscrição:', data);
                            }
                        })
                        .catch(error => console.error('Erro ao enviar inscrição:', error));
                    }
    
                    function urlBase64ToUint8Array(base64String) {
                        const padding = '='.repeat((4 - base64String.length % 4) % 4);
                        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
                        const rawData = window.atob(base64);
                        const outputArray = new Uint8Array(rawData.length);
                        for (let i = 0; i < rawData.length; ++i) {
                            outputArray[i] = rawData.charCodeAt(i);
                        }
                        return outputArray;
                    }
                }
                
                {$installButtonJs}
            </script>
            <!-- PWA scripts -->
        HTML;
    }    

    private static function getInstallButtonStyle(bool $installButton): string
    {
        if ($installButton) {
            return <<<'HTML'
            <style>
                .box-icon { position: fixed; bottom: 20px; right: 20px; }
                .box-icon .circle { cursor: pointer; width: 60px; height: 60px; background-color: rgba(255, 150, 35, 0.2); border-radius: 50%; }
                .box-icon img { width: 52px; height: auto; position: absolute; left: 4px; bottom: 4px; }
            </style>
            HTML;
        }

        return '';
    }

    private static function getInstallAppHtml(bool $installButton, string $icon): string
    {
        if ($installButton) {
            return <<<HTML
            <div id="install-prompt" class="box-icon" style="display: none;">
                <span id="install-button" class="circle">
                    <img src="{$icon}" alt="Install App">
                </span>
            </div>
            HTML;
        }

        return '';
    }
}
