<?php

namespace AgriPlace\Package\Repository;

use AgriPlace\Package\Entity\Package;
use AgriPlace\Package\Exception\PackageNotFoundException;
use AgriPlace\Package\PackageRepositoryInterface;

class FilePackageRepository implements PackageRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findAllNames()
    {
        $data = [];

        $file = file(base_path() . '/tests/Support/status-1-entry');

        foreach ($file as $line) {
            $exploded = explode(': ', $line);

            if ($exploded[0] == 'Package') {
                $data[] = trim($exploded[1]);
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function findOneByName($name)
    {
        if($this->packageExists($name) == false)
        {
            throw new PackageNotFoundException('That package does not exist');
        }

        $packageDetails = $this->getPackageDetails($name);

        return new Package($packageDetails['Package'], $packageDetails['Description']);
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
