<?php

namespace AdventOfCode\Days\Day17;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Days\Day17\Rock\HBar;
use AdventOfCode\Days\Day17\Rock\Rock;

class Solution extends BaseDay
{
    private Map $map;
    private array $directions;
    private int $directionIndex = 0;

    public function execute()
    {
        $this->map = new Map([array_fill(0, 6, '-')]);
        $this->directions = $this->getInputSignal();

        $rockCount = 1;
        $currentRock = $this->getNextRock(HBar::class);
        while ($rockCount <= 2022) {
            while (true) {
                $direction = $this->getDirection();

                if ($currentRock->canMove($this->map, $direction)) {
                    $currentRock->move($direction);
                }
                if ($currentRock->canMove($this->map, Location::DOWN)) {
                    $currentRock->move(Location::DOWN);
                } else {
                    $currentRock->rest($this->map);
                    $this->map->ksort();
                    break;
                }
            }

            $rockCount++;
            $currentRock = $this->getNextRock($currentRock->getNextRockClass());
        }

        $this->part1 = abs($this->getHighestPoint());
    }

    private function getHighestPoint() : int
    {
        return array_key_first($this->map->getMap());
    }

    private function getNextRock(string $className) : Rock
    {
        return new $className(new Location(2, $this->getHighestPoint() - 4));
    }

    private function getDirection() : string
    {
        if (!isset($this->directions[$this->directionIndex])) {
            $this->directionIndex = 0;
        }
        $arrow = $this->directions[$this->directionIndex];
        $this->directionIndex++;
        return match ($arrow) {
            '>' => Location::RIGHT,
            '<' => Location::LEFT
        };
    }
}