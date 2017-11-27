<?php
declare(strict_types=1);

namespace Yoshi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Yoshi\Solver;
use Yoshi\TestGenerator;
use Yoshi\Utility;
use Yoshi\Validator;

class SolveCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('solve')
            ->setDescription('Solve so/47261385')
            ->addArgument('tests', InputArgument::REQUIRED)
            ->addOption('size', null, InputOption::VALUE_OPTIONAL, 'Number of rows and columns of test array', 5)
            ->addOption('src', null, InputOption::VALUE_OPTIONAL, 'Source for fixed tests', 'tests.txt')
            ->addOption('num', null, InputOption::VALUE_OPTIONAL, 'Number of tests', 1)
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $solver = new Solver\SwapSolver();
        $tests = $this->loadTests($input->getArgument('tests'), $input);
        $num = (int) $input->getOption('num');

        $table = new Table($output);
        $table->setHeaders(['test', 'solution', 'result', 'time']);

        foreach ($tests as $test) {
            $rows = count($test);
            $start = microtime(true);
            $solution = $solver->solve($test);
            $end = microtime(true);

            $valid = Validator::isValid($test, $solution);

            $table->addRow([
                new TableCell(Utility::render($test), ['rowspan' => $rows]),
                new TableCell(Utility::render($solution), ['rowspan' => $rows]),
                $valid ? 'Valid' : 'Not valid',
                sprintf('%.5fs', $end - $start)
            ]);

            if (!$valid) {
                break;
            }

            if (--$num === 0) {
                break;
            }

            $table->addRow(new TableSeparator());
        }

        $table->render();
    }

    /**
     * @param string         $kind
     * @param InputInterface $input
     *
     * @return \Traversable
     */
    private function loadTests(string $kind, InputInterface $input): \Traversable
    {
        $generator = new TestGenerator();

        switch ($kind) {
            case 'unique':
                return $generator->createUniqueGenerator((int) $input->getOption('size'));

            case 'random':
                return $generator->createGenerator((int) $input->getOption('size'));

            case 'fixed':
                return new \ArrayIterator(Utility::loadTests($input->getOption('src')));
        }

        throw new \InvalidArgumentException('Unknown test case.');
    }
}
