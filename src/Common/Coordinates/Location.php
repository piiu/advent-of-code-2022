<?php

namespace AdventOfCode\Common\Coordinates;

class Location
{
    public int $x;
    public int $y;
    public int $z;

    const UP = 'U';
    const DOWN = 'D';
    const LEFT = 'L';
    const RIGHT = 'R';
    const IN = 'I';
    const OUT = 'O';

    const ALL_DIRECTIONS = [self::DOWN, self::LEFT, self::UP, self::RIGHT];
    const ALL_DIRECTIONS_3D = [self::DOWN, self::LEFT, self::UP, self::RIGHT, self::IN, self::OUT];

    const MAX_BOUNDS = [
        'minX' => PHP_INT_MAX,
        'maxX' => -PHP_INT_MAX,
        'minY' => PHP_INT_MAX,
        'maxY' => -PHP_INT_MAX,
        'minZ' => PHP_INT_MAX,
        'maxZ' => -PHP_INT_MAX
    ];

    public function __construct(int $x = 0, int $y = 0, int $z = 0)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
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
        if ($direction === self::IN) {
            $this->z -= $amount;
        }
        if ($direction === self::OUT) {
            $this->z += $amount;
        }
        return $this;
    }

    public function moveMultiple(array $directions) : self
    {
        foreach ($directions as $direction) {
            $this->move($direction);
        }
        return $this;
    }

    public function isEqual(self $location) : bool
    {
        return $this->x === $location->x && $this->y === $location->y && $this->z === $location->z;
    }

    public function toString() : string
    {
        return join('|', [$this->x, $this->y, $this->z]);
    }

    public function getManhattanDistanceFrom(self $location) : int
    {
        return abs($location->x - $this->x) + abs($location->y - $this->y);
    }

    public function isOutOfBounds(array $bounds) : bool
    {
        return (isset($bounds['maxX']) && $this->x > $bounds['maxX'])
            || (isset($bounds['maxY']) && $this->y > $bounds['maxY'])
            || (isset($bounds['maxZ']) && $this->z > $bounds['maxZ'])
            || (isset($bounds['minX']) && $this->x < $bounds['minX'])
            || (isset($bounds['minY']) && $this->y < $bounds['minY'])
            || (isset($bounds['minZ']) && $this->z < $bounds['minZ']);
    }
}