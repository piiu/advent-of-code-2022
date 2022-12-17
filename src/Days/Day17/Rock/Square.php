<?php

namespace AdventOfCode\Days\Day17\Rock;

use AdventOfCode\Common\Coordinates\Location;

class Square extends Rock
{
    protected string $nextRockClass = HBar::class;

    public function __construct(Location $start)
    {
        $this->locations = [
            new Location($start->x, $start->y-1),
            new Location($start->x+1, $start->y-1),
            new Location($start->x, $start->y),
            new Location($start->x+1, $start->y),
        ];
    }
}