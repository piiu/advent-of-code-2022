<?php

namespace AdventOfCode\Common\Coordinates;

class Map3D
{
    /** @var Map[] */
    private array $maps;

    public function __construct(array $maps = [])
    {
        $this->maps = $maps;
    }

    public function getValue(Location $point) : ?string
    {
        if (empty($this->maps[$point->z])) {
            return null;
        }
        return $this->maps[$point->z]->getValue($point) ?? null;
    }

    public function setValue(Location $point, string $value)
    {
        $mapInZ = $this->maps[$point->z] ?? new Map();
        $mapInZ->setValue($point, $value);
        $this->maps[$point->z] = $mapInZ;
    }

    public function getMaps(): array
    {
        return $this->maps;
    }
}