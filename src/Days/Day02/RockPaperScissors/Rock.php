<?php

namespace AdventOfCode\Days\Day02\RockPaperScissors;

class Rock extends Move
{
    protected int $score = 1;
    public string $winsAgainst = Scissors::class;
    public string $losesAgainst = Paper::class;
}