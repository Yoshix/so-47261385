#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Yoshi\Command\SolveCommand;
use Yoshi\Command\StressCommand;

$solveCommand = new SolveCommand();
$stressCommand = new StressCommand();

$application = new Application();
$application->add($solveCommand);
$application->add($stressCommand);
$application->run();