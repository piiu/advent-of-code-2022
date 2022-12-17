<?php

namespace AdventOfCode\Days\Day17\Rock;

use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

abstract class Rock
{
    const RESTING = '#';

    /** @var Location[] */
    protected array $locations;
    protected string $nextRockClass;

    abstract function __construct(Location $start);

    public function canMove(Map $map, string $direction) : bool
    {
        foreach ($this->locations as $location) {
            $newLocation = (clone $location)->move($direction);
            if ($newLocation->x < 0 || $newLocation->x > 6 || $map->getValue($newLocation)) {
                return false;
            }
        }
        return true;
    }

    public function move(string $direction)
    {
        foreach ($this->locations as $location) {
            $location->move($direction);
        }
    }

    public function rest(Map $map)
    {
        foreach ($this->locations as $location) {
            $map->setValue($location, self::RESTING);
        }
    }

    public function getNextRockClass(): string
    {
        return $this->nextRockClass;
    }
}