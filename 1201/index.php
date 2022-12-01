<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

//////////
// 1
//////////
$caloriesPerElf = [];

foreach (explode(PHP_EOL.PHP_EOL, file_get_contents('./input.txt')) as $data) {
    $caloriesPerElf[] = explode(PHP_EOL, $data);
}

array_walk($caloriesPerElf, function (&$value, $key) {
    $value = array_sum($value);
});

dump(max($caloriesPerElf));

//////////
// 2
//////////
arsort($caloriesPerElf);

dump(array_sum(array_slice($caloriesPerElf, 0, 3)));
