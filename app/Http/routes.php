<?php

use App\Http\Controllers\Api\V1\PackageController;

$app->get('/', function () use ($app) {
    return view('packages.index');
});

$app->get('/packages/{package}', function () use ($app) {
    return view('packages.show');
});

$app->group(['prefix' => 'api'], function () use ($app) {
    $app->get('packages', PackageController::class . '@index');
    $app->get('packages/{package}', PackageController::class . '@show');
});
