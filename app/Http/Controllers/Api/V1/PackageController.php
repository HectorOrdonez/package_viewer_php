<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use AgriPlace\Package\PackageRepositoryInterface;
use AgriPlace\Package\Exception\PackageNotFoundException;

class PackageController extends Controller
{
    public function index(PackageRepositoryInterface $packageRepository): Response
    {
        $names = $packageRepository->findAllNames();

        return response()->json($names);
    }

    public function show(PackageRepositoryInterface $packageRepository, $package): Response
    {
        try {
            $package = $packageRepository->findOneByName($package);
        } catch (PackageNotFoundException $exception) {
            return response()->json(['Could not find requested package'], 400);
        }

        return response()->json($package, 200);
    }
}
