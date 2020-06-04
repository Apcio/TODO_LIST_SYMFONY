<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TimeCommand extends Command {
    protected function configure() {
        $this->setName('time')
            ->setDescription('Pokaż aktualną datę i czas')
            ->setDefinition(new InputDefinition([
                new InputOption('short', 's', InputOption::VALUE_NONE, 'Wyświetla czas w krótszym formacie')
            ]))
            ->setHelp('Ta komenda wyświetla aktualna datę i czas');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $message = "Aktualna data i czas: ";
        if($input->getOption('short') === true) {
            $message .= $this->getShortDateTime();
        } else {
            $message .= $this->getLongDateTime();
        }

        $output->writeln($message);
        return 0;
    }

    private function getLongDateTime() {
        return date('c');
    }

    private function getShortDateTime() {
        return date('Y-m-d H:i:s');
    }

    public function __construct() {
        parent::__construct();
    }
}
