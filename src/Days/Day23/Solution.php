<?php

namespace AdventOfCode\Days\Day23;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\Console\Utils;
use AdventOfCode\Common\Coordinates\Location;
use AdventOfCode\Common\Coordinates\Map;
use AdventOfCode\Common\WolframAlpha\EngineFactory;
use AdventOfCode\Common\WolframAlpha\Solver;

class Solution extends BaseDay
{
    const ELF = '#';

    /** @var Location[] */
    private array $elves = [];
    private Map $map;
    private array $consider = [
        [
            'direction' => Location::UP,
            'conditions' => [
                [Location::UP],
                [Location::UP, Location::RIGHT],
                [Location::UP, Location::LEFT]
            ]
        ],
        [
            'direction' => Location::DOWN,
            'conditions' => [
                [Location::DOWN],
                [Location::DOWN, Location::RIGHT],
                [Location::DOWN, Location::LEFT]
            ]
        ],
        [
            'direction' => Location::LEFT,
            'conditions' => [
                [Location::LEFT],
                [Location::UP, Location::LEFT],
                [Location::DOWN, Location::LEFT]
            ]
        ],
        [
            'direction' => Location::RIGHT,
            'conditions' => [
                [Location::RIGHT],
                [Location::UP, Location::RIGHT],
                [Location::DOWN, Location::RIGHT]
            ]
        ]
    ];


    public function execute()
    {
        $this->findInitialElves();

        $round = 1;
        while (true) {
            $moves = [];
            foreach ($this->elves as $elf) {
                $moves[] = $this->getMove($elf);
            }

            $counts = array_count_values(array_map(fn (?Location $move): string => $move?->toString() ?? 'null', $moves));

            if (count($counts) === 1) {
                $this->part2 = $round;
                break;
            }

            foreach ($moves as $index => $move) {
                if ($move && $counts[$move->toString()] === 1) {
                    $this->elves[$index] = $move;
                }
            }
            $this->endRound($round);

            if ($round === 10) {
                $this->part1 = $this->getRectangleSize();
            }

            $round++;
        }
    }

    private function endRound(int $roundNumber)
    {
        $this->map->empty();
        foreach ($this->elves as $elf) {
            $this->map->setValue($elf, self::ELF);
        }
        $firstConsideration = array_shift($this->consider);
        $this->consider[] = $firstConsideration;

//        Utils::output("Round $roundNumber");
//        $this->map->draw('.');
    }

    private function getMove(Location $location) : ?Location
    {
        $canMoveTo = [];
        foreach ($this->consider as $toConsider) {
            foreach ($toConsider['conditions'] as $condition) {
                if ($this->map->getValue((clone $location)->moveMultiple($condition)) === self::ELF) {
                    continue 2;
                }
            }
            $canMoveTo[] = $toConsider['direction'];
        }
        if (!empty($canMoveTo) && count($canMoveTo) < 4) {
            return (clone $location)->move($canMoveTo[0]);
        }
        return null;
    }

    private function findInitialElves()
    {
        $this->map = $this->getInputMap();
        foreach ($this->map->getMap() as $y => $row) {
            foreach ($row as $x => $value) {
                if ($value === self::ELF) {
                    $this->elves[] = new Location($x, $y);
                }
            }
        }
    }

    private function getRectangleSize() : int
    {
        [$minX, $maxX] = $this->map->getXRange();
        [$minY, $maxY] = $this->map->getYRange();
        return ($maxX - $minX + 1) * ($maxY - $minY + 1) - count($this->elves);
    }
}