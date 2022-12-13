<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 5: Supply Stacks ---
class Day05ConundrumSolver extends AbstractConundrumSolver
{
    private mixed $cratePiles;
    private mixed $moves;

    public function __construct(string $folder)
    {
        parent::__construct($folder, PHP_EOL.PHP_EOL);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        // Init values
        [$this->cratePiles, $this->moves] = $this->getInput();

        $this->computeCratePiles();
        $this->computeMoves();

        // Let's keep them global values nice and tidy, shall we...
        $localPiles = $this->cratePiles;

        foreach ($this->moves as [$move, $from, $to]) {
            // Move to pile 'A' the last crate from pile 'B', n times
            for ($i = 0; $i < $move; $i++) {
                $localPiles[$to][] = array_pop($localPiles[$from]);
            }
        }

        return $this->writeOutput($localPiles);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $localPiles = $this->cratePiles;

        foreach ($this->moves as [$move, $from, $to]) {
            // Push to pile 'A' the n last crates from pile 'B'
            array_push($localPiles[$to], ...array_splice($localPiles[$from], -$move));
        }

        return $this->writeOutput($localPiles);
    }

    ////////////////
    // METHODS
    ////////////////

    private function computeCratePiles()
    {
        $this->cratePiles = explode(PHP_EOL, $this->cratePiles);
        $this->cratePiles = array_reverse($this->cratePiles);

        // Extracts the pile keys from the input
        $pileKeys = array_filter(preg_split('/\s+/', array_shift($this->cratePiles)));
        $keysFromOffset = array_combine(range(1, 33, 4), $pileKeys);
        $localPiles = array_fill_keys($pileKeys, []);

        // Moves each crate to the proper pile in the array
        array_walk($this->cratePiles, function ($value) use ($keysFromOffset, &$localPiles) {
            // Uses a REGEX to find every crate name and its offset
            preg_match_all('/\[([^]]*)]/', $value, $crates, PREG_OFFSET_CAPTURE);

            // Determines the key from the offset and puts all crates in the right pile
            foreach ($crates[1] as [$crate, $offset]) {
                $localPiles[$keysFromOffset[$offset]][] = $crate;
            }
        });

        $this->cratePiles = $localPiles;
    }

    private function computeMoves()
    {
        $this->moves = array_map(function ($value) {
            return explode(' ', $value);
        }, array_filter(explode(PHP_EOL, str_ireplace(['move ', 'from ', 'to '], '', $this->moves))));
    }

    private function writeOutput(array $cratePiles): string
    {
        array_walk($cratePiles, function ($crates) use (&$output) {
            $output .= end($crates);
        });

        return $output ?? '';
    }
}
