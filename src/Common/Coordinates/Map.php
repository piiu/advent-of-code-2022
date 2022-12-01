<?php

namespace AdventOfCode\Common\Coordinates;

use AdventOfCode\Console\Utils;

class Map
{
    private array $map;

    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    public function getValue(Location $point) : ?string
    {
        return $this->map[$point->y][$point->x] ?? null;
    }

    public function setValue(Location $point, string $value)
    {
        $this->map[$point->y][$point->x] = $value;
    }

    public function draw(array $output = [])
    {
        $minX = min(array_keys($this->map[0]));
        $maxX = max(array_keys($this->map[0]));
        $minY = min(array_keys($this->map));
        $maxY = max(array_keys($this->map));
        for ($y = $minY; $y <= $maxY; $y++) {
            $row = '';
            for ($x = $minX; $x <= $maxX; $x++) {
                $row .= $this->getValue(new Location($x, $y)) ?? ' ';
            }
            $output[] = $row;
        }
        Utils::outputArray($output);
    }
}