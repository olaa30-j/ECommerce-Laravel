<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Load Vite-built CSS -->
    @vite(['resources/css/app.css'])

    <!-- Include any additional meta tags or styles here -->
</head>
<body class="font-sans antialiased">
    <div id="filament-admin">
        <!-- Filament content will be rendered here -->
        {{ $slot }}
    </div>

    <!-- Load Vite-built JavaScript -->
    @vite(['resources/js/app.js'])
</body>
</html>