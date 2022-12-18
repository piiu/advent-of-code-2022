<?php

namespace AdventOfCode\Days\Day18;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map3D;

class Solution extends BaseDay
{
    const LAVA = '#';

    private Map3D $map;
    private array $knownOutSide = [];
    private array $bounds = Location::MAX_BOUNDS;

    public function execute()
    {
        ini_set('xdebug.max_nesting_level', '-1');
        $this->map = new Map3D();

        foreach ($this->getInputArray() as $row) {
            [$x, $y, $z] = explode(',', $row);
            $this->bounds = [
                'minX' => min($this->bounds['minX'], $x - 1),
                'maxX' => max($this->bounds['maxX'], $x + 1),
                'minY' => min($this->bounds['minY'], $y - 1),
                'maxY' => max($this->bounds['maxY'], $y + 1),
                'minZ' => min($this->bounds['minZ'], $z - 1),
                'maxZ' => max($this->bounds['maxZ'], $z + 1),
            ];
            $this->map->setValue(new Location($x, $y, $z), self::LAVA);
        }

        $this->mapOutside(new Location($this->bounds['minX'], $this->bounds['minY'], $this->bounds['minZ']));

        foreach ($this->map->getMaps() as $z => $layer) {
            foreach ($layer->getMap() as $y => $row) {
                foreach ($row as $x => $value) {
                    foreach (Location::ALL_DIRECTIONS_3D as $direction) {
                        $neighbour = (new Location($x, $y, $z))->move($direction);
                        if (!$this->map->getValue($neighbour)) {
                            $this->part1++;
                            if (in_array($neighbour->toString(), $this->knownOutSide)) {
                                $this->part2++;
                            }
                        }
                    }
                }
            }

        }
    }

    private function mapOutside(Location $location)
    {
        $this->knownOutSide[] = $location->toString();
        foreach (Location::ALL_DIRECTIONS_3D as $direction) {
            $neighbour = (clone ($location))->move($direction);
            if (!in_array($neighbour->toString(), $this->knownOutSide) && !$neighbour->isOutOfBounds($this->bounds) && !$this->map->getValue($neighbour)) {
                $this->mapOutside($neighbour);
            }
        }
    }
}