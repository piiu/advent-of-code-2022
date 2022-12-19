<?php

namespace AdventOfCode\Days\Day16;

use AdventOfCode\Common\BaseDay;
use SplQueue;

class Solution extends BaseDay
{
    /** @var Valve[] */
    private array $valves = [];
    /** @var Valve[] */
    private array $valvesWithFlowRate = [];

    private array $routeLengths = [];

    public function execute()
    {
        $regex = '/Valve (?<id>-?\w*) has flow rate=(?<rate>-?\d*); tunnels? leads? to valves? (?<valves>.*)/';
        foreach ($this->getInputArray() as $row) {
            preg_match($regex, $row, $matches);
            $valve = new Valve($matches['id'], $matches['rate'], explode(', ', $matches['valves']));
            $this->valves[$valve->id] = $valve;
            if ($valve->flowRate > 0) {
                $this->valvesWithFlowRate[] = $valve->id;
            }
        }

        $this->part1 = $this->getBestFlowRate($this->valves['AA'], $this->valvesWithFlowRate);
    }

    private function getBestFlowRate(Valve $currentValve, array $closedValves, $minute = 1, $releasedPressure = 0) : int
    {
        if (in_array($currentValve->id, $closedValves) && $currentValve->flowRate > 0) {
            $releasedPressure += $this->flow($closedValves, 1);
            unset($closedValves[array_search($currentValve->id, $closedValves)]);
            $minute++;
        }

        $bestMove = 0;
        foreach ($closedValves as $nextValve) {
            $routeDistance = $this->findShortestSteps($currentValve->id, $nextValve);
            if ($minute + $routeDistance < 30) {
                $pressureReleasedOnWalk = $this->flow($closedValves, $routeDistance);
                $move = $this->getBestFlowRate($this->valves[$nextValve], $closedValves, $minute + $routeDistance, $releasedPressure + $pressureReleasedOnWalk);
                if ($move && (!$bestMove || $bestMove < $move)) {
                    $bestMove = $move;
                }
            }
        }
        if ($bestMove > 0) {
            return $bestMove;
        }

        $releasedPressure += $this->flow($closedValves, 30 - $minute + 1);
        return $releasedPressure;
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
                $currentNode = $queue->dequeue();

                if ($currentNode == $valveB) {
                    $this->routeLengths[$routeId] = $steps;
                    return $steps;
                }

                foreach ($this->valves[$currentNode]->leadsTo as $connectedNode) {
                    if (!in_array($connectedNode, $visited)) {
                        $queue->enqueue($connectedNode);
                        $visited[] = $connectedNode;
                    }
                }
            }
            $steps++;
        }
    }

    private function getRouteId(array $parts) : string
    {
        sort($parts);
        return implode('->', $parts);
    }
}

class Valve
{
    public string $id;
    public int $flowRate;
    public array $leadsTo = [];

    public function __construct(string $id, int $flowRate, array $leadsTo)
    {
        $this->id = $id;
        $this->flowRate = $flowRate;
        $this->leadsTo = $leadsTo;
    }
}