<?php

namespace AgriPlace\Package\Entity;

use Illuminate\Contracts\Support\Arrayable;

class Package implements Arrayable
{
    private $name;
    private $description;

    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
