$env:PATH = "C:\laragon\bin\php\php-8.2.30-Win32-vs16-x64;C:\laragon\bin\composer;C:\laragon\bin\nodejs\node-v20.20.1;C:\laragon\bin\git\cmd;" + $env:PATH
composer require laravel/breeze --dev
php artisan breeze:install blade --no-interaction
composer require barryvdh/laravel-dompdf
npm install chart.js
php artisan migrate:fresh
