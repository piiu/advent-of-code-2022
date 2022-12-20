<?php

namespace AdventOfCode\Days\Day20;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        $numbers = $this->getNumbers();
        foreach ($numbers as $number) {
            $number->mix($numbers);
        }
        foreach ($numbers as $number) {
            if (in_array($number->currentPosition, $this->getWantedPositions($numbers))) {
                $this->part1 += $number->value;
            }
        }

        $numbers = $this->getNumbers(811589153);
        for ($i=0; $i < 10; $i++) {
            foreach ($numbers as $number) {
                $number->mix($numbers);
            }
        }
        foreach ($numbers as $number) {
            if (in_array($number->currentPosition, $this->getWantedPositions($numbers))) {
                $this->part2 += $number->value;
            }
        }
    }

    private function getNumbers(int $multi = 1) : array
    {
        /** @var Number[] $numbers */
        $numbers = [];
        $count = count($this->getInputArray());
        foreach ($this->getInputArray() as $order => $number) {
            $numbers[] = new Number($order, $number * $multi, $count);
        }
        return $numbers;
    }

    private function getWantedPositions(array $numbers) {
        foreach ($numbers as $number) {
            if ($number->value === 0) {
                $numberCount = count($numbers);
                $zeroPosition = $number->currentPosition;
                return [($zeroPosition + 1000) % $numberCount, ($zeroPosition + 2000) % $numberCount, ($zeroPosition + 3000) % $numberCount];
            }
        }
    }
}