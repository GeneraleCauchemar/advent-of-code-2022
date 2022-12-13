<?php

declare(strict_types=1);

namespace App\Conundrum;

// --- Day 7: No Space Left On Device ---
class Day07ConundrumSolver extends AbstractConundrumSolver
{
    private const CD = '$ cd ';
    private const LIST = '$ ls';
    private const UP = '$ cd ..';
    private const ROOT = '/';

    private const DISK_SPACE = 70000000;
    private const UPDATE_WEIGHT = 30000000;

    private array $tree = [];
    private string $pointer = '';
    private array $weightByFolder = [];

    private int $totalWeight = 0;

    public function __construct(string $folder)
    {
        parent::__construct($folder);
    }

    ////////////////
    // PART 1
    ////////////////

    public function partOne(): mixed
    {
        $currentDir = [];
        $this->pointer = 'root';

        foreach ($this->getInput() as $instruction) {
            // Ignoring list instructions
            if (self::LIST === $instruction) {
                continue;
            }

            // Changing folders
            if (str_starts_with($instruction, self::CD)) {
                // Pushes current dir to tree before moving pointer
                $this->pushToTree($currentDir, $this->getKeysFromPointer());

                // Moves up in tree and goes to next instruction
                if (self::UP === $instruction) {
                    $this->movePointerUp();

                    continue;
                }

                // Else moves pointer down to new dir
                $this->movePointerDown($this->getDirName($instruction));

                continue;
            }

            // Managing content (files and dirs)
            [$option, $name] = explode(' ', $instruction);
            $currentDir[$name] = 'dir' === $option ? [] : $option;
        }

        // Pushes current dir to tree
        $this->pushToTree($currentDir, $this->getKeysFromPointer());

        // Compute folder weights recursively
        $this->computeFolderWeights($this->tree, $this->weightByFolder);

        $this->totalWeight = $this->weightByFolder['root'];
        $output = array_filter($this->weightByFolder, function ($value) {
            return 100000 >= $value;
        });

        return array_sum($output);
    }

    ////////////////
    // PART 2
    ////////////////

    public function partTwo(): mixed
    {
        $freespace = self::DISK_SPACE - $this->totalWeight;
        $minSpaceGain = self::UPDATE_WEIGHT - $freespace;

        return array_reduce($this->weightByFolder, function ($carry, $item) use ($minSpaceGain) {
            if (null === $carry) {
                return $item;
            }

            // Return the closest solution that is higher than the desired space gain
            return abs($minSpaceGain - $carry) > abs($item - $minSpaceGain) && $item > $minSpaceGain ? $item : $carry;
        });
    }

    ////////////////
    // METHODS
    ////////////////

    private function getDirName(string $instruction): string
    {
        return str_ireplace(self::CD, '', $instruction);
    }

    private function movePointerUp()
    {
        $exploded = explode('/', $this->pointer);
        array_pop($exploded);

        $this->pointer = implode(self::ROOT, $exploded);
    }

    private function movePointerDown(string $dirname)
    {
        if (!str_starts_with($dirname, self::ROOT)) {
            $dirname = self::ROOT.$dirname;
        }

        $this->pointer .= $dirname;
    }

    private function pushToTree(array &$content, array $keys)
    {
        $contentDir = $content;
        $keys = array_reverse($keys);

        foreach ($keys as $k => $key) {
            $contentDir = [$key => (0 === $k ? $content : $contentDir)];
        }

        $this->tree = array_merge_recursive($this->tree, $contentDir);
        $content = [];
    }

    private function getKeysFromPointer(): array
    {
        return array_filter(explode('/', $this->pointer));
    }

    private function getIntVal(mixed $value): int
    {
        return is_string($value) ? (int) $value : 0;
    }

    private function computeFolderWeights(mixed $value, array &$array, string $path = '')
    {
        if (is_array($value)) {
            foreach ($value as $key => $subvalue) {
                if (is_array($subvalue)) {
                    // We need to go deeper
                    $this->computeFolderWeights($subvalue, $array, $path.'/'.$key);
                }

                if (empty($path)) {
                    $weight = $this->getIntVal($subvalue);
                    $array[$key] = array_key_exists($key, $array) ?
                        $array[$key] + $weight :
                        $weight;
                }
            }
        }

        $pathPart = '';

        // For each parent folder of file, adds file weight
        foreach (array_filter(explode('/', $path)) as $key) {
            $pathPart .= $key;

            $array[$pathPart] = array_key_exists($pathPart, $array) ?
                $array[$pathPart] + (int) array_sum($value) :
                (int) array_sum($value);

            $pathPart .= '/';
        }
    }
}
