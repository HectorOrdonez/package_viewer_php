<?php

namespace AgriPlace\Package\Repository;

use AgriPlace\Package\Entity\Package;
use AgriPlace\Package\Exception\PackageNotFoundException;
use AgriPlace\Package\Factory\PackageFactory;
use AgriPlace\Package\PackageRepositoryInterface;
use AgriPlace\Package\Parser\PackageParser;

class FilePackageRepository implements PackageRepositoryInterface
{
    /**
     * @var array
     */
    private $sourceData;
    /**
     * @var PackageParser
     */
    private $parser;
    /**
     * @var PackageFactory
     */
    private $factory;

    /**
     * Source file path is expected to be relative to the base path of the application
     * For instance: /tests/Support/status-1-entry
     *
     * @param PackageParser $parser
     * @param PackageFactory $factory
     * @param string $sourceFilePath
     */
    public function __construct(PackageParser $parser, PackageFactory $factory, $sourceFilePath)
    {
        $file = file(base_path() . $sourceFilePath);

        foreach ($file as $line) {
            $this->sourceData[] = explode(': ', $line);
        }
        $this->parser = $parser;
        $this->factory = $factory;
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
    public function findOneByName(string $name): Package
    {
        if ($this->packageExists($name) == false) {
            throw new PackageNotFoundException('That package does not exist');
        }

        $packageDetails = $this->getPackageDetails($name);

        return $this->factory->make($packageDetails);
    }

    private function packageExists(string $requestedPackage): bool
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
    private function getPackageDetails(string $requestedPackage): array
    {
        $packageDetails = $this->parser->parse($this->sourceData, $requestedPackage);

        foreach ($packageDetails['Depends'] as &$dependency) {
            $dependency['reference'] = $this->packageExists($dependency['name']) ?
                \Url::to('packages/show/' . $dependency['name']) :
                null;
        }

        return $packageDetails;
    }
}
