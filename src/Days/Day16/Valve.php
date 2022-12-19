<?php

namespace AdventOfCode\Days\Day16;

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