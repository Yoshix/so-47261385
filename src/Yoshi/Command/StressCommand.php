<?php
declare(strict_types=1);

namespace Yoshi\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Yoshi\Solver;
use Yoshi\TestGenerator;
use Yoshi\Utility;
use Yoshi\Validator;

class StressCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('stress-test')
            ->setDescription('Solver stress test')
            ->addArgument('tests', InputArgument::REQUIRED)
            ->addOption('size', null, InputOption::VALUE_OPTIONAL, 'Number of rows and columns of test array', 30)
            ->addOption('num', null, InputOption::VALUE_OPTIONAL, 'Number of tests', 1000)
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

        $start = microtime(true);
        foreach ($tests as $i => $test) {
            $solution = $solver->solve($test);
            $valid = Validator::isValid($test, $solution);

            if (!$valid) {
                $output->writeln('could not solve:');
                $output->writeln(Utility::render($test));
                break;
            }

            if (($i + 1) >= $num) {
                break;
            }
        }

        $end = microtime(true);
        $output->writeln(sprintf('avg. time to solve: ~%.5fs', ($end - $start) / $num));
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

            case 'equal':
                return $generator->createEqualRowGenerator((int) $input->getOption('size'));
        }

        throw new \InvalidArgumentException('Unknown test generator.');
    }
}
