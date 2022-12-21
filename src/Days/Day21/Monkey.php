<?php

namespace AdventOfCode\Days\Day21;

use AdventOfCode\Common\WolframAlpha\Solver;

class Monkey
{
    public string $id;
    public string $math;
    /** @var Monkey[] */
    public array $waitingFor = [];

    public function __construct(string $id, string $math)
    {
        $this->id = $id;
        $this->math = $math;
    }

    public function addWaitingFor(Monkey $monkey)
    {
        $this->waitingFor[] = $monkey;
    }

    public function doMath(Solver $solver = null) : string
    {
        $math = $this->getMath($solver);
        if (str_contains($math, 'x')) {
            return $math;
        }
        eval("\$result = $math;");
        return $result;
    }

    public function getMath(Solver $solver = null) : string
    {
        if (is_numeric($this->math) || $this->math === 'x') {
            return $this->math;
        }
        $math = $this->math;
        foreach ($this->waitingFor as $monkey) {
            $monkeyMath = $monkey->getMath($solver);
            if (!is_numeric($monkeyMath) && $monkeyMath !== 'x') {
                $monkeyMath = '(' . $monkeyMath . ')';
            }
            $math = str_replace($monkey->id, $monkeyMath, $math);
            if ($solver && str_contains($math, 'x') && strlen($math) > 60) {
                $y = $this->extractConstantFromEquation($math);
                if ($y) {
                    $math = $solver->simplify($math);
                    $math = str_replace('y', $y, $math);
                }
            }
        }
        if (!preg_match('/[a-z]/', $math)) {
            eval("\$result = $math;");
            return $result;
        }

        return $math;
    }

    private function extractConstantFromEquation(string &$math) : ?string
    {
        preg_match('/[a-z]{4}/', $math, $matches);
        if (empty($matches)) {
            return null;
        }
        $match = $matches[0];
        $math = str_replace($match, 'y', $math);
        return $match;
    }
}