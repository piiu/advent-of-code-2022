<?php

namespace AdventOfCode\Days\Day17\Rock;

use AdventOfCode\Common\Coordinates\Location;

class VBar extends Rock
{
    protected string $nextRockClass = Square::class;

    public function __construct(Location $start)
    {
        $this->locations = [
            new Location($start->x, $start->y-3),
            new Location($start->x, $start->y-2),
            new Location($start->x, $start->y-1),
            new Location($start->x, $start->y),
        ];
    }
}