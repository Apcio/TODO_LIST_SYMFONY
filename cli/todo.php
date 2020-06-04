<?php

require(__DIR__ . "/../vendor/autoload.php");

use App\Command\TimeCommand;
use App\Command\Todo\AddCommand;
use App\Command\Todo\CompleteCommand;
use App\Command\Todo\RemoveCommand;
use App\Command\Todo\ShowCommand;

use Symfony\Component\Console\Application;

$app = new Application();

$app->add(new TimeCommand());
$app->add(new AddCommand());
$app->add(new ShowCommand());
$app->add(new RemoveCommand());
$app->add(new CompleteCommand());

$app->run();