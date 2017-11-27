<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Yoshi\TestGenerator;
use Yoshi\Utility;
use Yoshi\SwapSolver;
use Yoshi\Validator;

// fixed tests
$tests = Utility::loadTests('tests.txt');

// random tests
#$generator = new TestGenerator();
#$tests = $generator->createUniqueGenerator(30, 10);

$solver = new SwapSolver();

$start = microtime(true);
$num = 0;

foreach ($tests as $test) {
    $num += 1;
    $solution = $solver->solve($test);
    if (!Validator::isValid($test, $solution)) {
        echo 'failed:';
        Utility::display($test);
    }
}

$total = microtime(true) - $start;
printf('done after: %.5fs (~%.5fs/test)', $total, $total / $num);