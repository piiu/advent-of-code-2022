<?php

namespace AdventOfCode\Days\Day10;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Console\Utils;

class Solution extends BaseDay
{
    public function execute()
    {
        $x = 1;
        $cycle = 0;
        $line = '';

        foreach ($this->getInputArray() as $instruction) {
            $cyclesToRun = 1;
            $value = 0;
            $instructionParts = explode(' ', $instruction);
            if ($instructionParts[0] === 'addx') {
                $cyclesToRun = 2;
                $value = $instructionParts[1];
            }

            for ($i = 0; $i < $cyclesToRun; $i++) {
                $line .= abs($cycle % 40 - $x) <= 1 ? '#' : ' ';
                if (strlen($line) === 40) {
                    Utils::output($line);
                    $line = '';
                }
                $cycle++;
                if (in_array($cycle, [20, 60, 100, 140, 180, 220])) {
                    $this->part1 += $cycle * $x;
                }
            }
            $x += $value;
        }
    }
}