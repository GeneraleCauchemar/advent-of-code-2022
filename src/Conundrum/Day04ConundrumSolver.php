<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 4: Camp Cleanup ---
class Day04ConundrumSolver extends AbstractConundrumSolver
{
    private array $spreadRanges;

    public function __construct(string $folder)
    {
        parent::__construct($folder);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        $this->setSpreadRanges();

        // Finds out every case where one range fully contains the other
        $output = array_filter($this->spreadRanges, function ($value) {
            return in_array($this->getOverlap(...$value), $value);
        });

        return count($output);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        // Finds out every case where the two ranges overlap
        $output = array_filter($this->spreadRanges, function ($value) {
            return 0 < count($this->getOverlap(...$value));
        });

        return count($output);
    }

    ////////////////
    // METHODS
    ////////////////

    private function setSpreadRanges()
    {
        $this->spreadRanges = array_map(function ($value) {
            return array_map(fn($range) => range(...explode('-', $range)), explode(',', $value));
        }, $this->getInput());
    }

    private function getOverlap(array $range1, array $range2): array
    {
        return array_values(array_intersect($range1, $range2));
    }
}
