<?php

namespace App\Http\Controllers;

use AgriPlace\Package\Exception\PackageNotFoundException;
use AgriPlace\Package\PackageRepositoryInterface;
use Illuminate\Support\Facades\File;

class PackageController extends Controller
{
    public function index(PackageRepositoryInterface $packageRepository)
    {
        $names = $packageRepository->findAllNames();

        return response()->json($names);
    }

    public function show(PackageRepositoryInterface $packageRepository, $package)
    {
        try {
            $package = $packageRepository->findOneByName($package);
        } catch (PackageNotFoundException $exception) {
            return response()->json(['Could not find requested package'], 400);
        }

        return response()->json($package, 200);
    }
}
