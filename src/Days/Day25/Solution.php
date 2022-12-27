<?php

namespace AdventOfCode\Days\Day25;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    const DICT = [
        0 => 0,
        '-' => -1,
        '=' => -2
    ];

    public function execute()
    {
        $snafuNumbers = $this->getInputArray();
        $base10Numbers = array_map([self::class, 'snafuToBase10'], $snafuNumbers);
        $this->part1 = self::base10ToSnafu(array_sum($base10Numbers));
    }

    private static function snafuToBase10(string $snafu) : int
    {
        $number = 0;
        foreach (array_reverse(str_split($snafu)) as $index => $char) {
            $number += pow(5, $index) * (self::DICT[$char] ?? $char);
        }
        return $number;
    }

    private static function base10ToSnafu(int $number) : string
    {
        $base5 = base_convert($number,10,5);
        $reverseDic = array_flip(self::DICT);
        $snafu = [];
        foreach (array_reverse(str_split($base5)) as $char) {
            $number = $char + ($over ?? 0);
            $over = isset($reverseDic[$number - 5]) ? 1 : 0;
            $snafu[] = $reverseDic[$number - 5] ?? $number;
        }
        return implode(array_reverse($snafu));
    }
}