<?php

namespace AdventOfCode\Days\Day24;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Solution extends BaseDay
{
    const EMPTY = '.';
    const WALL = '#';
    const BLIZZARD = '@';

    /** @var Blizzard[] */
    private array $blizzards = [];
    private Map $map;
    private Location $entrance;
    private Location $destination;

    public function execute()
    {
        $this->init();
        $locations = [$this->entrance];
        $reachedEndOnce = false;
        $haveSnacks = false;
        $minute = 0;

        do {
            $possibleMoves = [];
            $map = $this->getNextMap();
            $minute++;

            foreach ($locations as $location) {
                foreach (Location::ALL_DIRECTIONS as $direction) {
                    $newLocation = (clone $location)->move($direction);
                    if ($newLocation->isEqual($this->destination)) {
                        if (!$reachedEndOnce) {
                            $this->part1 = $minute;
                            $reachedEndOnce = true;
                            $possibleMoves = [$this->destination];
                            break 2;
                        } elseif ($haveSnacks) {
                            $this->part2 = $minute;
                            break 3;
                        }
                    }
                    if ($reachedEndOnce && !$haveSnacks && $newLocation->isEqual($this->entrance)) {
                        $haveSnacks = true;
                        $possibleMoves = [$this->entrance];
                        break 2;
                    }

                    if ($map->getValue($newLocation) === self::EMPTY) {
                        $possibleMoves[$newLocation->toString()] = $newLocation;
                    }
                }
                if ($location->isEqual($this->entrance) || $location->isEqual($this->destination)
                    || $map->getValue($location) === self::EMPTY) {
                    $possibleMoves[$location->toString()] = $location;
                }
            }

            $locations = $possibleMoves;
        } while (true);
    }

    private function init()
    {
        $this->map = $this->getInputMap();
        foreach ($this->map->getMap() as $y => $row) {
            foreach ($row as $x => $value) {
                if (in_array($value, array_keys(Blizzard::DIRECTION_MAP))) {
                    $location = new Location($x, $y);
                    $this->blizzards[] = new Blizzard($location, $value);
                }
            }
        }
        [$minY, $maxY] = $this->map->getYRange();
        $this->destination = new Location($this->getFirstEntranceOnRow($this->map->getMap()[$maxY]), $maxY);
        $this->entrance = new Location($this->getFirstEntranceOnRow($this->map->getMap()[$minY]), $minY);
    }

    private function getNextMap() : Map
    {
        $row = array_fill_keys(range(1, count($this->map->getMap()[0]) - 2), self::EMPTY);
        $map = new Map(array_fill_keys(range(1, count($this->map->getMap()) - 2), $row));
        foreach ($this->blizzards as $blizzard) {
            $blizzard->move($this->map);
            $map->setValue($blizzard->location, self::BLIZZARD);
        }
        return $map;
    }

    private function getFirstEntranceOnRow(array $row) : int
    {
        foreach ($row as $x => $value) {
            if ($value === self::EMPTY) {
                return $x;
            }
        }
    }
}