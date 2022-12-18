<?php

namespace AdventOfCode\Days\Day17\Rock;

use AdventOfCode\Common\Coordinates\Location;

class Angle extends Rock
{
    protected string $nextRockClass = VBar::class;

    public function __construct(Location $start)
    {
        $this->locations = [
            new Location($start->x+2, $start->y-2),
            new Location($start->x+2, $start->y-1),
            new Location($start->x, $start->y),
            new Location($start->x+1, $start->y),
            new Location($start->x+2, $start->y)
        ];
    }
}