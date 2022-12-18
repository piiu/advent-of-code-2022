<?php

namespace AdventOfCode\Days\Day17;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Days\Day17\Rock\HBar;

class Solution extends BaseDay
{
    private array $directions;
    private int $directionIndex = 0;

    public function execute()
    {
        $map = new Map([array_fill(0, 6, '-')]);
        $this->directions = $this->getInputSignal();

        $rockCount = 1;

        $moveRockCombinations = [];


        $firstCycleState = null;
        $currentRock = new HBar(new Location(2, -4));
        while (true) {
            while (true) {
                $combination = $this->directionIndex . '|' . $currentRock->getName();
                if (in_array($combination, $moveRockCombinations)) {
                    if (!$firstCycleState) {
                        $firstCycleState = new Map($map->getMap());
                        $firstCycleRockCount = $rockCount;
                        $firstCycleHeight = $this->getHeight($firstCycleState);
                        $moveRockCombinations = [];
                    } else {
                        $loopHeight = $this->getHeight($map) - $firstCycleHeight;
                        $loopRockCount = $rockCount - $firstCycleRockCount;
                        break 2;
                    }
                }
                $moveRockCombinations[] = $combination;

                $direction = $this->getDirection();
                if ($currentRock->canMove($map, $direction)) {
                    $currentRock->move($direction);
                }
                if ($currentRock->canMove($map, Location::DOWN)) {
                    $currentRock->move(Location::DOWN);
                } else {
                    $currentRock->rest($map);
                    break;
                }
            }
            if ($rockCount === 2022) {
                $this->part1 = abs($this->getHeight($map));
            }

            $rockCount++;
            $nextRockClass = $currentRock->getNextRockClass();
            $currentRock = new $nextRockClass(new Location(2, $this->getHeight($map) - 4));
        }

        $cycles = floor((1000000000000 - $firstCycleHeight) / $loopHeight);
        $rocksRemaining = 1000000000000 - $cycles * $loopRockCount - $firstCycleRockCount;

        for ($i = 0; $i < $rocksRemaining; $i++) {
            $direction = $this->getDirection();
            if ($currentRock->canMove($firstCycleState, $direction)) {
                $currentRock->move($direction);
            }
            if ($currentRock->canMove($firstCycleState, Location::DOWN)) {
                $currentRock->move(Location::DOWN);
            } else {
                $currentRock->rest($firstCycleState);
                break;
            }
        }

        $this->part2 = $this->getHeight($firstCycleState) + $cycles * $loopHeight;
    }

    private function getHeight(Map $map) : int
    {
        return abs(array_key_first($map->getMap()));
    }

    private function getDirection() : string
    {
        $arrow = $this->directions[$this->directionIndex];
        $this->directionIndex++;
        $this->directionIndex = $this->directionIndex % count($this->directions);
        return match ($arrow) {
            '>' => Location::RIGHT,
            '<' => Location::LEFT
        };
    }
}