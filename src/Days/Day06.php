<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day06 extends BaseDay
{
    public function execute()
    {
        $signal = $this->getInputSignal();
        $this->part1 = $this->solve($signal, 4);
        $this->part2 = $this->solve($signal, 14);
    }

    private function solve(array $signal, int $numberOfChars) : int
    {
        for ($index = $numberOfChars; $index < count($signal); $index++) {
            $chunk = array_slice($signal, $index - $numberOfChars, $numberOfChars);
            if (count(array_unique($chunk)) == $numberOfChars) {
                return $index;
            }
        }
        throw new \Exception('No solution found');
    }
}