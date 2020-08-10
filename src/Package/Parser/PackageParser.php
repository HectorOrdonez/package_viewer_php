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
     * @param $packageName
     * @return array
     */
    public function parse($source, $packageName): array
    {
        $packageFound = false;
        $data = [
            'name' => $packageName,
            'dependencies' => []
        ];

        // First we need to find where the package info starts
        foreach ($source as $record) {
            if ($record[0] == 'Package') {
                // Package was already found, we are now facing another package
                if ($packageFound == true) {
                    return $data;
                }

                if ($packageName == trim($record[1])) {
                    // This is the package we are looking for!
                    $packageFound = true;
                    continue;
                }
            }

            if ($packageFound == true) {
                $this->addData($data, $record);
            }
        }

        // End of package data
        return $data;
    }

    private function addData(array &$data, $record): void
    {
        // We do not need information related to multiple lines
        if (count($record) == 1) {
            return;
        }

        switch ($record[0]) {
            case 'Depends':
                $data['dependencies'] = $this->parseDependencies($record);
                break;
            case 'Description':
                $data['description'] = trim($record[1]);
                break;
            default:
                // We do not need this data
                return;
        }
    }

    /**
     * @param $record
     * @return array
     */
    private function parseDependencies($record): array
    {
        $rawDependencies = explode(', ', trim($record[1]));
        $dependencies = [];

        foreach ($rawDependencies as $dependency) {
            list($dependencyName) = explode(' ', $dependency);

            $dependencies[] = [
                'name' => $dependencyName,
            ];
        }
        return $dependencies;
    }
}
