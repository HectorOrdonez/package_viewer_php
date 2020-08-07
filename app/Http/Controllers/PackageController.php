<?php

namespace App\Http\Controllers;

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
        if ($this->packageExists($package) == false) {
            return response()->json(['Could not find requested package'], 400);
        }

        $packageDetails = $this->getPackageDetails($package);

        return response()->json($packageDetails, 200);
    }

    private function packageExists($requestedPackage)
    {
        $file = file(base_path() . '/tests/Support/status-1-entry');

        foreach ($file as $line) {
            $exploded = explode(': ', $line);

            if ($exploded[0] == 'Package') {
                $packageName = trim($exploded[1]);

                if($packageName == $requestedPackage)
                {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * This function assumes the package exists
     *
     * @param $package
     * @return array
     */
    private function getPackageDetails($requestedPackage)
    {
        $file = file(base_path() . '/tests/Support/status-1-entry');
        $fileFound = false;
        $data = [];

        // First we need to find where the package info starts
        foreach ($file as $line) {
            $exploded = explode(': ', $line);

            if($fileFound == true)
            {
                // If we found the file we need to make sure to stop reading
                // when data is finished
                if($line == "\n\n")
                {
                    return $data;
                }

                $exploded = explode(': ', $line);

                // We do not need information related to multiple lines
                if(count($exploded) == 1)
                {
                    continue;
                }

                $data[$exploded[0]] = trim($exploded[1]);
            }

            if ($exploded[0] == 'Package') {
                $packageName = trim($exploded[1]);

                if($packageName == $requestedPackage)
                {
                    // This is the package we are looking for!
                    $fileFound = true;
                    $data['Package'] = $packageName;
                }
            }
        }

        // End of file
        return $data;
    }
}
