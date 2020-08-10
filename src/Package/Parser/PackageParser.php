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
     * This is a helper to go through multi-line entries such as description
     * @var string
     */
    private $currentEntry;

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
            'dependencies' => [],
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

        return $data;
    }

    private function addData(array &$data, $record): void
    {
        // If current record contains more than one cell it is a new entry
        if (count($record) > 1) {
            $this->currentEntry = $record[0];
            $currentData = trim($record[1]);
        } else {
            $currentData = trim($record[0]);
        }

        switch ($this->currentEntry) {
            case 'Depends':
                $data['dependencies'] = $this->parseDependencies($currentData);
                break;
            case 'Description':
                $data['description'][] = $currentData;
                break;
            default:
                // We do not need this data
                return;
        }
    }

    /**
     * @param string $rawData
     * @return array
     */
    private function parseDependencies(string $rawData): array
    {
        $rawDependencies = explode(', ', $rawData);
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
