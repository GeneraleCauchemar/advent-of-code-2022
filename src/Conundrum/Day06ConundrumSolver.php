<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 6: Tuning Trouble ---
class Day06ConundrumSolver extends AbstractConundrumSolver
{
    public function __construct(string $folder)
    {
        parent::__construct($folder, PHP_EOL, true);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        return $this->solve(4);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        return $this->solve(14);
    }

    ////////////////
    // METHODS
    ////////////////

    private function solve(int $markerLength): string
    {
        $buffer = [];

        foreach (str_split($this->getInput()) as $index => $letter) {
            $buffer[++$index] = $letter;

            if ($markerLength === count($buffer)) {
                // Every character is unique, return index
                if ($buffer === array_unique($buffer)) {
                    return (string) $index;
                }

                // Removes first element from array without re-indexing
                unset($buffer[array_key_first($buffer)]);
            }
        }

        return '';
    }
}
