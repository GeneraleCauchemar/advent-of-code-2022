<?php

declare(strict_types=1);

namespace App\Command;

use App\Conundrum\AbstractConundrumSolver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:resolve-conundrums')]
class ResolveConundrumsCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument(
            'day',
            InputArgument::REQUIRED,
            '',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var AbstractConundrumSolver $conundrumSolver */
            $conundrumSolver = $this->getInstanceForDay($input->getArgument('day'));
            $result = $conundrumSolver->execute();

            $output->writeln(
                sprintf(
                    'Solution to part one is <info>%s</info>; solution to part two is <info>%s</info>.',
                    $result[0],
                    $result[1],
                )
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }

    private function getInstanceForDay(string $argument)
    {
        $day = $this->getDay($argument);
        $className = 'App\\Conundrum\\Day'.$day.'ConundrumSolver';

        return class_exists($className) ?
            new $className($day) :
            throw new \Exception(sprintf('<error>There is no service to solve day %s yet!</error>', $argument));
    }

    private function getDay(string $day): string
    {
        return 1 === strlen($day) ? '0'.$day : $day;
    }
}
