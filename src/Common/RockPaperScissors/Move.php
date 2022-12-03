<?php

namespace AdventOfCode\Common\RockPaperScissors;

abstract class Move {
    const WIN = 6;
    const DRAW = 3;
    const LOSE = 0;

    protected int $score;
    public string $winsAgainst;
    public string $losesAgainst;

    function getScoreAgainst(Move $move) : int
    {
        return match (get_class($move)) {
            $this->winsAgainst => $this->score + self::WIN,
            $this->losesAgainst => $this->score + self::LOSE,
            default => $this->score + self::DRAW,
        };
    }
}