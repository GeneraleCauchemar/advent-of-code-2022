<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 3: Rucksack Reorganization ---
class Day03ConundrumSolver extends AbstractConundrumSolver
{
    public function __construct(string $folder)
    {
        parent::__construct($folder);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        // For each rucksack, finds the common item and returns its value
        $output = array_map(function ($value) {
            $halved = str_split($value, strlen($value) / 2);
            $common = array_intersect(
                str_split($halved[0]),
                str_split($halved[1])
            );

            return $this->getNumericValueForLetter(reset($common));
        }, $this->getInput());

        return array_sum($output);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        // For each group of three elves, finds the common item and returns its value
        $output = array_map(function ($value) {
            $badge = array_intersect(
                str_split($value[0]),
                str_split($value[1]),
                str_split($value[2]),
            );

            return $this->getNumericValueForLetter(reset($badge));
        }, array_chunk($this->getInput(), 3));

        return array_sum($output);
    }

    ////////////////
    // METHODS
    ////////////////

    // Compute the value using the ASCII table
    private function getNumericValueForLetter(string $letter): int
    {
        return ord($letter) - (ctype_lower($letter) ? 96 : 38);
    }
}
