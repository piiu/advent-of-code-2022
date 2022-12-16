<?php

namespace AdventOfCode\Days\Day15;

use AdventOfCode\Common\Coordinates\Location;

class Sensor
{
    private Location $location;
    private int $radius;

    public function __construct(Location $location, Location $beacon)
    {
        $this->location = $location;
        $this->radius = $location->getManhattanDistanceFrom($beacon);
    }

    public function getReachableRangeInRow(int $rowNumber, array $restrictionRange = null) : ?array
    {
        if (($this->location->y < $rowNumber && $this->location->y + $this->radius < $rowNumber)
            || ($this->location->y > $rowNumber && $this->location->y - $this->radius > $rowNumber)) {
            return null;
        }

        $radiusOnX = $this->radius - abs($this->location->y - $rowNumber);

        if ($restrictionRange) {
            return [
                max($restrictionRange[0], $this->location->x - $radiusOnX),
                min($restrictionRange[1], $this->location->x + $radiusOnX)
            ];
        }

        return [$this->location->x - $radiusOnX, $this->location->x + $radiusOnX];
    }
}