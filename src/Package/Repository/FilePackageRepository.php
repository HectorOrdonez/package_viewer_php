<?php

namespace AgriPlace\Package\Repository;

use AgriPlace\Package\Entity\Package;
use AgriPlace\Package\Exception\PackageNotFoundException;
use AgriPlace\Package\PackageRepositoryInterface;

class FilePackageRepository implements PackageRepositoryInterface
{
    /**
     * @var array
     */
    private $sourceData;

    /**
     * Source file path is expected to be relative to the base path of the application
     * For instance: /tests/Support/status-1-entry
     *
     * @param string $sourceFilePath
     */
    public function __construct($sourceFilePath)
    {
        $file = file(base_path() . $sourceFilePath);

        foreach ($file as $line) {
            $this->sourceData[] = explode(': ', $line);
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllNames(): array
    {
        $data = [];

        foreach ($this->sourceData as $record) {
            if ($record[0] == 'Package') {
                $data[] = trim($record[1]);
            }
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function findOneByName($name): Package
    {

        if ($this->packageExists($name) == false) {
            throw new PackageNotFoundException('That package does not exist');
        }

        $packageDetails = $this->getPackageDetails($name);

        return new Package(
            $packageDetails['Package'],
            $packageDetails['Description'],
            $packageDetails['Depends']
        );
    }

    private function packageExists($requestedPackage): bool
    {
        foreach ($this->sourceData as $record) {
            if ($record[0] == 'Package') {
                $packageName = trim($record[1]);

                if ($packageName == $requestedPackage) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * This function assumes the package exists
     *
     * @param string $requestedPackage
     * @return array
     */
    private function getPackageDetails($requestedPackage): array
    {
        $fileFound = false;
        $data = ['Depends' => []];

        // First we need to find dwhere the package info starts
        foreach ($this->sourceData as $record) {
            if ($record[0] == 'Package') {
                // File was already found, we are now facing another package
                if ($fileFound == true) {
                    return $data;
                }

                $packageName = trim($record[1]);

                if ($packageName == $requestedPackage) {
                    // This is the package we are looking for!
                    $fileFound = true;
                    $data['Package'] = $packageName;
                }
            }

            if ($fileFound == true) {
                // We do not need information related to multiple lines
                if (count($record) == 1) {
                    continue;
                }

                if ($record[0] == 'Depends') {
                    $rawDependencies = explode(', ', trim($record[1]));
                    $dependencies = [];

                    foreach ($rawDependencies as $dependency) {
                        list($dependencyName) = explode(' ', $dependency);

                        if ($this->packageExists($dependencyName)) {
                            $dependencies[] = [
                                'name' => $dependencyName,
                                'reference' => \Url::to('packages/show/' . $dependencyName),
                            ];
                        } else {
                            $dependencies[] = [
                                'name' => $dependencyName,
                                'reference' => null,
                            ];
                        }
                    }

                    $data[$record[0]] = $dependencies;
                } else {
                    $data[$record[0]] = trim($record[1]);
                }
            }
        }

        // End of file
        return $data;
    }
}
