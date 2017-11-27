<?php
declare(strict_types=1);

namespace Yoshi\Solver;

interface SolverInterface
{
    /**
     * @param array $input
     *
     * @return array
     */
    public function solve(array $input): array;
}
