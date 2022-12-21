<?php

namespace AdventOfCode\Common\WolframAlpha;

use WolframAlpha\Engine;
use WolframAlpha\QueryResult;

class Solver
{
    private Engine $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function solveForX(string $equation) : int
    {
        $result = $this->process($equation);
        $solution = $result->pods->find('Solution')->subpods[0]->plaintext
            ?? $result->pods->find('Result')->subpods[0]->plaintext;
        return (int)str_replace('x = ', '', $solution);
    }

    public function simplify(string $equation) : string
    {
        $result = $this->process($equation);
        return $result->pods->find('Result')->subpods[0]->plaintext
            ?? $result->pods->find('AlternateForm')->subpods[0]->plaintext
            ?? $equation;
    }

    private function process(string $equation) : QueryResult
    {
        return $this->engine->process($equation, [], ['plaintext']);
    }
}