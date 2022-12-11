<?php

declare(strict_types=1);

namespace App\Conundrum;

use App\Entity\Day11\Monkey;

// --- Day 11: Monkey in the Middle ---
class Day11ConundrumSolver extends AbstractConundrumSolver
{
    private const STARTING_ITEMS = 'Starting items: ';
    private const OPERATION = 'Operation: new = old ';

    private array $monkeys;
    private int $supermodulo;

    public function __construct(string $folder)
    {
        parent::__construct($folder, 'Monkey ');
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        $this->initMonkeys();

        $this->playRounds(20);

        return $this->getMonkeyBusiness();
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $this->initMonkeys();

        $this->playRounds(10000, self::PART_TWO);

        return $this->getMonkeyBusiness();
    }

    ////////////////
    // METHODS
    ////////////////

    private function initMonkeys()
    {
        $input = $this->getInput();
        $this->supermodulo = 1;

        foreach ($input as $info) {
            $info = array_combine([
                'id',
                'items',
                'operation',
                'test',
                'if_true',
                'if_false',
            ], array_filter(explode(PHP_EOL, $info)));
            $args = [
                'id'        => FILTER_SANITIZE_NUMBER_INT,
                'items'     => [
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($value) {
                        $value = str_replace(self::STARTING_ITEMS, '', trim($value));

                        return array_map('intval', explode(', ', $value));
                    },
                ],
                'operation' => [
                    'filter'  => FILTER_CALLBACK,
                    'options' => function ($value) {
                        return [
                            'operand'  => str_contains($value, '*') ? Monkey::MULTIPLY : Monkey::ADD,
                            'modifier' => filter_var($value, FILTER_CALLBACK, [
                                'options' => function ($value) {
                                    $value = str_replace(self::OPERATION, '', trim($value));
                                    $value = explode(' ', $value);

                                    return $value[1];
                                },
                            ]),
                        ];
                    },
                ],
                'test'      => FILTER_SANITIZE_NUMBER_INT,
                'if_true'   => FILTER_SANITIZE_NUMBER_INT,
                'if_false'  => FILTER_SANITIZE_NUMBER_INT,
            ];

            $info = filter_var_array($info, $args);
            $test = (int) $info['test'];

            $this->monkeys[(int) $info['id']] = new Monkey(
                (int) $info['id'],
                $info['items'],
                $info['operation']['operand'],
                $info['operation']['modifier'],
                $test,
                (int) $info['if_true'],
                (int) $info['if_false']
            );

            $this->supermodulo *= $info['test'];
        }
    }

    private function playRounds(int $rounds, int $part = self::PART_ONE)
    {
        for ($i = 0; $i < $rounds; $i++) {
            /** @var Monkey $monkey */
            foreach ($this->monkeys as $monkey) {
                $moves = $monkey->processTurn($part, $this->supermodulo);

                foreach ($moves as $monkeyId => $items) {
                    $this->monkeys[$monkeyId]->receiveItems($items);
                }
            }
        }
    }

    private function getMonkeyBusiness(): int
    {
        $monkeyBusiness = [];

        foreach ($this->monkeys as $monkey) {
            $monkeyBusiness[$monkey->getId()] = $monkey->getInspectedItems();
        }

        rsort($monkeyBusiness, SORT_NUMERIC);

        return (int) $monkeyBusiness[0] * $monkeyBusiness[1];
    }
}
