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

    const TURN_RIGHT = 'R';
    const TURN_LEFT = 'L';

    const ALL_DIRECTIONS = [self::RIGHT, self::DOWN, self::LEFT, self::UP];
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

    public static function turn(string $currentDirection, string $turnDirection) : string
    {
        $currentIndex = array_search($currentDirection, self::ALL_DIRECTIONS);
        if ($turnDirection === self::TURN_RIGHT) {
            return self::ALL_DIRECTIONS[($currentIndex + 1) % 4];
        } else {
            return self::ALL_DIRECTIONS[($currentIndex - 1 + 4) % 4];
        }
    }

    public static function getOppositeDirection(string $direction) : string
    {
        $index = array_search($direction, self::ALL_DIRECTIONS);
        return self::ALL_DIRECTIONS[($index + 2) % 4];
    }

    public static function getDirectionOffset(string $direction1, string $direction2) : int
    {
        return array_search($direction1, self::ALL_DIRECTIONS) - array_search($direction2, self::ALL_DIRECTIONS);
    }

    public function getWrappedLocationOnMap(Map $map, string $direction) : Location
    {
        return match ($direction) {
            Location::LEFT => $map->getLastNonEmptyOnY($this->y),
            Location::RIGHT => $map->getFirstNonEmptyOnY($this->y),
            Location::DOWN => $map->getFirstNonEmptyOnX($this->x),
            Location::UP => $map->getLastNonEmptyOnX($this->x),
        };
    }
}