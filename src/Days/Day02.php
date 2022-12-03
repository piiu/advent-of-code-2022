<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\RockPaperScissors\Move;
use AdventOfCode\Common\RockPaperScissors\Paper;
use AdventOfCode\Common\RockPaperScissors\Rock;
use AdventOfCode\Common\RockPaperScissors\Scissors;

class Day02 extends BaseDay
{
    public function execute()
    {
        foreach ($this->getInputArray() as $round) {
            [$opponentMoveCode, $myMoveCode] =  explode(' ', $round);
            $opponentMove = $this->getOpponentMove($opponentMoveCode);
            $this->part1 += $this->getMyMovePart1($myMoveCode)->getScoreAgainst($opponentMove);
            $this->part2 += $this->getMyMovePart2($myMoveCode, $opponentMove)->getScoreAgainst($opponentMove);
        }
    }

    private function getOpponentMove(string $opponentMoveCode) : Move
    {
        return match ($opponentMoveCode) {
            "A" => new Rock(),
            "B" => new Paper(),
            "C" => new Scissors()
        };
    }

    private function getMyMovePart1(string $myMoveCode) : Move
    {
        return match ($myMoveCode) {
            "X" => new Rock(),
            "Y" => new Paper(),
            "Z" => new Scissors()
        };
    }

    private function getMyMovePart2(string $myMoveCode, Move $opponentMove) : Move
    {
        return match ($myMoveCode) {
            "X" => new $opponentMove->winsAgainst,
            "Y" => new $opponentMove,
            "Z" => new $opponentMove->losesAgainst
        };
    }
}