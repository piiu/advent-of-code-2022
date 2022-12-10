<?php

namespace AdventOfCode\Days\Day05;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    public function execute()
    {
        [$rawCrane, $moves] = $this->getInputArray("\n\r\n", false);
        $crane1 =  $crane2 = $this->getCrane($rawCrane);

        foreach (explode("\n", $moves) as $move) {
            preg_match('/move (?<count>\d+) from (?<from>\d+) to (?<to>\d+)/', $move, $matches);
            $crane2Picks = [];
            for ($i = 0; $i < $matches['count']; $i++) {
                $crane1[$matches["to"]][] = array_pop($crane1[$matches['from']]);
                $crane2Picks[] = array_pop($crane2[$matches['from']]);
            }
            $crane2[$matches["to"]] += array_merge($crane2[$matches["to"]], array_reverse($crane2Picks));
        }

        $this->part1 = join('', array_map('array_pop', $crane1));
        $this->part2 = join('', array_map('array_pop', $crane2));
    }

    private function getCrane(string $rawCrane) : array
    {
        $rawCrane = array_reverse(explode("\n", $rawCrane));
        $craneKeys = str_split(trim(str_replace(' ', '', array_shift($rawCrane))));
        $crane = array_fill_keys($craneKeys, []);
        foreach ($rawCrane as $row) {
            $row = str_split($row);
            foreach ($crane as $stack => $content) {
                $index = -3 + 4 * $stack;
                if ($row[$index] !== ' ') {
                    $crane[$stack][] = $row[$index];
                }
            }
        }
        return $crane;
    }
}