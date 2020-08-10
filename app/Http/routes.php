<?php
use App\Http\Controllers\Api\V1\PackageController as ApiPackageController;

$app->get('/', PackageController::class . '@index');
$app->get('packages/{package}', PackageController::class . '@show');

$app->group(['prefix' => 'api'], function () use ($app) {
    $app->get('packages', ApiPackageController::class . '@index');
    $app->get('packages/{package}', ApiPackageController::class . '@show');
});
