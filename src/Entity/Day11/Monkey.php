<?php

declare(strict_types=1);

namespace App\Entity\Day11;

use App\Conundrum\AbstractConundrumSolver;

class Monkey
{
    public const MULTIPLY = 'multiply';
    public const ADD = 'add';

    private int $id;
    private array $items;
    private string $operand;
    private string|int $modifier;
    private $test;
    private int $toMonkeyIfTrue;
    private int $toMonkeyIfFalse;
    private $currentWorryLevel;
    private int $inspectedItems;

    public function __construct(
        int $id,
        array $items,
        string $operand,
        $modifier,
        $test,
        int $toMonkeyIfTrue,
        int $toMonkeyIfFalse
    ) {
        $this->id = $id;
        $this->items = $items;
        $this->operand = $operand;
        $this->modifier = $modifier;
        $this->test = $test;
        $this->toMonkeyIfTrue = $toMonkeyIfTrue;
        $this->toMonkeyIfFalse = $toMonkeyIfFalse;
        $this->inspectedItems = 0;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getInspectedItems(): int
    {
        return $this->inspectedItems;
    }

    public function receiveItems(array $items): void
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }
    }

    public function processTurn(int $part, int $supermodulo): array
    {
        $moveTo = [];
        $this->resetWorryLevel();

        foreach ($this->items as $item) {
            $this->inspect($item, $supermodulo);

            if ($part === AbstractConundrumSolver::PART_ONE) {
                $this->computeRelief();
            }

            $moveTo[$this->isTest() ? $this->toMonkeyIfTrue : $this->toMonkeyIfFalse][] = $this->currentWorryLevel;

            $this->resetWorryLevel();
        }

        $this->items = [];

        return $moveTo;
    }

    private function resetWorryLevel(): void
    {
        $this->currentWorryLevel = 0;
    }

    private function inspect($item, int $supermodulo): void
    {
        $worryLevel = $item;
        $modifier = 'old' === $this->modifier ? $worryLevel : $this->modifier;

        $this->currentWorryLevel = self::MULTIPLY === $this->operand ? $worryLevel * $modifier : $worryLevel + $modifier;
        $this->currentWorryLevel = $this->currentWorryLevel % $supermodulo;

        $this->inspectedItems++;
    }

    private function computeRelief(): void
    {
        $this->currentWorryLevel = (int) floor($this->currentWorryLevel / 3);
    }

    private function isTest(): bool
    {
        return 0 === $this->currentWorryLevel % $this->test;
    }
}
