<?php

namespace AdventOfCode\Days\Day09;

use AdventOfCode\Common\Coordinates\Location;

class Knot
{
    private Location $location;
    private ?self $nextKnot;
    public array $history = [];

    public function __construct(self $nextKnot = null)
    {
        $this->nextKnot = $nextKnot;
        $this->location = new Location();
        $this->history[] = $this->location->toString();
    }

    public function move(array $directions)
    {
        foreach ($directions as $direction) {
            $this->location->move($direction);
        }
        $this->history[] = $this->location->toString();

        if (!$this->nextKnot) {
            return;
        }

        $xOffset = $this->location->x - $this->nextKnot->location->x;
        $yOffset = $this->location->y - $this->nextKnot->location->y;
        if (abs($xOffset) > 1 || abs($yOffset) > 1) {
            $directions = [];
            if ($xOffset !== 0) {
                $directions[] = $xOffset > 0 ? Location::RIGHT : Location::LEFT;
            }
            if ($yOffset !== 0) {
                $directions[] = $yOffset > 0 ? Location::DOWN : Location::UP;
            }
            $this->nextKnot->move($directions);
        }
    }

    public function getLastKnotHistory() : array
    {
        return $this->nextKnot ? $this->nextKnot->getLastKnotHistory() : $this->history;
    }

    public function getNextKnot(): ?Knot
    {
        return $this->nextKnot;
    }
}