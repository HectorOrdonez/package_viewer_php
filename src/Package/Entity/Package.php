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

    public function __construct(array $details)
    {
        $this->name = $details['name'];
        $this->description = $details['description'];
        $this->dependencies = $details['dependencies'];
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
