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
        $rounds = $this->getInputArray();

        $totalScore1 = 0;
        $totalScore2 = 0;
        foreach ($rounds as $round) {
            [$opponentMoveCode, $myMoveCode] =  explode(' ', $round);
            $opponentMove = $this->getOpponentMove($opponentMoveCode);
            $totalScore1 += $this->getMyMovePart1($myMoveCode)->getScoreAgainst($opponentMove);
            $totalScore2 += $this->getMyMovePart2($myMoveCode, $opponentMove)->getScoreAgainst($opponentMove);
        }

        $this->part1 = $totalScore1;
        $this->part2 = $totalScore2;
    }

    private function getOpponentMove(string $opponentMoveCode) : Move
    {
        switch ($opponentMoveCode) {
            case "A":
                return new Rock();
            case "B":
                return new Paper();
            case "C":
                return new Scissors();
            default:
                throw new \Exception('Invalid move');
        }
    }

    private function getMyMovePart1(string $myMoveCode) : Move
    {
        switch ($myMoveCode) {
            case "X":
                return new Rock();
            case "Y":
                return new Paper();
            case "Z":
                return new Scissors();
            default:
                throw new \Exception('Invalid move');
        }
    }

    private function getMyMovePart2(string $myMoveCode, Move $opponentMove) :Move
    {
        switch ($myMoveCode) {
            case "X":
                return new $opponentMove->winsAgainst;
            case "Y":
                return new $opponentMove;
            case "Z":
                return new $opponentMove->losesAgainst;
            default:
                throw new \Exception('Invalid move');
        }
    }
}