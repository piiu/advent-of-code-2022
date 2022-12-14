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
        foreach ($this->getInputArray() as $row) {
            $points = explode(' -> ' , $row);
            $previousLocation = null;
            foreach ($points as $point) {
                [$x, $y] = explode(',', $point);
                $location = new Location($x, $y);
                if ($previousLocation) {
                    $map->setValueInLine($previousLocation, $location, self::ROCK);
                }
                $lowestRock = (int)max($lowestRock, $y);
                $previousLocation = $location;
            }
        }

        $startingLocation = new Location(500, 0);
        $possibilities = [[Location::DOWN], [Location::DOWN, Location::LEFT], [Location::DOWN, Location::RIGHT]];
        $count = 0;
        while (true) {
            $sand = clone $startingLocation;
            while (true) {
                $move = false;
                foreach ($possibilities as $possibility) {
                    $potentialLocation = (clone $sand)->moveMultiple($possibility);
                    if (empty($map->getValue($potentialLocation)) && $potentialLocation->y < $lowestRock + 2) {
                        if ($potentialLocation->y === $lowestRock && empty($this->part1)) {
                            $this->part1 = $count;
                        }
                        $move = true;
                        $sand = $potentialLocation;
                        break;
                    }
                }
                if (!$move) {
                    if ($sand->isEqual($startingLocation)) {
                        $this->part2 = $count + 1;
                        break 2;
                    }

                    $map->setValue($sand, self::SAND);
                    break;
                }
            }
            $count++;
        }}
}