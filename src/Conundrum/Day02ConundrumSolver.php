<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 2: Rock Paper Scissors ---
class Day02ConundrumSolver extends AbstractConundrumSolver
{
    private const WIN = 6;
    private const DRAW = 3;
    private const LOSE = 0;
    private const ROCK = 1;
    private const PAPER = 2;
    private const SCISSORS = 3;
    private const OUTCOMES = [
        'A X' => self::DRAW,
        'A Y' => self::WIN,
        'A Z' => self::LOSE,
        'B X' => self::LOSE,
        'B Y' => self::DRAW,
        'B Z' => self::WIN,
        'C X' => self::WIN,
        'C Y' => self::LOSE,
        'C Z' => self::DRAW,
    ];
    private const MOVE_SCORE = [
        'X' => self::ROCK,
        'Y' => self::PAPER,
        'Z' => self::SCISSORS,
    ];
    private const OUTCOME_SCORE = [
        'X' => self::LOSE,
        'Y' => self::DRAW,
        'Z' => self::WIN,
    ];

    public function __construct(string $folder)
    {
        parent::__construct($folder);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        $outcomes = self::OUTCOMES;
        $tacticalScores = self::MOVE_SCORE;
        $scores = array_map(function ($value) use ($outcomes, $tacticalScores) {
            return $outcomes[$value] + $tacticalScores[substr($value, -1)];
        }, $this->getInput());

        return array_sum($scores);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $tacticalScores = self::OUTCOME_SCORE;
        $strategies = [
            'X' => [
                'A' => self::MOVE_SCORE['Z'],
                'B' => self::MOVE_SCORE['X'],
                'C' => self::MOVE_SCORE['Y'],
            ],
            'Y' => [
                'A' => self::MOVE_SCORE['X'],
                'B' => self::MOVE_SCORE['Y'],
                'C' => self::MOVE_SCORE['Z'],
            ],
            'Z' => [
                'A' => self::MOVE_SCORE['Y'],
                'B' => self::MOVE_SCORE['Z'],
                'C' => self::MOVE_SCORE['X'],
            ],
        ];

        $results = array_map(function ($value) use ($strategies, $tacticalScores) {
            $params = preg_split('/\s+/', $value);

            return $strategies[$params[1]][$params[0]] + $tacticalScores[$params[1]];
        }, $this->getInput());

        return array_sum($results);
    }
}
