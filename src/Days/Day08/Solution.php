<?php

namespace AdventOfCode\Days\Day08;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;

class Solution extends BaseDay
{
    public function execute()
    {
        $forest = $this->getInputMap();

        foreach ($forest->getMap() as $y => $row) {
            foreach ($row as $x => $tree) {
                $currentTreeLocation = new Location($x, $y);
                $currentTreeHeight = $forest->getValue($currentTreeLocation);
                $isVisible = false;
                $scenicScore = 1;

                $column = $forest->getColumn($x);
                $comparisons = [
                    array_reverse(array_slice($row, 0, $x)),
                    array_slice($row, $x + 1),
                    array_reverse(array_slice($column, 0, $y)),
                    array_slice($column, $y + 1)
                ];

                foreach ($comparisons as $comparison) {
                    $blockingTrees = array_filter($comparison, function ($height) use ($currentTreeHeight) {
                        return $height >= $currentTreeHeight;
                    });
                    if (empty($blockingTrees)) {
                        $isVisible = true;
                    }

                    $viewingDistance = 0;
                    foreach ($comparison as $height) {
                        $viewingDistance++;
                        if ($height >= $currentTreeHeight) {
                            break;
                        }
                    }
                    $scenicScore *= $viewingDistance;
                }

                if ($isVisible) {
                    $this->part1++;
                }
                if ($scenicScore > $this->part2) {
                    $this->part2 = $scenicScore;
                }
            }
        }
    }
}