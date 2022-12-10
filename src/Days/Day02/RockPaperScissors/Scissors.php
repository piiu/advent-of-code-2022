<?php

namespace AdventOfCode\Days\Day02\RockPaperScissors;

class Scissors extends Move
{
    protected int $score = 3;
    public string $winsAgainst = Paper::class;
    public string $losesAgainst = Rock::class;
}