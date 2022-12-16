<?php

namespace AdventOfCode\Days\Day15;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;

class Solution extends BaseDay
{
    public function execute()
    {
        /** @var Sensor[] $sensors */
        $sensors = $this->getSensors();

        $ranges = [];
        foreach ($sensors as $sensor) {
            if ($range = $sensor->getReachableRangeInRow(2000000)) {
                $this->addRange($ranges, $range[0], $range[1]);
            }
        }
        $this->part1 = $this->count($ranges);

        $targetRadius = 4000000;
        for ($i = 0; $i < $targetRadius; $i++) {
            $ranges = [];
            foreach ($sensors as $sensor) {
                if ($range = $sensor->getReachableRangeInRow($i, [0, $targetRadius])) {
                    $this->addRange($ranges, $range[0], $range[1]);
                }
            }
            $count = $this->count($ranges);
            if ($count !== $targetRadius) {
                $this->part2 = $this->getGapLocation($ranges) * $targetRadius + $i;
                break;
            }
        }
    }

    private function getSensors() : array
    {
        $regex = '/Sensor at x=(?<sensorX>-?\d*), y=(?<sensorY>-?\d*): closest beacon is at x=(?<beaconX>-?\d*), y=(?<beaconY>-?\d*)/';
        $sensors = [];
        foreach ($this->getInputArray() as $row) {
            preg_match($regex, $row, $matches);
            $sensor = new Location($matches['sensorX'], $matches['sensorY']);
            $beacon = new Location($matches['beaconX'], $matches['beaconY']);
            $sensors[] = new Sensor($sensor, $beacon);
        }
        return $sensors;
    }

    private function addRange(array &$ranges, int $start, int $end)
    {
        foreach ($ranges as $index => $range) {
            if ($start <= $range[1] && $end >= $range[0]) {
                $start = min($start, $range[0]);
                $end = max($end, $range[1]);
                unset($ranges[$index]);
            }
        }
        $ranges[] = [$start, $end];
        usort($ranges, fn (array $a, array $b): int => $a[0] - $b[0]);
    }

    private function count(array $ranges) : int
    {
        return array_sum(array_map(function (array $range) {
            return $range[1] - $range[0] + 1;
        }, $ranges)) - 1;
    }

    private function getGapLocation(array $ranges) : int
    {
        $previousEnd = null;
        foreach ($ranges as $range) {
            if ($previousEnd == 0) {
                $previousEnd = $range[1];
                continue;
            }
            if (abs($range[0] - $previousEnd) > 1) {
                return $range[0] - 1;
            }
        }
        throw new \Exception('No gap');
    }
}