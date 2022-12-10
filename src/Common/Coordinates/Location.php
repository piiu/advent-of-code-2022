<?php

namespace AdventOfCode\Common\Coordinates;

class Location
{
    public int $x;
    public int $y;

    const UP = 'U';
    const DOWN = 'D';
    const LEFT = 'L';
    const RIGHT = 'R';

    const ALL_DIRECTIONS = [self::UP, self::RIGHT, self::DOWN, self::LEFT];

    public function __construct(int $x = 0, int $y = 0)
    {
        $this->set($x, $y);
    }

    public function move(string $direction, int $amount = 1) : self
    {
        if ($direction === self::UP) {
            $this->y -= $amount;
        }
        if ($direction === self::DOWN) {
            $this->y += $amount;
        }
        if ($direction === self::LEFT) {
            $this->x -= $amount;
        }
        if ($direction === self::RIGHT) {
            $this->x += $amount;
        }
        return $this;
    }

    public function set(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function isEqual(self $location) : bool
    {
        return $this->x === $location->x && $this->y === $location->y;
    }

    public function getString() : string{
        return $this->x . '-' . $this->y;
    }
}