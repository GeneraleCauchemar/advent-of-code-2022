<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 13: Distress Signal ---
class Day13ConundrumSolver extends AbstractConundrumSolver
{
    private const DIVIDERS = [[[2]], [[6]]];

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        $rightOrder = [];

        foreach (array_chunk(array_map('json_decode', $this->getInput()), 2) as $key => $packet) {
            $compare = $this->compare(...$packet);

            if (1 === $compare) {
                $rightOrder[] = $key + 1;
            }
        }

        return array_sum($rightOrder);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $input = array_merge(
            array_map('json_decode', $this->getInput()),
            self::DIVIDERS
        );

        usort($input, function (mixed $a, mixed $b) {
            return $this->compare($a, $b);
        });

        $output = array_reverse($input);

        return (array_search(self::DIVIDERS[0], $output) + 1) * (array_search(self::DIVIDERS[1], $output) + 1);
    }

    ////////////////
    // METHODS
    ////////////////

    /**
     * Recursively compares two values: returns -1 if left is
     * smaller, 1 if it's bigger
     * Continues recursing while values are of different type
     * or equal
     */
    private function compare(mixed $left, mixed $right): int
    {
        if (is_int($left) && is_int($right)) {
            return match (true) {
                $left > $right => -1,
                $left < $right => 1,
                default => 0
            };
        }

        if (is_int($left) && is_array($right)) {
            return $this->compare([$left], $right);
        }

        if (is_array($left) && is_int($right)) {
            return $this->compare($left, [$right]);
        }

        $i = 0;

        while (array_key_exists($i, $left) && array_key_exists($i, $right)) {
            $compare = $this->compare($left[$i], $right[$i]);

            // Not equal, return
            if (0 != $compare) {
                return $compare;
            }

            $i++;
        };

        // At least one of the arrays is empty
        return match (true) {
            $left > $right => -1,
            $left < $right => 1,
            default => 0
        };
    }
}
