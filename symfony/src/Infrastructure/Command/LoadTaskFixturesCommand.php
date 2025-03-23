<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\DataFixture\TaskFixture;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-task-fixtures',
    description: 'Load task fixtures into the database',
)]
class LoadTaskFixturesCommand extends Command
{
    private TaskFixture $taskFixture;

    public function __construct(TaskFixture $taskFixture)
    {
        parent::__construct();
        $this->taskFixture = $taskFixture;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $io->info('Starting to load task fixtures...');
        
        try {
            $this->taskFixture->load();
            $io->success('Successfully loaded 20 tasks with tags.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Error loading task fixtures: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
