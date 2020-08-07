<?php

namespace AgriPlace\Package;

use AgriPlace\Package\Entity\Package;

interface PackageRepositoryInterface
{
    /**
     * Returns the names of all the packages installed
     * @return array
     */
    public function findAllNames();

    /**
     * Returns a package by its name
     * @param string $name
     * @return Package
     */
    public function findOneByName($name);
}
