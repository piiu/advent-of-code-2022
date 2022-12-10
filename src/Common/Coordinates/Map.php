<?php

namespace AdventOfCode\Common\Coordinates;

use AdventOfCode\Common\Console\Utils;

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
        [$minX, $maxX] = $this->getXRange();
        [$minY, $maxY] = $this->getYRange();
        for ($y = $minY; $y <= $maxY; $y++) {
            $row = '';
            for ($x = $minX; $x <= $maxX; $x++) {
                $row .= $this->getValue(new Location($x, $y)) ?? ' ';
            }
            $output[] = $row;
        }
        Utils::outputArray($output);
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function getXRange() : array
    {
        return [min(array_keys($this->map[0])), max(array_keys($this->map[0]))];
    }

    public function getYRange() : array
    {
        return [min(array_keys($this->map)), max(array_keys($this->map))];
    }

    public function getRow(int $y) : array
    {
        return $this->map[$y];
    }

    public function getColumn(int $x) : array
    {
        return array_map(function($row) use($x) {
            return $row[$x];
        }, $this->map);
    }
}