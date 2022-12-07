<?php

namespace AdventOfCode\Days;

use AdventOfCode\Common\BaseDay;

class Day07 extends BaseDay
{
    private array $fileStructure = [];
    private array $directorySizes = [];

    public function execute()
    {
        $this->buildFileStructure();
        $this->populateDirectorySizes($this->fileStructure);

        $this->part1 = array_sum(array_filter($this->directorySizes, function ($size) {
            return $size < 100000;
        }));

        $sizeRequired = 30000000 - (70000000 - max($this->directorySizes));
        $this->part2 = min(array_filter($this->directorySizes, function ($size) use ($sizeRequired) {
            return $size > $sizeRequired;
        }));
    }

    private function populateDirectorySizes(array $directory) : int
    {
        $size = 0;
        foreach ($directory as $element) {
            $size += is_array($element) ? $this->populateDirectorySizes($element) : $element;
        }
        $this->directorySizes[] = $size;
        return $size;
    }

    private function buildFileStructure()
    {
        $currentLocation = [];
        $fileList = [];

        foreach ($this->getInputArray() as $line) {
            $parts = explode(' ', trim($line));
            if ($parts[0] === '$') {
                if (!empty($fileList)) {
                    $this->setArrayValue($this->fileStructure, $currentLocation, $fileList);
                    $fileList = [];
                }
                if ($parts[1] === 'cd') {
                    if ($parts[2] === '/') {
                        $currentLocation = [];
                    } elseif ($parts[2] === '..') {
                        array_pop($currentLocation);
                    } else {
                        $currentLocation[] = $parts[2];
                    }
                }
            } elseif ($parts[0] !== 'dir') {
                $fileList[] = $parts[0];
            }
        }
        $this->setArrayValue($this->fileStructure, $currentLocation, $fileList);
    }

    public function setArrayValue(array &$array, array $keys, $value)
    {
        $current = &$array;
        foreach ($keys as $key) {
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        $current = $value;
    }
}