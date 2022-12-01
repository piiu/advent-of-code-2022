<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day01 extends BaseDay
{
    public function execute()
    {
        $food = $this->getInputArray();
        $elves = [];
        $sum = 0;
        foreach ($food as $calories) {
            if (empty(trim($calories))) {
                $elves[] = $sum;
                $sum = 0;
            }
            $sum += (int)$calories;
        }
        rsort($elves);
        $this->part1 = $elves[0];
        $this->part2 = array_sum(array_splice($elves, 0, 3));
    }
}