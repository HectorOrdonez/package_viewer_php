<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Http\Controllers\PackageController;

$app->get('/', function () use ($app) {
    return $app->version();
});


$app->group(['prefix' => 'api'], function () use ($app) {
    $app->get('packages', PackageController::class . '@index');
    $app->get('packages/{package}', PackageController::class . '@show');
});
