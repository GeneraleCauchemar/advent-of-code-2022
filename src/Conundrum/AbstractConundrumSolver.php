<?php

declare(strict_types=1);

namespace App\Conundrum;

abstract class AbstractConundrumSolver implements ConundrumSolverInterface
{
    protected const UNDETERMINED = 'to be determined';

    protected array $input;

    public function __construct(string $day, string $separator = PHP_EOL)
    {
        $this->input = array_filter(
            explode($separator, file_get_contents(sprintf('%s/../../Resources/input/%s.txt', __DIR__, $day)))
        );
    }

    public function execute(): array
    {
        return [
            $this->partOne(),
            $this->partTwo(),
        ];
    }

    public function partOne(): mixed
    {
        return self::UNDETERMINED;
    }

    public function partTwo(): mixed
    {
        return self::UNDETERMINED;
    }
}
