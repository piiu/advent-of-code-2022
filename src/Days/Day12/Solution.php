<?php

namespace AdventOfCode\Days\Day12;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Solution extends BaseDay
{
    private Map $map;
    private array $shortestTrips = [];
    private array $finalHistory;

    public function execute()
    {
        ini_set('xdebug.max_nesting_level', '-1');
        $this->map = $this->getInputMap();
        $currentLocation = $this->map->findLocationOfValue('S');
        $this->map->setValue($currentLocation, 'a');
        $destinationLocation = $this->map->findLocationOfValue('E');
        $this->map->setValue($destinationLocation, 'z');

        $this->find($destinationLocation, $currentLocation);

        foreach ($this->finalHistory as $entry) {
            [$x, $y] = explode('|', $entry);
            $this->map->setValue(new Location($x, $y), '+');
        }
        $this->map->draw(); // Part 2 can be found on here if you're smart about it
    }

    private function find(Location $currentLocation, Location $destinationLocation, array $history = [])
    {
        $currentSteps = count($history);
        $currentValue = $this->map->getValue($currentLocation);
        $locationString = $currentLocation->getAsString();

        if ((!empty($this->part1) && $currentSteps > $this->part1) || in_array($locationString, $history)) {
            return;
        }

        if ($currentLocation->isEqual($destinationLocation)) {
            $this->part1 = $currentSteps;
            $this->finalHistory = $history;
            return;
        }

        if (isset($this->shortestTrips[$locationString]) && $this->shortestTrips[$locationString] <= $currentSteps) {
            return;
        } else {
            $this->shortestTrips[$locationString] = $currentSteps;
        }

        $history[] = $locationString;
        foreach (Location::ALL_DIRECTIONS as $direction) {
            $newLocation = (clone $currentLocation)->move($direction);
            $newValue = $this->map->getValue($newLocation);

            if ($newValue && ord($currentValue) - ord($newValue) <= 1) {
                $this->find($newLocation, $destinationLocation, $history);
            }
        }
    }
}