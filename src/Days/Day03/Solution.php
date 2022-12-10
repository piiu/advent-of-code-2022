<?php

namespace AdventOfCode\Days\Day03;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        $sacks = $this->getInputArray();

        foreach ($sacks as $sack) {
            $halves = str_split($sack, strlen($sack) / 2);
            $this->part1 += $this->getCommonItemValue($halves);
        }

        foreach (array_chunk($sacks, 3) as $group) {
            $this->part2 += $this->getCommonItemValue($group);
        }
    }

    private function getCommonItemValue(array $lists) : int
    {
        $firstList = array_shift($lists);
        foreach (str_split($firstList) as $item) {
            foreach ($lists as $list) {
                if (!str_contains($list, $item)) {
                    continue 2;
                }
            }
            return $this->getCharValue($item);
        }
        throw new \Exception('No common item');
    }

    private function getCharValue(string $char) : int
    {
        $asciiVal = ord($char);
        return $asciiVal > 96 ? $asciiVal - 96 :  $asciiVal - 38;
    }
}