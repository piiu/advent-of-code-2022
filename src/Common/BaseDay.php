<?php

namespace AdventOfCode\Common;

use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Console\Utils;

abstract class BaseDay
{
    private string $input;
    protected string $part1 = '0';
    protected string $part2 = '0';

    public function __construct($input)
    {
        $this->input = $input;
    }

    public abstract function execute();

    public function results()
    {
        $this->execute();
        Utils::outputArray([
            'Part 1: ' . $this->part1,
            'Part 2: ' . $this->part2
        ]);
    }

    protected function getInputArray(string $delimiter = PHP_EOL, $trim = true) : array
    {
        if ($trim) {
            return array_map('trim', explode($delimiter, $this->input));
        }
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