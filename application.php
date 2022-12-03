<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use App\Command\ResolveConundrumsCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$command = new ResolveConundrumsCommand();

$application->add($command);

$application->run();
