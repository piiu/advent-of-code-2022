<?php

namespace AdventOfCode\Console;

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

    public static function getClassByDayNumber(int $dayNumber) : ?BaseDay
    {
        $day = str_pad($dayNumber, 2, '0', STR_PAD_LEFT);
        $class = "AdventOfCode\Days\Day$day";
        if (!class_exists($class)) {
            return null;
        }
        $inputFile = __DIR__ . '\..\..\input\day' . $day;
        if (!file_exists($inputFile)) {
            return null;
        }
        return new $class(file_get_contents($inputFile));
    }
}