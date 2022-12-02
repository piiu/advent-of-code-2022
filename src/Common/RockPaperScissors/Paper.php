<?php

namespace AdventOfCode\Common\RockPaperScissors;

class Paper extends Move
{
    protected int $score = 2;
    public string $winsAgainst = Rock::class;
    public string $losesAgainst = Scissors::class;
}