<?php


namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputOption;
class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:import';
    protected function configure()
    {
//        $this->addArgument('filePath', InputArgument::REQUIRED, 'Path to CSV file');
        $this->addOption(
            'filePath',
            'f',
            InputOption::VALUE_REQUIRED,
            'Path to CSV file',
            'dataset.csv'
        );
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Path - '.$input->getOption('filePath');


        $output->writeln($text.'!');

        return 0;
    }
}