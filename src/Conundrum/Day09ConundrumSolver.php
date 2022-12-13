<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 9: Rope Bridge ---
class Day09ConundrumSolver extends AbstractConundrumSolver
{
    private const UP = 'U';
    private const RIGHT = 'R';
    private const LEFT = 'L';
    private const DOWN = 'D';
    private const ROPE_LENGTH = [self::PART_ONE => 2, self::PART_TWO => 10];

    private array $visited = [self::PART_ONE => [], self::PART_TWO => []];
    private array $positions = [];

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

        $this->initPositions(1);

        array_walk($input, function ($instruction) {
            [$direction, $moves] = explode(' ', $instruction);

            $this->computeMoves(self::PART_ONE, (int) $moves, $direction);
        });

        // Count only unique positions
        return count(array_unique($this->visited[self::PART_ONE], SORT_REGULAR));
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $input = $this->getInput();

        $this->initPositions(9);

        array_walk($input, function ($instruction) {
            [$direction, $moves] = explode(' ', $instruction);

            $this->computeMoves(self::PART_TWO, (int) $moves, $direction);
        });

        // Count only unique positions
        return count(array_unique($this->visited[self::PART_TWO], SORT_REGULAR));
    }

    ////////////////
    // METHODS
    ////////////////

    private function initPositions(int $rangeEnd)
    {
        $this->positions = array_fill_keys(range(0, $rangeEnd), ['x' => 0, 'y' => 0]);
    }

    private function computeMoves(int $part, int $moves, string $direction)
    {
        for ($i = 0; $i < $moves; $i++) {
            $this->saveVisitedCoordinates($part);

            for ($j = 0; $j < self::ROPE_LENGTH[$part]; $j++) {
                $this->move($part, $j, $direction);
            }
        }
    }

    private function move(int $part, int $knotMarker, string $direction)
    {
        if (0 === $knotMarker) {
            $this->moveHead($direction);
        } elseif (!$this->areTouching($knotMarker)) {
            $this->moveFollowingKnot($part, $knotMarker);
        }
    }

    private function moveHead(string $direction)
    {
        [$axis, $modifier] = $this->computeMoveForHead($direction);
        $this->positions[array_key_first($this->positions)][$axis] += $modifier;
    }

    private function computeMoveForHead(string $direction): array
    {
        return match ($direction) {
            self::UP => ['y', -1],
            self::DOWN => ['y', 1],
            self::LEFT => ['x', -1],
            self::RIGHT => ['x', 1],
        };
    }

    private function moveFollowingKnot(int $part, int $knotMarker)
    {
        foreach ($this->computeMoveForKnot($knotMarker) as [$axis, $modifier]) {
            $this->positions[$knotMarker][$axis] += $modifier;
        }

        // Last knot, save new coordinates
        if (9 === $knotMarker) {
            $this->saveVisitedCoordinates($part);
        }
    }

    private function computeMoveForKnot(int $knotMarker): array
    {
        $a = $this->positions[$knotMarker - 1];
        $b = $this->positions[$knotMarker];

        if ($this->moveDiagonally($a, $b)) {
            return [
                ['x', 0 < ($a['x'] - $b['x']) ? 1 : -1],
                ['y', 0 < ($a['y'] - $b['y']) ? 1 : -1],
            ];
        }

        if ($a['y'] === $b['y'] && 2 === abs($a['x'] - $b['x'])) {
            return [['x', $a['x'] > $b['x'] ? 1 : -1]];
        }

        return [['y', $a['y'] > $b['y'] ? 1 : -1]];
    }

    private function areTouching(int $knotMarker): bool
    {
        $a = $this->positions[$knotMarker - 1];
        $b = $this->positions[$knotMarker];

        return ($a['x'] === $b['x'] || 1 === abs($a['x'] - $b['x'])) &&
            ($a['y'] === $b['y'] || 1 === abs($a['y'] - $b['y']));
    }

    private function saveVisitedCoordinates(int $part)
    {
        $this->visited[$part][] = $this->positions[array_key_last($this->positions)];
    }

    private function moveDiagonally(array $a, array $b): bool
    {
        return $b['x'] !== $a['x'] && $b['y'] !== $a['y'];
    }
}
