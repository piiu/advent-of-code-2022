<?php

namespace AdventOfCode\Common\Console;

use AdventOfCode\Common\BaseDay;

class Utils
{
    public static function output(string $message)
    {
        echo $message . PHP_EOL;
    }

    public static function outputArray(array $lines)
    {
        foreach ($lines as $line) {
            self::output($line);
        }
    }

    public static function getDayNumberFromArgument() : ?int
    {
        return $_SERVER['argv'][1] ?? null;
    }

    public static function getIsExampleFromArgument() : bool
    {
        return !empty($_SERVER['argv'][2]) && $_SERVER['argv'][2] === 'e';
    }

    public static function getClassByDayNumber(int $dayNumber, bool $isExample) : ?BaseDay
    {
        $day = str_pad($dayNumber, 2, '0', STR_PAD_LEFT);
        $nameSpace = "AdventOfCode\Days\Day$day";
        if (!class_exists($class = "$nameSpace\Solution")) {
            return null;
        }
        $inputFile = __DIR__ . "\..\..\Days\Day$day\\" . ($isExample ? 'example' : 'input');
        if (!file_exists($inputFile)) {
            return null;
        }
        return new $class(file_get_contents($inputFile));
    }
}