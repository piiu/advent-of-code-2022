<?php

namespace AdventOfCode\Days\Day09;

use AdventOfCode\Common\Coordinates\Location;

class RopeSegment
{
    private Location $location;
    private ?self $tail;
    public array $history = [];

    public function __construct(self $tail = null)
    {
        $this->tail = $tail;
        $this->location = new Location();
        $this->writeHistory();
    }

    public function move(array $directions)
    {
        foreach ($directions as $direction) {
            $this->location->move($direction);
        }
        $this->writeHistory();

        if (!$this->tail) {
            return;
        }

        $tailXOffset = $this->location->x - $this->tail->location->x;
        $tailYOffset = $this->location->y - $this->tail->location->y;
        if (abs($tailXOffset) > 1 || abs($tailYOffset) > 1) {
            $directions = [];
            if ($tailXOffset !== 0) {
                $directions[] = $tailXOffset > 0 ? Location::RIGHT : Location::LEFT;
            }
            if ($tailYOffset !== 0) {
                $directions[] = $tailYOffset > 0 ? Location::DOWN : Location::UP;
            }
            $this->tail->move($directions);
        }
    }

    public function writeHistory()
    {
        $this->history[] = $this->location->x . '|' . $this->location->y;
    }

    public function getTailHistory() : array
    {
        return $this->tail ? $this->tail->getTailHistory() : $this->history;
    }
}