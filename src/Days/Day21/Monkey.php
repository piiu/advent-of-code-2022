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
        $math = $this->getEquation($solver);
        return $this->includesVariable($math) ? $math : $this->evalSolver($math);
    }

    public function getEquation(Solver $solver = null) : string
    {
        $math = $this->math;
        foreach ($this->waitingFor as $monkey) {
            $monkeyMath = $monkey->getEquation($solver);
            if (!is_numeric($monkeyMath) && $monkeyMath !== 'x') {
                $monkeyMath = '(' . $monkeyMath . ')';
            }
            $math = str_replace($monkey->id, $monkeyMath, $math);
            if ($solver) {
                $math = $this->simplifyWithSolver($solver, $math);
            }
        }
        if (!preg_match('/[a-z]/', $math)) {
            return $this->evalSolver($math);
        }

        return $math;
    }

    private function includesVariable(string $math) : bool
    {
        return str_contains($math, 'x');
    }

    private function simplifyWithSolver(Solver $solver, string $math) : string
    {
        if ($this->includesVariable($math) && strlen($math) > 60) {
            if (preg_match('/[a-z]{4}/', $math, $matches)) {
                $math = str_replace($matches[0], 'y', $math);
                $math = $solver->simplify($math);
                return str_replace('y', $matches[0], $math);
            }
        }
        return $math;
    }

    private function evalSolver(string $math) : int
    {
        eval("\$result = $math;");
        return $result;
    }
}