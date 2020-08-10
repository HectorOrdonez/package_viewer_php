<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}

$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

if (!function_exists('dump')) {
    function dump()
    {
        array_map(function ($x) {
            (new Illuminate\Support\Debug\Dumper)->dump($x);
        }, func_get_args());
    }
}

$app->withFacades();
$app->register(\AgriPlace\Package\PackageServiceProvider::class);
$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__ . '/../app/Http/routes.php';
});

return $app;
