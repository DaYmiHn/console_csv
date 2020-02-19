<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

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
//        $entityManager = $this->getDoctrine()->getManager();
        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        $T = new TIMER();
        $file = fopen($input->getOption('filePath'), "r");
        $i=1;
        while (($data = fgetcsv($file, 300000, " ")) !== FALSE)
        {
            $uploader = new Uploader();
            $uploader->setTimestamp($data[0]);
            $uploader->setRfc($data[1]);
            $uploader->setDomain($data[2]);
            $uploader->setSize($data[3]);
            $uploader->setPath($data[4]);
            $uploader->setAgent($data[5]);
            $uploader->setStatus($data[6]);
            $uploader->setMethod($data[7]);
            $uploader->setType($data[8]);
            $output->writeln($i);
            $entityManager->persist($uploader);
            if (memory_get_usage() > 30217728){
                $entityManager->flush();
                unset($uploader);
            }
            $i++;
        }

        fclose($file);

        $output->writeln("Время выполнения кода: " . $T->result() . " c.");

        return 0;
    }
}