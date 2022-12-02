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
        switch (get_class($move)) {
            case $this->winsAgainst:
                return $this->score + self::WIN;
            case $this->losesAgainst:
                return $this->score + self::LOSE;
            default:
                return $this->score + self::DRAW;
        }
    }
}