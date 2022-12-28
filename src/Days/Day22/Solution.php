<?php

namespace AdventOfCode\Days\Day22;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;

class Solution extends BaseDay
{
    const WALL = '#';
    const CUBEWIDTH = 50;
    const CUBERANGE = [
        'A' => [self::CUBEWIDTH, 0],
        'B' => [self::CUBEWIDTH * 2, 0],
        'C' => [self::CUBEWIDTH, self::CUBEWIDTH],
        'D' => [0, self::CUBEWIDTH * 2],
        'E' => [self::CUBEWIDTH, self::CUBEWIDTH * 2],
        'F' => [0, self::CUBEWIDTH * 3],
    ];
    const CUBEMAP = [
        'A'.Location::UP => 'F'.Location::LEFT,
        'A'.Location::LEFT => 'D'.Location::LEFT,
        'A'.Location::DOWN => 'C'.Location::UP,
        'A'.Location::RIGHT => 'B'.Location::LEFT,
        'B'.Location::UP => 'F'.Location::DOWN,
        'B'.Location::DOWN => 'C'.Location::RIGHT,
        'B'.Location::RIGHT => 'E'.Location::RIGHT,
        'C'.Location::LEFT => 'D'.Location::UP,
        'C'.Location::DOWN => 'E'.Location::UP,
        'D'.Location::DOWN => 'F'.Location::UP,
        'D'.Location::RIGHT => 'E'.Location::LEFT,
        'E'.Location::DOWN => 'F'.Location::RIGHT,
    ];

    private Map $map;
    private array $cube;

    public function execute()
    {
        [$rawMap, $steps] = $this->getInputArray("\n\r\n", false);
        $this->generateMaps($rawMap);

        $direction = Location::RIGHT;
        $location = $this->map->getFirstNonEmptyOnY(0);
        $cubeLocation = [
            'side' => 'A',
            'location' => new Location(),
            'direction' => $direction
        ];

        $distance = '';
        foreach (str_split($steps) as $step) {
            if (is_numeric($step)) {
                $distance .= $step;
                continue;
            }
            $location = $this->moveWithWrap($location, $direction, (int)$distance);
            $cubeLocation = $this->moveOnCube($cubeLocation, (int)$distance);

            $distance = '';
            $direction = Location::turn($direction, $step);
            $cubeLocation['direction'] = Location::turn($cubeLocation['direction'], $step);
        }
        $location = $this->moveWithWrap($location, $direction, $distance);
        $cubeLocation = $this->moveOnCube($cubeLocation, $distance);

        $this->part1 = $this->getPassword($location, $direction);
        $org = $this->getOriginalLocation($cubeLocation);
        $this->part2 = $this->getPassword($org, $cubeLocation['direction']);
    }

    private function generateMaps(string $rawMap)
    {
        $rawMapArray = array_map('str_split', array_map('rtrim', explode("\n", $rawMap)));
        $this->map = new Map($rawMapArray);

        foreach (self::CUBERANGE as $id => $range) {
            $side = array_map(function(array $row) use ($range) {
                return array_slice($row, $range[0], self::CUBEWIDTH);
            }, array_slice($rawMapArray, $range[1], self::CUBEWIDTH));
            $this->cube[$id] = new Map($side);
        }
    }

    private function getPassword(Location $location, string $direction) : int
    {
        return (1000 * ($location->y + 1))
        + (4 * ($location->x + 1))
        + array_search($direction, Location::ALL_DIRECTIONS);
    }

    private function getOriginalLocation(array $cubeLocation) : Location
    {
        $offsets = self::CUBERANGE[$cubeLocation['side']];
        return new Location($cubeLocation['location']->x + $offsets[0], $cubeLocation['location']->y + $offsets[1]);
    }

    private function moveWithWrap(Location $location, string $direction, int $distance) : Location
    {
        for ($i = 0; $i < $distance; $i++) {
            $newLocation = (clone $location)->move($direction);
            if (empty(trim($this->map->getValue($newLocation)))) {
                $newLocation = $newLocation->getWrappedLocationOnMap($this->map, $direction);
            }
            if ($this->map->getValue($newLocation) === self::WALL) {
                return $location;
            }
            $location = $newLocation;
        }
        return $location;
    }

    private function moveOnCube(array $cubeLocation, int $distance) : array
    {
        for ($i = 0; $i < $distance; $i++) {
            $newCubeLocation = [
                'location' => (clone $cubeLocation['location'])->move($cubeLocation['direction']),
                'side' => $cubeLocation['side'],
                'direction' => $cubeLocation['direction']
            ];
            $map = $this->cube[$cubeLocation['side']];

            if (empty(trim($map->getValue($newCubeLocation['location'])))) {
                $newCubeLocation = $this->goOverEdge($newCubeLocation);
                $map = $this->cube[$newCubeLocation['side']];
            }

            if ($map->getValue($newCubeLocation['location']) === self::WALL) {
                return $cubeLocation;
            }
            $cubeLocation = $newCubeLocation;
        }
        return $cubeLocation;
    }

    private function goOverEdge(array $newCubeLocation) : array
    {
        $sideString = $newCubeLocation['side'] . $newCubeLocation['direction'];
        [$newSide, $sideDirection] = str_split(self::CUBEMAP[$sideString] ?? array_flip(self::CUBEMAP)[$sideString]);
        $sideDirection = Location::getOppositeDirection($sideDirection);

        $map = $this->cube[$newSide];

        $adjustedLocation = $newCubeLocation['location'];
        if ($sideDirection === Location::getOppositeDirection($newCubeLocation['direction'])) {
            $adjustedLocation = new Location(self::CUBEWIDTH - 1 - $adjustedLocation->x, self::CUBEWIDTH - 1 - $adjustedLocation->y);
        } elseif (Location::getDirectionOffset($sideDirection, $newCubeLocation['direction']) > 0) {
            $adjustedLocation = new Location(self::CUBEWIDTH - 1 - $adjustedLocation->y, self::CUBEWIDTH - 1 - $adjustedLocation->x);
        } elseif (Location::getDirectionOffset($sideDirection, $newCubeLocation['direction']) < 0) {
            $adjustedLocation = new Location($adjustedLocation->y, $adjustedLocation->x);
        }
        $adjustedLocation = $adjustedLocation->getWrappedLocationOnMap($map, $sideDirection);

        return [
            'side' => $newSide,
            'location' => $adjustedLocation,
            'direction' => $sideDirection
        ];
    }
}