<?php

namespace AdventOfCode\Days\Day16;

use AdventOfCode\Common\BaseDay;
use SplQueue;

class Solution extends BaseDay
{
    /** @var Valve[] */
    private array $valves = [];
    private array $routeLengths = [];
    private array $history = [];

    public function execute()
    {
        $regex = '/Valve (?<id>-?\w*) has flow rate=(?<rate>-?\d*); tunnels? leads? to valves? (?<valves>.*)/';
        $usableValves = [];
        foreach ($this->getInputArray() as $row) {
            preg_match($regex, $row, $matches);
            $valve = new Valve($matches['id'], $matches['rate'], explode(', ', $matches['valves']));
            $this->valves[$valve->id] = $valve;
            if ($valve->flowRate > 0) {
                $usableValves[] = $valve->id;
            }
        }

        //$this->part1 = $this->getBestFlowRate($this->valves['AA'], $usableValves);

        $openers = [
            new Opener('Human', $this->valves['AA']),
            new Opener('Elephant', $this->valves['AA'])
        ];
        $this->part2 = $this->getBestFlowRateWithElephant($openers, $usableValves);
    }

    private function getBestFlowRate(Valve $currentValve, array $closedValves, $minute = 1, $releasedPressure = 0) : int
    {
        if ($minute > 1) {
            $releasedPressure += $this->flow($closedValves, 1);
            unset($closedValves[array_search($currentValve->id, $closedValves)]);
            $minute++;
        }

        $bestMove = 0;
        foreach ($closedValves as $nextValve) {
            $routeDistance = $this->findShortestSteps($currentValve->id, $nextValve);
            if ($minute + $routeDistance >= 30) {
                continue;
            }
            $pressureReleasedOnWalk = $this->flow($closedValves, $routeDistance);
            $move = $this->getBestFlowRate($this->valves[$nextValve], $closedValves, $minute + $routeDistance, $releasedPressure + $pressureReleasedOnWalk);
            $bestMove = max($move, $bestMove);

        }
        if ($bestMove) {
            return $bestMove;
        }

        $releasedPressure += $this->flow($closedValves, 30 - $minute + 1);
        return $releasedPressure;
    }

    private function getBestFlowRateWithElephant(array $openers, array $closedValves, $minute = 1, $releasedPressure = 0, $history = []) : int
    {
        $history[] = "Minute $minute";
        $flowing = $this->flow($closedValves);
        $history[] = "Releasing $flowing pressure";

        $releasedPressure += $flowing;
        $minutesLeft = 26 - $minute;

        /** @var Opener[] $openers */
        foreach ($openers as $opener) {
            if ($opener->pathAhead > 0) {
                $opener->pathAhead--;
                if ($opener->pathAhead > 0) {
                    $history[] = "$opener->id moves towards " . $opener->currentValve->id . ", $opener->pathAhead steps to go";
                } else {
                    $history[] = "$opener->id moves to " . $opener->currentValve->id;
                }
            } elseif ($minute > 1) {
                $history[] = "$opener->id opens " . $opener->currentValve->id;
                unset($closedValves[array_search($opener->currentValve->id, $closedValves)]);
                $opener->needsNewPath = true;
            }
        }

        if (empty($closedValves) || !$minutesLeft) {
            $history[] = "Waiting for $minutesLeft minutes";
            $releasedPressure += $this->flow($closedValves, $minutesLeft);

            if (empty($this->history) || $this->history['released'] < $releasedPressure) {
                $this->history = [
                    'released' => $releasedPressure,
                    'history' => $history
                ];
            }

            return $releasedPressure;
        }

        if ($openers[0]->needsNewPath || $openers[1]->needsNewPath) {
            $bestMove = 0;
            foreach ($closedValves as $nextValve) {
                foreach ($openers as $opener) {
                    if ($opener->currentValve->id === $nextValve) {
                        continue 2;
                    }
                }

                $combinations = [];
                if ($openers[0]->needsNewPath && $openers[1]->needsNewPath) {
                    foreach ([[0, 1], [1, 0]] as $sequence) {
                        $opener1 = $this->getNewOpenerToGoToValve($openers[$sequence[0]], $nextValve, $minutesLeft);
                        if (!$opener1) {
                            continue;
                        }
                        foreach ($closedValves as $otherNextValve) {
                            $opener2 = $this->getNewOpenerToGoToValve($openers[$sequence[1]], $otherNextValve, $minutesLeft);
                            if ($opener2 && $otherNextValve !== $opener1->currentValve->id) {
                                $combinations[] = [$opener1, $opener2];
                            }
                        }
                    }
                } else {
                    $opener1 = $openers[0]->needsNewPath ? ($this->getNewOpenerToGoToValve($openers[0], $nextValve, $minutesLeft)) : $openers[0];
                    $opener2 = $openers[1]->needsNewPath ? ($this->getNewOpenerToGoToValve($openers[1], $nextValve, $minutesLeft)) : $openers[1];
                    if ($opener1 && $opener2) {
                        $combinations[] = [$opener1, $opener2];
                    }

                }
                foreach ($combinations as $combination) {
                    $move = $this->getBestFlowRateWithElephant($combination, $closedValves,$minute + 1, $releasedPressure, $history);
                    $bestMove = max($move, $bestMove);
                }

            }
            if ($bestMove) {
                return $bestMove;
            }
        }

        return $this->getBestFlowRateWithElephant($openers, $closedValves, $minute + 1, $releasedPressure, $history);

    }

    private function flow(array $closedValves, int $minutes = 1, ) : int
    {
        $openValves = array_map(function(Valve $valve) use ($closedValves) {
            return !in_array($valve->id, $closedValves) ? $valve->flowRate : 0;
        }, $this->valves);
        return $minutes * array_sum($openValves);
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
                foreach ($this->valves[$currentValve]->leadsTo as $connectedValve) {
                    if (!in_array($connectedValve, $visited)) {
                        $queue->enqueue($connectedValve);
                        $visited[] = $connectedValve;
                    }
                }
            }
            $steps++;
        }
    }

    private function getNewOpenerToGoToValve(Opener $currentOpener, string $valveIndex, $minutesLeft) : ?Opener
    {
        $valve = $this->valves[$valveIndex];
        $opener = clone $currentOpener;
        $steps = $this->findShortestSteps($opener->currentValve->id, $valve->id);
        if ($steps >= $minutesLeft) {
            return null;
        }
        $opener->needsNewPath = false;
        $opener->currentValve = $valve;
        $opener->pathAhead = $steps;
        if ($minutesLeft === 25) {
            $opener->pathAhead--;
        }
        return $opener;
    }

    private function getRouteId(array $parts) : string
    {
        sort($parts);
        return implode('->', $parts);
    }
}