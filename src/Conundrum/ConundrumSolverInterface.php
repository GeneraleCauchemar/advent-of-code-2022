<?php

declare(strict_types=1);

namespace App\Conundrum;

interface ConundrumSolverInterface
{
    public function execute(): array;

    public function partOne(): mixed;

    public function partTwo(): mixed;
}
