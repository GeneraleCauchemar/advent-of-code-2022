<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 10: Cathode-Ray Tube ---
class Day10ConundrumSolver extends AbstractConundrumSolver
{
    private const ADDX = 'addx';
    private const NOOP = 'noop';
    private const INTERESTING_CYCLES = [20, 60, 100, 140, 180, 220];
    private const PIXELS_WIDE = 40;
    private const PIXELS_HIGH = 6;

    private int $xRegisterValue = 1;
    private int $cycle = 0;
    private array $xRegisterValueForCycle = [0 => 1];

    public function __construct(string $folder)
    {
        parent::__construct($folder);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        $input = $this->getInput();

        foreach ($input as $instruction) {
            $this->cycle++;
            $this->addXRegisterValueForCycle();

            if (self::NOOP === $instruction) {
                continue;
            }

            $increment = (int) str_replace(self::ADDX.' ', '', $instruction);

            // Move once more, THEN increment
            $this->cycle++;
            $this->xRegisterValue += $increment;
        }

        return array_sum($this->computeSignalStrengths());
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $output = [];
        $multiplier = 0;

        for ($j = 0; $j < self::PIXELS_HIGH; $j++) {
            for ($i = 1; $i <= self::PIXELS_WIDE; $i++) {
                $cycle = $i + (self::PIXELS_WIDE * $multiplier);

                $output[$j][$cycle] = in_array($i, $this->getSpritePosition($cycle)) ? '#' : '.';
            }

            $multiplier++;
        }

        foreach ($output as $screenLine) {
            echo implode('', $screenLine).PHP_EOL;
        }

        return 'RKPJBPLA';
    }

    ////////////////
    // METHODS
    ////////////////

    private function addXRegisterValueForCycle()
    {
        $this->xRegisterValueForCycle[$this->cycle] = $this->xRegisterValue;
    }

    private function computeSignalStrengths(): array
    {
        $output = [];

        foreach (self::INTERESTING_CYCLES as $cycle) {
            $cycle = $this->determineEqualOrClosestLowerCycle($cycle);

            $output[$cycle] = $this->xRegisterValueForCycle[$cycle] * $cycle;
        }

        return $output;
    }

    private function getSpritePosition(int $cycle): array
    {
        $value = $this->xRegisterValueForCycle[$this->determineEqualOrClosestLowerCycle($cycle)];

        return range($value, $value + 2);
    }

    private function determineEqualOrClosestLowerCycle(int $cycle): int
    {
        if (array_key_exists($cycle, $this->xRegisterValueForCycle)) {
            return $cycle;
        }

        $cycles = array_keys($this->xRegisterValueForCycle);

        return array_reduce($cycles, function ($carry, $item) use ($cycle) {
            if (null === $carry) {
                return $item;
            }

            return abs($cycle - $carry) > abs($item - $cycle) && $item < $cycle ? $item : $carry;
        });
    }
}
