<?php

namespace AdventOfCode\Days\Day10;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Console\Utils;

class Solution extends BaseDay
{
    public function execute()
    {
        $x = 1;
        $cycle = 1;
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
                $line .= abs(($cycle - 1) % 40 - $x) <= 1 ? '#' : ' ';
                if (strlen($line) === 40) {
                    Utils::output($line);
                    $line = '';
                }
                if ($cycle % 40 === 20) {
                    $this->part1 += $cycle * $x;
                }
                $cycle++;
            }
            $x += $value;
        }
    }
}