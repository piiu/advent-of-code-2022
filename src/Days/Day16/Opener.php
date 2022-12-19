<?php

namespace AdventOfCode\Days\Day16;

class Opener
{
    public string $id;
    public Valve $currentValve;
    public int $pathAhead = 0;
    public bool $needsNewPath = true;

    public function __construct(string $id, Valve $currentValve)
    {
        $this->id = $id;
        $this->currentValve = $currentValve;
    }
}