<?php

namespace AdventOfCode\Days\Day18;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map3D;

class Solution extends BaseDay
{
    const LAVA = '#';

    public function execute()
    {
        $map = new Map3D();

        foreach ($this->getInputArray() as $row) {
            [$x, $y, $z] = explode(',', $row);
            $map->setValue(new Location($x, $y, $z), self::LAVA);
        }

        foreach ($map->getMaps() as $z => $layer) {
            foreach ($layer->getMap() as $y => $row) {
                foreach ($row as $x => $value) {
                    foreach (Location::ALL_DIRECTIONS_3D as $direction) {
                        $neighbour = (new Location($x, $y, $z))->move($direction);
                        if (!$map->getValue($neighbour)) {
                            $this->part1++;
                        }
                    }
                }
            }
        }
    }
}