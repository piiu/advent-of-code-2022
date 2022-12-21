<?php

namespace AdventOfCode\Days\Day21;

use AdventOfCode\Common\BaseDay;
use AdventOfCode\Common\WolframAlpha\EngineFactory;
use AdventOfCode\Common\WolframAlpha\Solver;

class Solution extends BaseDay
{
    public function execute()
    {
        $monkeys = $this->getMonkeys();
        $solver = $this->getSolver();

        $this->part1 = $monkeys['root']->doMath();

        $monkeys['humn']->math = 'x';
        $monkeys['root']->math = str_replace('+', '=', $monkeys['root']->math);
        $equation = $monkeys['root']->doMath($solver);

        $this->part2 = $solver ? $solver->solveForX($equation) : $equation;
    }

    private function getSolver() : ?Solver
    {
        if ($waEngine = EngineFactory::create()) {
            return new Solver($waEngine);
        }
        return null;
    }

    /**
     * @return Monkey[]
     */
    private function getMonkeys() : array
    {
        $monkeys = [];
        foreach ($this->getInputArray() as $rawMonkey) {
            [$id, $math] = explode(': ', $rawMonkey);
            $monkeys[$id] = new Monkey($id, $math);
        }

        foreach ($monkeys as $monkey) {
            if (is_numeric($monkey->math)) {
                continue;
            }
            foreach (preg_split('/ [\+\-\*\/] /', $monkey->math) as $id) {
                $monkey->addWaitingFor($monkeys[$id]);
            }
        }
        return $monkeys;
    }
}