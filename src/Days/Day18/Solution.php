<?php

namespace AdventOfCode\Days\Day18;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map3D;

class Solution extends BaseDay
{
    const LAVA = '#';

    private Map3D $map;
    private array $knownBubbles = [];
    private array $bounds = Location::MAX_BOUNDS;

    public function execute()
    {
        ini_set('xdebug.max_nesting_level', '-1');
        $this->map = new Map3D();

        foreach ($this->getInputArray() as $row) {
            [$x, $y, $z] = explode(',', $row);
            $this->bounds = [
                'minX' => min($this->bounds['minX'], $x),
                'maxX' => max($this->bounds['maxX'], $x),
                'minY' => min($this->bounds['minY'], $y),
                'maxY' => max($this->bounds['maxY'], $y),
                'minZ' => min($this->bounds['minZ'], $z),
                'maxZ' => max($this->bounds['maxZ'], $z),
            ];
            $this->map->setValue(new Location($x, $y, $z), self::LAVA);
        }

        foreach ($this->map->getMaps() as $z => $layer) {
            foreach ($layer->getMap() as $y => $row) {
                foreach ($row as $x => $value) {
                    foreach (Location::ALL_DIRECTIONS_3D as $direction) {
                        $neighbour = (new Location($x, $y, $z))->move($direction);
                        if (!$this->map->getValue($neighbour)) {
                            $this->part1++;
                            if (!$this->isPartOfABubble($neighbour)) {
                                $this->part2++;
                            }
                        }
                    }
                }
            }

        }
    }

    private function isPartOfABubble(Location $location, array $visited = []) : bool
    {
        if (in_array($location->toString(), $this->knownBubbles)) {
            return true;
        }

        $visited[] = $location->toString();
        foreach (Location::ALL_DIRECTIONS_3D as $direction) {
            $neighbour = (clone ($location))->move($direction);
            if ($neighbour->isOutOfBounds($this->bounds)) {
                return false;
            }

            if (!$this->map->getValue($neighbour) && !in_array($neighbour->toString(), $visited)
            && !$this->isPartOfABubble($neighbour, $visited)) {
               return false;
            }
        }

        $this->knownBubbles = array_unique(array_merge($this->knownBubbles, $visited));
        return true;
    }
}