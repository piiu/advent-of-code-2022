<?php

namespace AdventOfCode\Common;

use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Console\Utils;

abstract class BaseDay
{
    private string $input;
    protected string $part1;
    protected string $part2;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public abstract function execute();

    public function results()
    {
        $time = microtime();
        $this->execute();
        Utils::outputArray([
            'Part 1: ' . $this->part1,
            'Part 2: ' . $this->part2,
            'Total runtime: ' . round(microtime() - $time, 6) . 'ms'
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