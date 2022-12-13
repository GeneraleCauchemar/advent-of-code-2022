<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 1: Calorie Counting ---
class Day01ConundrumSolver extends AbstractConundrumSolver
{
    private array $caloriesPerElf = [];

    public function __construct(string $folder)
    {
        parent::__construct($folder, PHP_EOL.PHP_EOL);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        foreach ($this->getInput() as $data) {
            $this->caloriesPerElf[] = explode(PHP_EOL, $data);
        }

        array_walk($this->caloriesPerElf, function (&$value) {
            $value = array_sum($value);
        });

        return max($this->caloriesPerElf);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        arsort($this->caloriesPerElf);

        return array_sum(array_slice($this->caloriesPerElf, 0, 3));
    }
}
