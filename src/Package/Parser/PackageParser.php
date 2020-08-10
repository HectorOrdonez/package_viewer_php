<?php

namespace AgriPlace\Package\Parser;

/**
 * Class PackageParser
 * @package AgriPlace\Package\Parser
 *
 * Extracts data from raw source for a given package
 */
class PackageParser
{
    /**
     * Extracts package related information from the given source
     * It assumes the package exists in it
     *
     * @param array $source
     * @param string $package
     * @return array
     */
    public function parse($source, $package): array
    {
        $fileFound = false;
        $data = ['dependencies' => []];

        // First we need to find where the package info starts
        foreach ($source as $record) {
            if ($record[0] == 'Package') {
                // File was already found, we are now facing another package
                if ($fileFound == true) {
                    return $data;
                }

                $packageName = trim($record[1]);

                if ($packageName == $package) {
                    // This is the package we are looking for!
                    $fileFound = true;
                    $data['name'] = $packageName;
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

                        $dependencies[] = [
                            'name' => $dependencyName,
                        ];
                    }

                    $data['dependencies'] = $dependencies;
                } else if ($record[0] == 'Description') {
                    $data['description'] = trim($record[1]);
                }
            }
        }

        // End of file
        return $data;
    }
}
