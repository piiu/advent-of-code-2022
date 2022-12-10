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
        $this->history[] = $this->location->getString();
    }

    public function move($direction)
    {
        $newLocation = $this->location->move($direction);
        $this->moveTo($newLocation->x, $newLocation->y);
    }

    public function moveTo(int $x, int $y) {
        $this->location->set($x, $y);
        $this->history[] = $this->location->getString();

        if (!$this->tail) {
            return;
        }

        $tailXOffset = $this->location->x - $this->tail->location->x;
        if (abs($tailXOffset) > 1) {
            $tailX = $this->tail->location->x + ($tailXOffset > 0 ? floor($tailXOffset / 2) : ceil($tailXOffset / 2));
            $this->tail->moveTo($tailX, $y);
        }

        $tailYOffset = $this->location->y - $this->tail->location->y;
        if (abs($tailYOffset) > 1) {
            $tailY = $this->tail->location->y + ($tailYOffset > 0 ? floor($tailYOffset / 2) : ceil($tailYOffset / 2));
            $this->tail->moveTo($x, $tailY);
        }
    }

    public function getTailHistory() : array
    {
        return $this->tail ? $this->tail->getTailHistory() : $this->history;
    }
}