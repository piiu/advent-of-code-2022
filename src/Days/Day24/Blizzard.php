<?php

namespace AdventOfCode\Days\Day24;

use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Blizzard
{
    const DIRECTION_MAP = [
        '^' => Location::UP,
        '>' => Location::RIGHT,
        '<' => Location::LEFT,
        'v' => Location::DOWN
    ];

    public Location $location;
    private string $direction;

    public function __construct(Location $location, string $rawDirection)
    {
        $this->location = $location;
        $this->direction = self::DIRECTION_MAP[$rawDirection];
    }

    public function move(Map $map)
    {
        $this->location->move($this->direction);
        if ($map->getValue($this->location) === Solution::WALL) {
            $this->location = $this->location->getWrappedLocationOnMap($map, $this->direction)->move($this->direction);
        }
    }
}