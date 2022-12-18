<?php

namespace AdventOfCode\Days\Day17;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Days\Day17\Rock\HBar;
use AdventOfCode\Days\Day17\Rock\Rock;

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
            $this->moveWhileCan($currentRock, $map);
            if ($rockCount === 2022) {
                $this->part1 = abs($this->getHighestPoint($map));
            }

            $combination = $this->directionIndex . '|' . $currentRock->getName();
            if (in_array($combination, $moveRockCombinations)) {
                if (!$firstCycleState) {
                    $firstCycleState = new Map($map->getMap());
                    $firstCycleRockCount = $rockCount;
                    $firstCycleLastMove = $currentRock::class;
                    $moveRockCombinations = [];
                } else {
                    $loopHeight = abs($this->getHighestPoint($map)) - abs($this->getHighestPoint($firstCycleState));
                    $loopRockCount = $rockCount - $firstCycleRockCount;
                    break;
                }
            }
            $moveRockCombinations[] = $combination;

            $rockCount++;
            $currentRock = $this->spawnRockByClass($currentRock->getNextRockClass(), $map);
        }

        $cycles = floor((1000000000000 - $firstCycleRockCount) / $loopRockCount);
        $rocksRemaining = 1000000000000 - $cycles * $loopRockCount - $firstCycleRockCount;
        $currentRock = $this->spawnRockByClass($firstCycleLastMove, $firstCycleState);

        for ($i = 0; $i < $rocksRemaining; $i++) {
            $currentRock = $this->spawnRockByClass($currentRock->getNextRockClass(), $firstCycleState);
            $this->moveWhileCan($currentRock, $firstCycleState);
        }

        $this->part2 = abs($this->getHighestPoint($firstCycleState)) + ($cycles * $loopHeight);
    }

    private function getHighestPoint(Map $map) : int
    {
        return array_key_first($map->getMap());
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

    private function spawnRockByClass(string $className, Map $map) : Rock
    {
        return new $className(new Location(2, $this->getHighestPoint($map) - 4));
    }

    private function moveWhileCan(Rock $currentRock, Map $firstCycleState)
    {
        $direction = $this->getDirection();
        while (true) {
            if ($currentRock->canMove($firstCycleState, $direction)) {
                $currentRock->move($direction);
            }
            if ($currentRock->canMove($firstCycleState, Location::DOWN)) {
                $currentRock->move(Location::DOWN);
            } else {
                $currentRock->rest($firstCycleState);
                break;
            }
            $direction = $this->getDirection();
        }
    }
}