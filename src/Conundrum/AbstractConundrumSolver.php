<?php

declare(strict_types=1);

namespace App\Conundrum;

use App\Exception\InputFileNotFoundException;

abstract class AbstractConundrumSolver implements ConundrumSolverInterface
{
    protected const UNDETERMINED = 'to be determined';

    private array $input;
    private string $day;
    private string $separator;

    public function __construct(string $day, string $separator = PHP_EOL)
    {
        $this->day = $day;
        $this->separator = $separator;
    }

    public function execute(): array
    {
        $path = sprintf('%s/../../Resources/input/%s.txt', __DIR__, $this->day);

        if (!file_exists($path)) {
            throw new InputFileNotFoundException(sprintf('<error>Missing input file at path "%s".</error>', $path));
        }

        $this->input = array_filter(explode($this->separator, file_get_contents($path)));

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

    protected function getInput(): array
    {
        return $this->input;
    }
}
