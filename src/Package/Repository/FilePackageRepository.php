<?php

namespace AgriPlace\Package\Repository;

use AgriPlace\Package\Entity\Package;
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
        // TODO: Implement findOneByName() method.
    }
}
