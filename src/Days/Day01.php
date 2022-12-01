<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day01 extends BaseDay
{
    public function execute()
    {
        $elves = array_map(function($elf) {
            return array_sum(explode("\n", $elf));
        }, $this->getInputArray("\n\r\n"));
        rsort($elves);
        $this->part1 = $elves[0];
        $this->part2 = array_sum(array_splice($elves, 0, 3));
    }
}