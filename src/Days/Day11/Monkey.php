<?php

namespace AdventOfCode\Days\Day11;

class Monkey
{
    public int $id;
    public array $items;
    public string $operation;
    public int $divisibleTest;
    public int $trueTo;
    public int $falseTo;
    public Monkey $trueToMonkey;
    public Monkey $falseToMonkey;
    public int $inspectionCount = 0;

    public function __construct(int $id, array $items, string $operation, int $divisibleTest, int $trueTo, int $falseTo)
    {
        $this->id = $id;
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

    public function inspect($divideByThree) : ?int
    {
        if (empty($this->items)) {
            return null;
        }
        $this->inspectionCount++;
        $item = array_shift($this->items);
        $math = str_replace('old', $item, $this->operation);
        eval("\$result = $math;");
        return $divideByThree ? floor($result / 3) : $result;
    }

    public function throw(int $item, int $mod)
    {
        $throwTo = $item % $this->divisibleTest === 0 ? $this->trueToMonkey : $this->falseToMonkey;
        $throwTo->addItem($item % $mod);
    }
}