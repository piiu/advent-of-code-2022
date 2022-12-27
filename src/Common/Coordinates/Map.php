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

    public function empty()
    {
        $this->map = [];
    }

    public function getValue(Location $point) : ?string
    {
        return $this->map[$point->y][$point->x] ?? null;
    }

    public function setValue(Location $point, string $value)
    {
        $this->map[$point->y][$point->x] = $value;
    }

    public function draw(string $empty = ' ')
    {
        $output = [''];
        [$minX, $maxX] = $this->getXRange();
        [$minY, $maxY] = $this->getYRange();
        for ($y = $minY; $y <= $maxY; $y++) {
            $row = '';
            for ($x = $minX; $x <= $maxX; $x++) {
                $row .= $this->getValue(new Location($x, $y)) ?? $empty;
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
        $min = PHP_INT_MAX;
        $max = -PHP_INT_MAX;
        foreach ($this->map as $row) {
            $min = min($min, min(array_keys($row)));
            $max = max($max, max(array_keys($row)));
        }
        return [$min, $max];
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

    public function findLocationOfValue(string $target) : ?Location
    {
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === $target) {
                    return new Location($x, $y);
                }
            }
        }
        return null;
    }

    public function setValueInLine(Location $location1, Location $location2, string $lineMarker)
    {
        foreach (range($location1->y, $location2->y) as $y) {
            foreach (range($location1->x, $location2->x) as $x) {
                $this->setValue(new Location($x, $y), $lineMarker);
            }
        }
    }

    public function ksort()
    {
        ksort($this->map);
    }

    public function getFirstNonEmptyOnY(int $y) : Location
    {
        foreach ($this->map[$y] as $x => $value) {
            if (!empty(trim($value))) {
                return new Location($x, $y);
            }
        }
    }

    public function getLastNonEmptyOnY(int $y) : Location
    {
        foreach (array_reverse($this->map[$y], true) as $x => $value) {
            if (!empty(trim($value))) {
                return new Location($x, $y);
            }
        }
    }

    public function getFirstNonEmptyOnX(int $x) : Location
    {
        foreach ($this->map as $y => $row) {
            $newLocation = new Location($x, $y);
            if (!empty(trim($this->getValue($newLocation)))) {
                return $newLocation;
            }
        }
    }

    public function getLastNonEmptyOnX(int $x) : Location
    {
        foreach (array_reverse($this->map, true) as $y => $row) {
            if (!empty(trim($this->getValue(new Location($x, $y))))) {
                return new Location($x, $y);
            }
        }
    }
}