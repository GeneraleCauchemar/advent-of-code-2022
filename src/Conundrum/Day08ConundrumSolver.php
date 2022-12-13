<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 8: Treetop Tree House ---
class Day08ConundrumSolver extends AbstractConundrumSolver
{
    private int $topEdgeKey = 0;
    private int $leftEdgeKey = 0;
    private int $rightEdgeKey = 0;
    private int $bottomEdgeKey = 0;

    private int $lineLength = 0;

    private int $visible = 0;
    private int $visibleOut = 0;
    private int $visibleIn = 0;

    private array $grid = [];

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
        $this->grid = array_fill(0, count($input), []);
        $this->bottomEdgeKey = count($input) - 1;

        // Builds grid
        foreach ($input as $key => $line) {
            if ($key === array_key_first($input)) {
                $this->lineLength = strlen($line);
                $this->rightEdgeKey = $this->lineLength - 1;
            }

            $trees = str_split($line);
            $this->grid[$key] = $trees;
        }

        // Foreach each tree, checks surrounding ones
        foreach ($this->grid as $hKey => $trees) {
            foreach ($trees as $vKey => $tree) {
                if ($this->isEdgeTree($hKey, $vKey)) {
                    $this->visibleOut++;

                    continue;
                }

                if ($this->isVisible($hKey, $vKey, (int) $tree)) {
                    $this->visibleIn++;
                }
            }
        }

        $this->visible = $this->visibleOut + $this->visibleIn;

        return $this->visible;
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $scores = [];

        foreach ($this->grid as $hKey => $trees) {
            foreach ($trees as $vKey => $tree) {
                $scores[] = $this->getScenicScore($hKey, $vKey, (int) $tree);
            }
        }

        return max($scores);
    }

    ////////////////
    // METHODS
    ////////////////

    private function isEdgeTree(int $horizontal, int $vertical): bool
    {
        return (in_array($horizontal, [$this->leftEdgeKey, $this->rightEdgeKey])) ||
            (in_array($vertical, [$this->topEdgeKey, $this->bottomEdgeKey]));
    }

    private function isVisible(int $horizontal, int $vertical, int $height): bool
    {
        $sideTrees = array_merge(
            $this->getAllFromHorizontalLineExcept($horizontal, $vertical),
            $this->getAllFromVerticalLineExcept($vertical, $horizontal)
        );

        foreach ($sideTrees as $trees) {
            $canHide = array_filter($trees, fn($tree) => $tree >= $height);

            // count === 0 means all trees on this side are smaller:
            // this tree is visible
            if (0 === count($canHide)) {
                return true;
            }
        }

        return false;
    }

    private function getScenicScore(int $horizontal, int $vertical, int $height): int
    {
        $sideTrees = array_merge(
            $this->getAllFromHorizontalLineExcept($horizontal, $vertical),
            $this->getAllFromVerticalLineExcept($vertical, $horizontal)
        );
        $scores = [];

        foreach ($sideTrees as $side => $trees) {
            $trees = in_array($side, ['l', 't']) ? array_reverse($trees) : $trees;
            $score = 0;

            foreach ($trees as $tree) {
                $score++;

                if ($tree >= $height) {
                    break;
                }
            }

            $scores[] = $score;
        }

        $scores = array_filter($scores, function ($score) {
            return 0 < $score;
        });;

        return empty($scores) ? 0 : array_product($scores);
    }

    private function getAllFromHorizontalLineExcept(int $horizontal, int $vExcept): array
    {
        $trees = [
            'l' => [],
            'r' => [],
        ];
        $afterExcept = false;

        foreach ($this->grid[$horizontal] as $v => $tree) {
            if ($v === $vExcept) {
                $afterExcept = true;

                continue;
            }

            $trees[$afterExcept ? 'r' : 'l'][] = $this->getTreeHeight($horizontal, $v);
        }

        return $trees;
    }

    private function getAllFromVerticalLineExcept(int $vertical, int $hExcept): array
    {
        $trees = [
            't' => [],
            'b' => [],
        ];
        $afterExcept = false;

        foreach ($this->grid as $h => $line) {
            if ($h === $hExcept) {
                $afterExcept = true;

                continue;
            }

            $trees[$afterExcept ? 'b' : 't'][] = $this->getTreeHeight($h, $vertical);
        }

        return $trees;
    }

    private function getTreeHeight(int $horizontal, int $vertical): int
    {
        return (int) $this->grid[$horizontal][$vertical];
    }
}
