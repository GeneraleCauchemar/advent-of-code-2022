<?php

declare(strict_types=1);

namespace App\Conundrum;

use App\Exception\InputFileNotFoundException;

abstract class AbstractConundrumSolver implements ConundrumSolverInterface
{
    public const PART_ONE = 1;
    public const PART_TWO = 2;
    protected const UNDETERMINED = 'to be determined';

    private array|string $input;
    private array $testInputs;
    private string $day;
    private ?string $separator;
    private bool $keepAsString;

    public function __construct(
        string $day,
        ?string $separator = PHP_EOL,
        bool $keepAsString = false
    ) {
        $this->day = $day;
        $this->separator = $separator;
        $this->keepAsString = $keepAsString;
    }

    /**
     * @throws InputFileNotFoundException
     */
    public function execute(): array
    {
        $this->initInput();
        $this->initTestInputs();

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

    protected function getInput(): array|string
    {
        return $this->input;
    }

    protected function getTestInput(int $part = self::PART_ONE)
    {
        if (array_key_exists($part, $this->testInputs)) {
            return $this->testInputs[$part];
        }

        return [];
    }

    private function initInput()
    {
        $path = sprintf('%s/../../Resources/input/%s.txt', __DIR__, $this->day);

        if (!file_exists($path)) {
            throw new InputFileNotFoundException(sprintf('<error>Missing input file at path "%s".</error>', $path));
        }

        $this->input = $this->keepAsString ?
            trim(file_get_contents($path)) :
            array_filter(explode($this->separator, file_get_contents($path)));
    }

    private function initTestInputs()
    {
        $this->testInputs = [];
        $partialPath = '%s/../../Resources/input/test/%s_%s.txt';
        $paths = [
            self::PART_ONE => sprintf($partialPath, __DIR__, $this->day, '0'.self::PART_ONE),
            self::PART_TWO => sprintf($partialPath, __DIR__, $this->day, '0'.self::PART_TWO),
        ];

        foreach ($paths as $part => $path) {
            if (!file_exists($path)) {
                continue;
            }

            $this->testInputs[$part] = $this->keepAsString ?
                trim(file_get_contents($path)) :
                array_filter(explode($this->separator, file_get_contents($path)));
        }

        if (empty($this->testInputs)) {
            $path = sprintf(str_replace('_%s', '', $partialPath), __DIR__, $this->day);

            if (file_exists($path)) {
                $this->testInputs = array_fill_keys(
                    [self::PART_ONE, self::PART_TWO],
                    $this->keepAsString ?
                        trim(file_get_contents($path)) :
                        array_filter(explode($this->separator, file_get_contents($path)))
                );
            }
        }
    }
}
