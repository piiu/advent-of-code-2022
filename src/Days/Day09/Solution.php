<?php

namespace AdventOfCode\Days\Day09;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        $head = new Knot();
        for ($i = 0; $i < 9; $i++) {
            $head = new Knot($head);
        }

        foreach ($this->getInputArray() as $move) {
            [$direction, $amount] = explode(' ', $move);
            for ($i = 0; $i < $amount; $i++) {
                $head->move([$direction]);
            }
        }

        $this->part1 = count(array_unique($head->getNextKnot()->history));
        $this->part2 = count(array_unique($head->getLastKnotHistory()));
    }
}