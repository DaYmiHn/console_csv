<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Symfony\Component\Console\Input\InputOption;

class TIMER {

    var $T1 = null;
    var $T2 = null;

    function __construct() {
        $this->start();
    }

    //запустили секундомер
    public function start() {
        $this->T1 = microtime(TRUE);
        $this->T2 = null;
    }

    //остановили секундомер
    public function stop() {
        $this->T2 = microtime(TRUE);
    }

    //измерить результат в секундах (вплоть до мкс)
    public function result() {
        //финишная отметка не определена
        if (is_null($this->T2)) {
            $this->stop();
        }
        return $this->T2 - $this->T1;
    }
}

class CreateUserCommand extends ContainerAwareCommand
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
        ini_set("memory_limit", "32M");
//        $T = new TIMER();
        $path = $input->getOption('filePath');
        $file = fopen($path, "r");
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
        $query = "SET GLOBAL local_infile = 'ON';
                LOAD DATA LOCAL INFILE '".$path."'
                INTO TABLE uploader
                FIELDS TERMINATED BY ' '
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\n'
                (`timestamp`,`rfc`, `domain`, `size`, `path`, `agent`, `status`, `method`, `type` );";

        $statement = $entityManager->getConnection()->prepare($query);
        $statement->execute();

        fclose($file);

//        $output->writeln("Время выполнения кода: " . $T->result() . " c.");
        $output->writeln("Done!");
        return 0;
    }
}