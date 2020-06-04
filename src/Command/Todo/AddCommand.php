<?php

namespace App\Command\Todo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use App\Command\Todo\Helper\DataModel;
use DateTime;

class AddCommand extends Command {
    private function checkDate($d) {
        if(!($d instanceof DateTime)) return false;
        $errors = DateTime::getLastErrors();
        if($errors === false) return false;
        if(is_array($errors)) {
            if($errors["warning_count"] > 0) return false;
            if($errors["error_count"] > 0) return false;
        }
        return true;
    }

    protected function configure() {
        $this->setName('add')
            ->setDescription('Dodaje pozycję do listy')
            ->setHelp('Dodaje do listy zadań nową pozycję');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $ask = $this->getHelper('question');
        $question = new Question('Proszę podać nazwę zadania [Nowe zadanie]: ', 'Nowe zadanie');
        $name = $ask->ask($input, $output, $question);

        $question = new Question('Proszę podać opis zadania [Wykonać w wolnej chwili]: ', 'Wykonać w wolnej chwili');
        $desc = $ask->ask($input, $output, $question);

        $date = date('Y-m-d');
        $question = new Question("Proszę podać datę realizacji zadania (format Y-m-d) [$date]: ", $date);
        do {
            $date = $ask->ask($input, $output, $question);
            if(!empty($date)) {
                $date = DateTime::createFromFormat('Y-m-d', $date);
            } else {
                $date = new DateTime();
            }

            if($this->checkDate($date) === false) {
                $output->writeln('Podata data jest nieprawidłowa.');
                $date = null;
            }
        } while(($date instanceof DateTime) === false);
        
        $question = new Question('Czy zapisać powyższe dane? (t/n) [t]: ', 't');
        $save = $ask->ask($input, $output, $question);
        if(strtolower($save) === 't') {
            $model = new DataModel();
            $model->addNewJob($name, $desc, $date);
            $output->writeln('Zapisano');
        } else {
            $output->writeln('Anulowano');
        }

        return 0;
    }

    public function __construct() {
        parent::__construct();
    }
}
