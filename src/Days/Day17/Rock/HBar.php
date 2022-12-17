<?php

namespace AdventOfCode\Days\Day17\Rock;

use AdventOfCode\Common\Coordinates\Location;

class HBar extends Rock
{
    protected string $nextRockClass = Cross::class;

    public function __construct(Location $start)
    {
        $this->locations = [
            new Location($start->x, $start->y),
            new Location($start->x+1, $start->y),
            new Location($start->x+2, $start->y),
            new Location($start->x+3, $start->y),
        ];
    }
}