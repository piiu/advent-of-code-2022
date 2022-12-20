<?php

namespace AdventOfCode\Days\Day20;

class Number
{
    public int $currentPosition;
    public int $value;
    private int $total;

    public function __construct(int $order, int $value)
    {
        $this->currentPosition = $order;
        $this->value = $value;
    }

    public function mix(array $allNumbers)
    {
        $this->total = $this->total ?? count($allNumbers);
        $previousPosition = $this->currentPosition;
        $this->move();
        foreach ($allNumbers as $number) {
            if ($number === $this) {
                continue;
            }
            if ($number->currentPosition > $previousPosition && $number->currentPosition <= $this->currentPosition) {
                $number->currentPosition--;
            }
            if ($number->currentPosition < $previousPosition && $number->currentPosition >= $this->currentPosition) {
                $number->currentPosition++;
            }
        }
    }

    public function move()
    {
        $adjustment = $this->value < 0 ? ceil($this->value / $this->total) : floor ($this->value / $this->total);
        $this->currentPosition += $this->value % $this->total + $adjustment;
        if ($this->currentPosition < 0) {
            $this->currentPosition += $this->total - 1;
        }
        if ($this->currentPosition >= $this->total) {
            $this->currentPosition = $this->currentPosition % $this->total + 1;
        }
    }
}