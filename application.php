<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use App\Command\ResolveConundrumsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

$application = new Application();
$command = new ResolveConundrumsCommand();

// Format output
$formatter = new OutputFormatter(false, [
    'christmas_red'   => new OutputFormatterStyle(null, 'bright-red'),
    'christmas_white' => new OutputFormatterStyle('black', 'bright-white'),
    'christmas_green' => new OutputFormatterStyle(null, 'green'),
]);
$output = new ConsoleOutput(OutputInterface::VERBOSITY_NORMAL, null, $formatter);

// Add commands
$application->add($command);

// Run
$application->run(null, $output);
