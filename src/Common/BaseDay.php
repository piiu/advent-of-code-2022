<?php

namespace AdventOfCode\Common;

use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Console\Utils;

abstract class BaseDay
{
    private int $dayNumber;
    private string $input;
    protected string $part1 = "0";
    protected string $part2 = "0";

    public function __construct()
    {
        ini_set('memory_limit', '512M');
        $dayNumber = preg_replace("/[^0-9]/", "", get_class($this));
        $inputFile = __DIR__ . '\..\..\input\day' . $dayNumber;
        if (file_exists($inputFile)) {
            $this->input = file_get_contents($inputFile);
        }
        $this->dayNumber = (int)$dayNumber;
    }

    public abstract function execute();

    public function results()
    {
        Utils::output("######## Day $this->dayNumber ########");
        $this->execute();
        Utils::outputArray([
            'Part 1: ' . $this->part1,
            'Part 2: ' . $this->part2
        ]);
    }

    protected function getInputArray(string $delimiter = PHP_EOL) : array
    {
        return explode($delimiter, $this->input);
    }

    protected function getInputMap() : Map
    {
        $map = array_map(function ($row) {
            return str_split($row);
        }, $this->getInputArray());
        return new Map($map);
    }
}