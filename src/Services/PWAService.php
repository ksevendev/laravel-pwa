<?php

namespace KsLaravelPwa\Services;

class PWAService
{
    public function HeadTag(): string
    {
        $manifest = asset('/assets/manifest.json');
        $themeColor = config('pwa.manifest.theme_color', '#6777ef');
        $icon = asset(config('pwa.manifest.icons.src', 'assets/images/96x96.png'));
        $installButton = config('pwa.install-button', false);

        $style = self::getInstallButtonStyle($installButton);

        return <<<HTML
        <!-- PWA  -->
        <meta name="theme-color" content="{$themeColor}"/>
        <link rel="apple-touch-icon" href="{$icon}">
        <link rel="apple-touch-icon" sizes="120x120" href="{$icon}">
        <link rel="apple-touch-icon" sizes="152x152" href="{$icon}">
        <link rel="apple-touch-icon" sizes="167x167" href="{$icon}">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="manifest" href="{$manifest}">
        <!-- PWA end -->
        {$style}
        HTML;
    }

    public function RegisterServiceWorkerScript(): string
    {
        $swPath = asset('/assets/sw.js');
        $isDebug = config('pwa.debug', false);
        $consoleLog = $isDebug ? 'console.log' : '//';
        $icon = asset(config('pwa.manifest.icons.src', '/assets/images/96x96.png'));
        $iconButon = asset('/assets/images/app.png');
        $installButton = config('pwa.install-button', false);

        $installApp = self::getInstallAppHtml($installButton, $iconButon);
        $installButtonJs = $installButton ? self::installButtonJs() : '';

        $vapid_public = config('pwa.vapid_public', null);

        return <<<HTML
        {$installApp}
        <!-- PWA scripts -->
        <script src="{$swPath}"></script>
        <script>
            // Verificar se o navegador suporta Service Worker e Push Manager
            if ('serviceWorker' in navigator && 'PushManager' in window) {
                navigator.serviceWorker.register('/assets/sw.js').then(function(registration) {
                    console.log('Service Worker registrado com sucesso:', registration);

                    // Solicitar permissão para notificações push
                    Notification.requestPermission().then(function(permission) {
                        if (permission === 'granted') {
                            console.log('Permissão para notificações concedida');
                            // Inscrever o usuário para receber notificações push
                            subscribeUserToPush(registration);
                        } else {
                            console.log('Permissão para notificações negada');
                        }
                    });
                }).catch(function(error) {
                    console.log('Erro ao registrar o Service Worker:', error);
                });
            }
            // Função para inscrever o usuário em notificações push
            function subscribeUserToPush(registration) {
                registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: '{$vapid_public}'
                }).then(function(subscription) {
                    console.log('Inscrição do usuário:', subscription);
                    // Enviar a inscrição para o backend (Laravel)
                    sendSubscriptionToBackend(subscription);
                }).catch(function(error) {
                    console.error('Falha ao se inscrever para notificações push:', error);
                });
            }
            // Função para enviar a inscrição para o backend (Laravel)
            function sendSubscriptionToBackend(subscription) {
                fetch('/api/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subscription: subscription,
                    })
                })
                .then(response => response.json())
                .then(data => console.log('Inscrição enviada com sucesso:', data))
                .catch(error => console.error('Erro ao enviar inscrição para o backend:', error));
            }
            {$installButtonJs}
        </script>
        <!-- PWA scripts -->
        HTML;
    }

    private static function installButtonJs(): string
    {
        return <<<'HTML'
            let deferredPrompt;function showInstallPromotion(){document.getElementById("install-prompt").style.display="block"}window.addEventListener("load",(()=>{if(window.matchMedia("(display-mode: standalone)").matches){document.getElementById("install-prompt").style.display="none"}})),window.addEventListener("beforeinstallprompt",(e=>{e.preventDefault(),deferredPrompt=e,showInstallPromotion();document.getElementById("install-button").addEventListener("click",(()=>{deferredPrompt.prompt(),deferredPrompt.userChoice.then((e=>{deferredPrompt=null}))}))})),window.addEventListener("appinstalled",(()=>{document.getElementById("install-prompt").style.display="none"}));
        HTML;
    }

    private static function getInstallButtonStyle(bool $installButton): string
    {
        if ($installButton) {
            return <<<'HTML'
                <style>
                    .box-icon{position:fixed;bottom:100px;right:100px}.box-icon .circle{cursor:pointer;width:60px;height:60px;background-color:rgba(255,150,35,0.2);border-radius:100%;position:absolute;top:-10px;left:-10px;transition:transform ease-out 0.1s,background 0.2s}.box-icon .circle:after{position:absolute;width:100%;height:100%;border-radius:50%;content:'';top:0;left:0;z-index:-1;animation:shadow-pulse 1s infinite;box-shadow:0 0 0 0 rgb(193 54 1 / 40%)}@keyframes shadow-pulse{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 35px rgba(0,0,0,0)}}@keyframes shadow-pulse-big{0%{box-shadow:0 0 0 0 rgb(240,240,240)}100%{box-shadow:0 0 0 70px rgba(0,0,0,0)}}
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
                        <img src="{$icon}" alt="Install App" style="width: 52px;height: auto;position: absolute;left: 5px;bottom: 4px;">
                    </span>
                </div>
            HTML;
        }

        return '';
    }
}
