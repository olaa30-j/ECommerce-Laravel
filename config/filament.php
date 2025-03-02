<?php

return [

    'providers' => [
        App\Providers\Filament\AdminPanelProvider::class,
    ],

    'assets' => [
        function_exists('vite') ? vite('resources/css/filament.css') : '/public/vendor/filament/filament.css',
        function_exists('vite') ? vite('resources/js/filament.js') : '/public/vendor/filament/filament.js',
    ],

];
