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
        $options = getopt("d:");
        return array_pop($options) ?? null;
    }

    public static function getClassByDayNumber(int $dayNumber) : ?BaseDay
    {
        $day = str_pad($dayNumber, 2, '0', STR_PAD_LEFT);
        $class = "AdventOfCode\Days\Day$day\Solution";
        if (!class_exists($class)) {
            return null;
        }
        $inputFile = __DIR__ . "\..\..\Days\Day$day\input";
        if (!file_exists($inputFile)) {
            return null;
        }
        return new $class(file_get_contents($inputFile));
    }
}