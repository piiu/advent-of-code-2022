<?php

namespace AdventOfCode\Days\Day22;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Solution extends BaseDay
{
    const WALL = '#';

    private Map $map;

    public function execute()
    {
        [$rawMap, $steps] = $this->getInputArray("\n\r\n", false);
        $this->generateMaps($rawMap);

        $direction = Location::RIGHT;
        $location = $this->map->getFirstNonEmptyOnY(0);

        $distance = '';
        foreach (str_split($steps) as $step) {
            if (is_numeric($step)) {
                $distance .= $step;
                continue;
            }
            for ($i = 0; $i < (int)$distance; $i++) {
                $newLocation = $this->moveWithWrap($location, $direction);
                if ($this->map->getValue($newLocation) === self::WALL) {
                    break;
                }
                $location = $newLocation;
            }

            $distance = '';
            $direction = Location::turn($direction, $step);
        }

        $this->part1 = (1000 * ($location->y + 1))
            + (4 * ($location->x + 1))
            + array_search($direction, Location::ALL_DIRECTIONS);
    }

    private function generateMaps(string $rawMap)
    {
        $rawMapArray = array_map('str_split', array_map('rtrim', explode("\n", $rawMap)));
        $this->map = new Map($rawMapArray);
    }

    private function moveWithWrap(Location $location, string $direction) : Location
    {
        $newLocation = (clone $location)->move($direction);
        if (!empty(trim($this->map->getValue($newLocation)))) {
            return $newLocation;
        }
        return $newLocation->getWrappedLocationOnMap($this->map, $direction);
    }
}