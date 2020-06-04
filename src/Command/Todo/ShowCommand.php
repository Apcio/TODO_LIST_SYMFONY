<?php

namespace App\Command\Todo;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use App\Command\Todo\Helper\DataModel;
use Symfony\Component\Console\Input\InputOption;

class ShowCommand extends Command {
    protected function configure() {
        $this->setName('show')
            ->setDescription('Wyświetla wszystkie zadania w formie tabeli')
            ->setHelp('Wyświetl wszystkie zadania, możesz użyć opcji <info>today</info>, <info>sortBy</info> lub <info>hideCompleted</info>')
            ->addOption("sortBy", null, InputOption::VALUE_REQUIRED, "Podaj nazwę kolumny według której wyniki będą sortowane (nazwa lub data)")
            ->addOption("hideCompleted", null, InputOption::VALUE_NONE, "Ukryj zadania wykonane")
            ->addOption("today", null, InputOption::VALUE_NONE, "Wyświetl zadania do wykonania dzisiaj");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $data = new DataModel();

        $tableData = $data->getList($input->getOption("today"), $input->getOption("hideCompleted"));
        if($input->getOption("sortBy") !== null) {
            $tableData = $data->sortList($tableData, $input->getOption("sortBy"));
        }

        $tableData = $data->getListForTable($tableData);

        $table = new Table($output);
        $table->setHeaderTitle('Lista zadań');
        $table->setHeaders($tableData->HeaderList);
        $table->setRows($tableData->Rows);

        unset($tableData);

        $table->render();

        return 0;
    }

    public function __construct() {
        parent::__construct();
    }
}
