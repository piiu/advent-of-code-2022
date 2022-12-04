<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day04 extends BaseDay
{
    public function execute()
    {
        $pairs = array_map(function($row) {
            return array_map(function ($elf) {
                [$from, $to] = explode('-', $elf);
                return range($from, $to);
            }, explode(',', $row));
        }, $this->getInputArray());

        $this->part1 = count(array_filter($pairs, function ($pair) {
            return !array_diff($pair[0], $pair[1]) || !array_diff($pair[1], $pair[0]);
        }));

        $this->part2 = count(array_filter($pairs, function ($pair) {
            return array_intersect($pair[0], $pair[1]);
        }));
    }
}