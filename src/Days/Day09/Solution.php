<?php

namespace AdventOfCode\Days\Day09;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        $rope1 = $this->getRope(1);
        $rope2 = $this->getRope(9);

        foreach ($this->getInputArray() as $move) {
            [$direction, $amount] = explode(' ', $move);
            for ($i = 0; $i < $amount; $i++) {
                $rope1->move($direction);
                $rope2->move($direction);
            }
        }
        $this->part1 = count(array_unique($rope1->getTailHistory()));
        $this->part2 = count(array_unique($rope2->getTailHistory()));
    }

    private function getRope(int $tailLength) : RopeSegment
    {
        $rope = new RopeSegment();
        for ($i = 0; $i < $tailLength; $i++) {
            $rope = new RopeSegment($rope);
        }
        return $rope;
    }
}