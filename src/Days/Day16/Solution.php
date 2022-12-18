<?php

namespace AdventOfCode\Days\Day16;

use AdventOfCode\Common\BaseDay;

class Solution extends BaseDay
{
    /** @var Valve[] */
    private array $valves = [];
    /** @var Valve[] */
    private array $valvesWithFlowRate = [];

    private array $routeLengths = [];

    private array $histories = [];

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
        ksort($this->histories);
    }

    private function getBestFlowRate(Valve $currentValve, array $closedValves, $minute = 1, $releasedPressure = 0, $history = []) : int
    {
        if (in_array($currentValve->id, $closedValves) && $currentValve->flowRate > 0) {
            $releasedPressure += $this->flow($closedValves, 1, $history);
            unset($closedValves[array_search($currentValve->id, $closedValves)]);
            $history[] = "Minute $minute: Open valve $currentValve->id";
            $minute++;
        }

        $bestMove = 0;
        foreach ($closedValves as $nextValve) {
            $routeDistance = $this->getShortestRoute($currentValve, $nextValve);
            if ($minute + $routeDistance < 30) {
                $history[] = "Minute $minute: Walk  valve $nextValve for $routeDistance minutes";
                $pressureReleasedOnWalk = $this->flow($closedValves, $routeDistance, $history);
                $move = $this->getBestFlowRate($this->valves[$nextValve], $closedValves, $minute + $routeDistance, $releasedPressure + $pressureReleasedOnWalk, $history);
                if ($move && (!$bestMove || $bestMove < $move)) {
                    $bestMove = $move;
                }
            }
        }
        if ($bestMove > 0) {
            return $bestMove;
        }
        $history[] = "Minute $minute: Wait until end";

        $releasedPressure += $this->flow($closedValves, 30 - $minute);

        $this->histories[$releasedPressure] = $history;
        return $releasedPressure;
    }

    private function flow(array $closedValves, int $minutes = 1, array &$history = []) : int
    {
        $openValves = array_map(function(Valve $valve) use ($closedValves) {
            return !in_array($valve->id, $closedValves) ? $valve->flowRate : 0;
        }, $this->valves);
        $perMinute = array_sum($openValves);
        $history[] = "Flowing $perMinute per minute for $minutes minutes";
        return $minutes * array_sum($openValves);
    }

    private function getShortestRoute(Valve $currentValve, string $destinationId, array $path = []) : ?int
    {
        $pathId = ($path[0] ?? $currentValve->id).'->'.$destinationId;
        if (isset($this->routeLengths[$pathId])) {
            return $this->routeLengths[$pathId];
        }

        $path[] = $currentValve->id;
        if (in_array($destinationId, $currentValve->leadsTo)) {
            $length = count($path);
            $this->routeLengths[$pathId] = $length;
            return $length;
        }

        $bestPath = null;
        foreach ($currentValve->leadsTo as $to) {
            if (in_array($to, $path)) {
                continue;
            }
            $potentialPath = $this->getShortestRoute($this->valves[$to], $destinationId, $path);
            if (!$potentialPath) {
                continue;
            }
            if (!$bestPath || $potentialPath < $bestPath) {
                $bestPath = $potentialPath;
            }
        }

        return $bestPath;
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