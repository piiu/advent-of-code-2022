<?php

namespace AdventOfCode\Days\Day11;

class Monkey
{
    private array $items;
    private string $operation;
    private int $divisibleTest;
    private int $trueTo;
    private int $falseTo;
    private Monkey $trueToMonkey;
    private Monkey $falseToMonkey;
    private int $inspectionCount = 0;

    public function __construct(array $items, string $operation, int $divisibleTest, int $trueTo, int $falseTo)
    {
        $this->items = $items;
        $this->operation = $operation;
        $this->divisibleTest = $divisibleTest;
        $this->trueTo = $trueTo;
        $this->falseTo = $falseTo;
    }

    public function addItem(int $item)
    {
        $this->items[] = $item;
    }

    public function inspect($divideByThree = true) : ?int
    {
        if (empty($this->items)) {
            return null;
        }
        $this->inspectionCount++;
        $input = array_shift($this->items);
        $math = str_replace('old', $input, $this->operation);
        eval("\$result = $math;");
        return $divideByThree ? floor($result / 3) : $result;
    }

    public function throw(int $item)
    {
        $throwTo = $item % $this->divisibleTest === 0 ? $this->trueToMonkey : $this->falseToMonkey;
        $throwTo->addItem($item);
    }

    public function getInspectionCount(): int
    {
        return $this->inspectionCount;
    }

    public function setTrueToMonkey(Monkey $trueToMonkey)
    {
        $this->trueToMonkey = $trueToMonkey;
    }

    public function setFalseToMonkey(Monkey $falseToMonkey)
    {
        $this->falseToMonkey = $falseToMonkey;
    }

    public function getTrueTo(): int
    {
        return $this->trueTo;
    }

    public function getFalseTo(): int
    {
        return $this->falseTo;
    }
}