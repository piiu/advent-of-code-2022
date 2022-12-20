<?php

namespace AdventOfCode\Days\Day20;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Console\Utils;

class Solution extends BaseDay
{
    public function execute()
    {
        $numbers = [];
        foreach ($this->getInputArray() as $order => $number) {
            $numbers[] = new Number($order, $number);
        }

        foreach ($numbers as $number) {
            $number->mix($numbers);
        }

        foreach ($numbers as $number) {
            if ($number->value === 0) {
                $zeroPosition = $number->currentPosition;
                break;
            }
        }

        $numberCount = count($numbers);
        $wantedPositions = [($zeroPosition + 1000) % $numberCount, ($zeroPosition + 2000) % $numberCount, ($zeroPosition + 3000) % $numberCount];
        foreach ($numbers as $number) {
            if (in_array($number->currentPosition, $wantedPositions)) {
                $this->part1 += $number->value;
            }
        }
    }
}