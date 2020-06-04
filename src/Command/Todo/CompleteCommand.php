<?php

namespace App\Command\Todo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Command\Todo\Helper\DataModel;


class CompleteCommand extends Command {
    protected function configure() {
        $this->setName('complete')
            ->setDescription('Oznacz zadanie jako wykonane, wprowadzając numer zadani')
            ->setHelp('Oznacza zadanie jako wykonane')
            ->addArgument('jobNo', InputArgument::REQUIRED, 'Numer zadania');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $number = $input->getArgument("jobNo");
        if(!is_numeric($number)) {
            $output->writeln("Błędna wartość argumentu - powinna być liczba wskazująca numer zadania");
        } else {
            $dataModel = new DataModel();
            if($dataModel->jobComplete(intval($number))) {
                $output->writeln("Zadanie o numerze $number zostało oznaczone jako wykonane");
            } else {
                $output->writeln("Zadanie o numerze $number nie zostało odnalezione");
            }
        }
        return 0;
    }

    public function __construct() {
        parent::__construct();
    }
}
