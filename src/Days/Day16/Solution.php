<?php

namespace AdventOfCode\Days\Day16;

use AdventOfCode\Common\BaseDay;
use SplQueue;

class Solution extends BaseDay
{
    private array $valves = [];
    private array $routeLengths = [];

    public function execute()
    {
        $regex = '/Valve (?<id>-?\w*) has flow rate=(?<rate>-?\d*); tunnels? leads? to valves? (?<valves>.*)/';
        $usableValves = [];
        foreach ($this->getInputArray() as $row) {
            preg_match($regex, $row, $matches);
            $this->valves[$matches['id']] = [
                'flowRate' => $matches['rate'],
                'leadsTo' => explode(', ', $matches['valves'])
            ];
            if ($matches['rate'] > 0) {
                $usableValves[] = $matches['id'];
            }
        }

        $this->part1 = $this->getBestFlowRate('AA', $usableValves);

        $persons = [
            [
                'currentValve' => 'AA',
                'pathAhead' => 0,
                'needsNewPath' => true
            ],[
                'currentValve' => 'AA',
                'pathAhead' => 0,
                'needsNewPath' => true
            ]
        ];
        $this->part2 = $this->getBestFlowRateWithElephant($persons, $usableValves);
    }

    private function getBestFlowRate(string $currentValve, array $closedValves, $minute = 1, $releasedPressure = 0) : int
    {
        if ($minute > 1) {
            $releasedPressure += $this->flow($closedValves);
            unset($closedValves[array_search($currentValve, $closedValves)]);
            $minute++;
        }

        $bestMove = 0;
        foreach ($closedValves as $nextValve) {
            $routeDistance = $this->findShortestSteps($currentValve, $nextValve);
            if ($minute + $routeDistance >= 30) {
                continue;
            }
            $pressureReleasedOnWalk = $this->flow($closedValves, $routeDistance);
            $move = $this->getBestFlowRate($nextValve, $closedValves, $minute + $routeDistance, $releasedPressure + $pressureReleasedOnWalk);
            $bestMove = max($move, $bestMove);

        }
        if ($bestMove) {
            return $bestMove;
        }

        $releasedPressure += $this->flow($closedValves, 30 - $minute + 1);
        return $releasedPressure;
    }

    private function getBestFlowRateWithElephant(array $persons, array $closedValves, $minute = 1, $releasedPressure = 0) : int
    {
        $flowing = $this->flow($closedValves);
        $releasedPressure += $flowing;
        $minutesLeft = 26 - $minute;

        foreach ($persons as $id => $person) {
            if ($person['pathAhead'] > 0) {
                $persons[$id]['pathAhead'] = $person['pathAhead'] - 1;
            } elseif (in_array($person['currentValve'], $closedValves)) {
                unset($closedValves[array_search($person['currentValve'], $closedValves)]);
                $persons[$id]['needsNewPath'] = true;
            }
        }

        if (empty($closedValves) || !$minutesLeft) {
            $releasedPressure += $this->flow($closedValves, $minutesLeft);
            return $releasedPressure;
        }

        if ($persons[0]['needsNewPath'] || $persons[1]['needsNewPath']) {
            $bestMove = 0;
            foreach ($closedValves as $nextValve) {
                foreach ($persons as $person) {
                    if ($person['currentValve'] === $nextValve) {
                        continue 2;
                    }
                }

                $combinations = [];
                if ($persons[0]['needsNewPath'] && $persons[1]['needsNewPath']) {
                    foreach ([[0, 1], [1, 0]] as $sequence) {
                        $person1 = $this->getPersonToGoToValve($persons[$sequence[0]], $nextValve, $minutesLeft);
                        if (!$person1) {
                            continue;
                        }
                        foreach ($closedValves as $otherNextValve) {
                            $person2 = $this->getPersonToGoToValve($persons[$sequence[1]], $otherNextValve, $minutesLeft);
                            if ($person2 && $otherNextValve !== $person1['currentValve']) {
                                $combinations[] = [$person1, $person2];
                            }
                        }
                    }
                } else {
                    $person1 = $persons[0]['needsNewPath'] ? ($this->getPersonToGoToValve($persons[0], $nextValve, $minutesLeft)) : $persons[0];
                    $person2 = $persons[1]['needsNewPath'] ? ($this->getPersonToGoToValve($persons[1], $nextValve, $minutesLeft)) : $persons[1];
                    if ($person1 && $person2) {
                        $combinations[] = [$person1, $person2];
                    }

                }
                foreach ($combinations as $combination) {
                    $move = $this->getBestFlowRateWithElephant($combination, $closedValves,$minute + 1, $releasedPressure);
                    $bestMove = max($move, $bestMove);
                }

            }
            if ($bestMove) {
                return $bestMove;
            }
        }

        return $this->getBestFlowRateWithElephant($persons, $closedValves, $minute + 1, $releasedPressure);

    }

    private function flow(array $closedValves, int $minutes = 1, ) : int
    {
        $sum = 0;
        foreach (array_keys($this->valves) as $valve) {
            if (!in_array($valve, $closedValves)) {
                $sum += $this->valves[$valve]['flowRate'];
            }
        }
        return $minutes * $sum;
    }

    private function findShortestSteps(string $valveA, string $valveB) : int
    {
        $routeId = $this->getRouteId([$valveA, $valveB]);
        if (isset($this->routeLengths[$routeId])) {
            return $this->routeLengths[$routeId];
        }

        $queue = new SplQueue();
        $queue->enqueue($valveA);
        $visited = [$valveA];
        $steps = 0;
        while (!$queue->isEmpty()) {
            $size = $queue->count();
            for ($i = 0; $i < $size; $i++) {
                $currentValve = $queue->dequeue();
                if ($currentValve == $valveB) {
                    $this->routeLengths[$routeId] = $steps;
                    return $steps;
                }
                foreach ($this->valves[$currentValve]['leadsTo'] as $connectedValve) {
                    if (!in_array($connectedValve, $visited)) {
                        $queue->enqueue($connectedValve);
                        $visited[] = $connectedValve;
                    }
                }
            }
            $steps++;
        }
    }

    private function getPersonToGoToValve(array $currentperson, string $nextValve, $minutesLeft) : ?array
    {
        $steps = $this->findShortestSteps($currentperson['currentValve'], $nextValve);
        if ($steps >= $minutesLeft) {
            return null;
        }
        return [
            'currentValve' => $nextValve,
            'pathAhead' => $minutesLeft === 25 ? ($steps - 1) : $steps,
            'needsNewPath' => false
        ];
    }

    private function getRouteId(array $parts) : string
    {
        sort($parts);
        return implode('->', $parts);
    }
}