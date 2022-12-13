<?php

namespace AdventOfCode\Days\Day13;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Console\Utils;

class Solution extends BaseDay
{

    public function execute()
    {
        $pairs = array_map(function(string $rawPair) {
            return array_map(function (string $stringValue) {
                return json_decode($stringValue, true);
            }, explode("\n", $rawPair));
        }, $this->getInputArray("\n\r\n"));

        foreach ($pairs as $i => $pair) {
            if (self::compare($pair[0], $pair[1])) {
                $this->part1 += $i + 1;
            }
        }

        $allSignals = array_filter(array_map(function(string $stringValue) {
            return !empty($stringValue) ? json_decode($stringValue, true) : null;
        }, $this->getInputArray()), function(?array $signal) {
            return !empty($signal);
        });
        $decoderKeys = [[[2]], [[6]]];
        $allSignals = array_merge($allSignals, $decoderKeys);
        usort($allSignals, [self::class, 'compare']);

        $this->part2 = 1;
        foreach (array_reverse($allSignals) as $i => $signal) {
            if (in_array($signal, $decoderKeys)) {
                $this->part2 *= $i + 2;
            }
        }
    }

    private static function compare(array $l1, array $l2) : ?bool
    {
        if (empty($l1) && empty($l2)) {
            return null;
        }
        foreach (range(0, max(count($l1), count($l2)) - 1) as $i) {
            if (!isset($l1[$i]) || !isset($l2[$i])) {
                return isset($l2[$i]);
            }
            if (is_int($l1[$i]) && is_int($l2[$i])) {
                if ($l1[$i] !== $l2[$i]) {
                    return $l1[$i] < $l2[$i];
                }
                continue;
            }
            $result = self::compare((array)$l1[$i], (array)$l2[$i]);
            if (!is_null($result)) {
                return $result;
            }
        }
        return null;
    }
}