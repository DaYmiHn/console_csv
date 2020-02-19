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
        ini_set("memory_limit", "3000M");
//        $entityManager = $this->getDoctrine()->getManager();

        $T = new TIMER();
        $file = fopen($input->getOption('filePath'), "r");
        $i=1;
        $entityManager = $this->getContainer()->get('doctrine')->getManager();
//        while (($data = fgetcsv($file, 10000, " ")) !== FALSE)
//        {
//            $uploader = new Uploader();
//            $uploader->setTimestamp($data[0]);
//            $uploader->setRfc($data[1]);
//            $uploader->setDomain($data[2]);
//            $uploader->setSize($data[3]);
//            $uploader->setPath($data[4]);
//            $uploader->setAgent($data[5]);
//            $uploader->setStatus($data[6]);
//            $uploader->setMethod($data[7]);
//            $uploader->setType($data[8]);
//
//            $output->writeln($i.'_-_'.memory_get_usage());
//            if ($i>1000){
//                $entityManager->persist($uploader);

//            }
//            if (memory_get_usage()>200000000)
//                unset($uploader);
//            $query = 'LOAD DATA LOCAL INFILE "D:\Desktop\dataset.csv\"
//INTO TABLE uploader
//FIELDS TERMINATED BY \',\'
//ENCLOSED BY \'"\'
//LINES TERMINATED BY \'\n\'
//(`id`, `timestamp`, `domain`, `size`, `path`, `agent`, `status`, `method`, `type`, `rfc`);';
        $query = "SET GLOBAL local_infile = 'ON';LOAD DATA LOCAL INFILE 'D:\\\Desktop\\\dataset.csv'
                INTO TABLE uploader
                FIELDS TERMINATED BY ' '
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\n'
                (`timestamp`,`rfc`, `domain`, `size`, `path`, `agent`, `status`, `method`, `type` );";

            $statement = $entityManager->getConnection()->prepare($query);
            $statement->execute();
            $i++;
//        }
//        $entityManager->flush();

        fclose($file);

        $output->writeln("Время выполнения кода: " . $T->result() . " c.");

        return 0;
    }
}