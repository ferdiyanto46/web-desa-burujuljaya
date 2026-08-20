<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Route;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$routes = collect(Route::getRoutes()->getRoutesByName())
    ->mapWithKeys(function ($route, $name) {
        return [$name => '/'.ltrim($route->uri(), '/')];
    })
    ->toJson(JSON_PRETTY_PRINT);

file_put_contents('routes_dump.json', $routes);
echo "Routes dumped successfully.\n";
