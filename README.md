# Advent of Code 2022

## Prerequisites

- PHP 8.1

## How to install

```bash
$ (symfony) composer install
```

## How to use

### Adding a new solver if none exists

- **`{day}`: single digits must be prefixed with a `0`, ie. `3` => `03` etc.**
- Add a `{day}.txt` in `src/Resources/input/`
- Add a new service in `src/Conundrum/`: `Day{day}ConundrumSolver` and have it extend `AbstractConundrumSolver`
  - Don't forget to pass the separator as second argument in the parent constructor call if it's different from PHP_EOL
- Implement your logic in both `partOne()` and `partTwo()` and have them return your result

### Displaying the results

Just run:

```bash
$ (symfony) php application.php app:resolve-conundrums 1
```

With the day's number as the only argument (both 1 and 01 are valid options).
