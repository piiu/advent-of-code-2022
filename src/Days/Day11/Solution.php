<?php

namespace AdventOfCode\Days\Day11;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        $monkeys = $this->getMonkeys();
        for ($i = 0; $i < 20; $i++) {
            foreach ($monkeys as $monkey) {
                while ($item = $monkey->inspect()) {
                    $monkey->throw($item);
                }
            }
        }
        $this->part1 = $this->getMonkeyBusinessLevel($monkeys);
    }

    private function getMonkeys() {
        $monkeys = [];
        $regex = '/Monkey (?<id>\d+):\s*Starting items: (?<items>.*)\s*Operation: new = (?<operation>.*)\s*Test: divisible by (?<divisible>.*)\s*If true: throw to monkey (?<true>.*)\s*If false: throw to monkey (?<false>.*)/';
        foreach ($this->getInputArray("\n\r\n") as $rawMonkey) {
            preg_match($regex, $rawMonkey, $matches);
            $monkeys[$matches['id']] = new Monkey(
                explode(', ', $matches['items']),
                $matches['operation'],
                $matches['divisible'],
                $matches['true'],
                $matches['false']
            );
        }

        foreach ($monkeys as $monkey) {
            $monkey->setTrueToMonkey($monkeys[$monkey->getTrueTo()]);
            $monkey->setFalseToMonkey($monkeys[$monkey->getFalseTo()]);
        }

        return $monkeys;
    }

    private function getMonkeyBusinessLevel(array $monkeys) : int
    {
        $inspectionCounts = array_map(function(Monkey $monkey) {
            return $monkey->getInspectionCount();
        }, $monkeys);
        rsort($inspectionCounts);

        return array_product(array_splice($inspectionCounts, 0, 2));
    }
}