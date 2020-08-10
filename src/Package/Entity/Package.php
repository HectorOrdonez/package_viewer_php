<?php

namespace AgriPlace\Package\Entity;

use Illuminate\Contracts\Support\Arrayable;

class Package implements Arrayable
{
    /** @var string  */
    private $name;
    /** @var string  */
    private $description;
    /** @var array  */
    private $dependencies;

    public function __construct(string $name, string $description, array $dependencies)
    {
        $this->name = $name;
        $this->description = $description;
        $this->dependencies = $dependencies;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'dependencies' => $this->dependencies,
        ];
    }
}
