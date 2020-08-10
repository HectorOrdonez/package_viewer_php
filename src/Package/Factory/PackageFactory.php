<?php

namespace AgriPlace\Package\Factory;

use AgriPlace\Package\Entity\Package;

class PackageFactory
{
    public function make(array $packageDetails): Package
    {
        return new Package(
            $packageDetails['Package'],
            $packageDetails['Description'],
            $packageDetails['Depends']
        );
    }
}
