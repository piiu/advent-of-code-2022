<?php

namespace AdventOfCode\Days\Day14;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Console\Utils;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Solution extends BaseDay
{
    private const ROCK = '#';
    private const SAND = 'o';

    public function execute()
    {
        $map = new Map();
        $lowestRock = 0;
        $startingLocation = new Location(500, 0);
        $possibilities = [[Location::DOWN], [Location::DOWN, Location::LEFT], [Location::DOWN, Location::RIGHT]];

        foreach ($this->getInputArray() as $row) {
            $previousLocation = null;
            foreach (explode(' -> ', $row) as $point) {
                [$x, $y] = explode(',', $point);
                $location = new Location($x, $y);
                if ($previousLocation) {
                    $map->setValueInLine($previousLocation, $location, self::ROCK);
                }
                $previousLocation = $location;
                $lowestRock = (int)max($lowestRock, $y);
            }
        }

        do {
            $sandLocation = clone $startingLocation;
            do {
                foreach ($possibilities as $possibility) {
                    $potentialLocation = (clone $sandLocation)->moveMultiple($possibility);
                    $canMove = empty($map->getValue($potentialLocation)) && $potentialLocation->y < $lowestRock + 2;
                    if ($canMove) {
                        if ($potentialLocation->y === $lowestRock && empty($this->part1)) {
                            $this->part1 = $this->part2;
                        }
                        $sandLocation = $potentialLocation;
                        break;
                    }
                }
            } while ($canMove);

            $map->setValue($sandLocation, self::SAND);
            $this->part2++;
        } while (!$sandLocation->isEqual($startingLocation));
    }
}