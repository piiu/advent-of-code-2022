<?php

namespace AdventOfCode\Days\Day11;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        $this->part1 = $this->solve(20);
        $this->part2 = $this->solve(10000, false);
    }

    private function solve(int $cycles, bool $divideByThree = true) : int
    {
        $monkeys = $this->getMonkeys();
        $commonProduct = array_product(array_map(function($monkey) {
            return $monkey->divisibleTest;
        }, $monkeys));

        for ($i = 0; $i < $cycles; $i++) {
            foreach ($monkeys as $monkey) {
                while ($item = $monkey->inspect($divideByThree)) {
                    $monkey->throw($item, $commonProduct);
                }
            }
        }
        return $this->getMonkeyBusinessLevel($monkeys);
    }

    /**
     * @return Monkey[]
     */
    private function getMonkeys() : array
    {
        $monkeys = [];
        $regex = '/Monkey (?<id>\d+):\s*Starting items: (?<items>.*)\s*Operation: new = (?<operation>.*)\s*Test: divisible by (?<divisible>.*)\s*If true: throw to monkey (?<true>.*)\s*If false: throw to monkey (?<false>.*)/';
        foreach ($this->getInputArray("\n\r\n") as $rawMonkey) {
            preg_match($regex, $rawMonkey, $matches);
            $monkeys[$matches['id']] = new Monkey(
                $matches['id'],
                explode(', ', $matches['items']),
                $matches['operation'],
                $matches['divisible'],
                $matches['true'],
                $matches['false']
            );
        }

        foreach ($monkeys as $monkey) {
            $monkey->trueToMonkey = $monkeys[$monkey->trueTo];
            $monkey->falseToMonkey =$monkeys[$monkey->falseTo];
        }

        return $monkeys;
    }

    private function getMonkeyBusinessLevel(array $monkeys) : int
    {
        $inspectionCounts = array_map(function(Monkey $monkey) {
            return $monkey->inspectionCount;
        }, $monkeys);
        rsort($inspectionCounts);

        return array_product(array_splice($inspectionCounts, 0, 2));
    }
}