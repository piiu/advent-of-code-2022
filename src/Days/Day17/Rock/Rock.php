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

    public function canMove(Map $map, string $direction, Location $exception = null) : bool
    {
        foreach ($this->locations as $location) {
            $newLocation = (clone $location)->move($direction);
            if ($newLocation->x < 0 || $newLocation->x > 6
                || $map->getValue($newLocation)
                || ($exception && $newLocation->isEqual($exception))
            ) {
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
        $map->ksort();
    }

    public function getNextRockClass(): string
    {
        return $this->nextRockClass;
    }

    public function getName() : string
    {
        $parts = explode('\\', get_class($this));
        return end($parts);
    }
}