<?php

namespace App\Http\Controllers;

use AgriPlace\Package\PackageRepositoryInterface;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(PackageRepositoryInterface $packageRepository): View
    {
        $packageNames = $packageRepository->findAllNames();

        return view('packages.index', compact('packageNames'));
    }

    public function show(PackageRepositoryInterface $packageRepository, string $packageName): View
    {
        $package = $packageRepository->findOneByName($packageName);

        return view('packages.show', ['package' => $package->toArray()]);
    }
}
