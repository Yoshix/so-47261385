<?php
declare(strict_types=1);

namespace Yoshi\Solver;

final class SwapSolver implements SolverInterface
{
    /**
     * @param array $input
     *
     * @return array
     */
    public function solve(array $input): array
    {
        $input = array_values($input);

        return $this->swapDuplicates($this->prepare($input, $this->getMinRowLength($input)));
    }

    /**
     * @param array $input
     *
     * @return int
     */
    private function getMinRowLength(array $input): int
    {
        return max(
            ...array_values(array_count_values(array_merge(...$input))),
            ...array_map('count', $input)
        );
    }

    /**
     * @param array $input
     * @param int   $padSize
     *
     * @return array
     */
    private function prepare(array $input, int $padSize): array
    {
        return array_map(function (array $row) use ($padSize): array {
            return $this->shuffle(array_pad($row, $padSize, null));
        }, $input);
    }

    /**
     * @param array $input
     *
     * @return array
     */
    private function shuffle(array $input): array
    {
        for ($i = count($input) - 1; $i > 0; $i -= 1) {
            $input = $this->swap($input, $i, random_int(0, $i));
        }

        return $input;
    }

    /**
     * @param array $input
     *
     * @return array
     */
    private function swapDuplicates(array $input): array
    {
        $unswappable = [];

        foreach ($this->duplicates($input) as $position) {
            list($r, $a) = $position;

            $swapped = false;
            foreach ($this->swapCandidates($input, $r, $a, true) as $b) {
                $input[$r] = $this->swap($input[$r], $a, $b);
                $swapped = true;
                break;
            }

            if (!$swapped) {
                $unswappable[] = $position;
            }
        }

        // still unswappable
        $unswappable = array_values(array_filter($unswappable, function (array $position) use ($input): bool {
            return $this->isDuplicate($input, ...$position);
        }));

        // tie breaker
        if (($num = count($unswappable)) > 0) {
            list($r, $a) = $unswappable[random_int(0, $num - 1)];

            $candidates = [];
            foreach ($this->swapCandidates($input, $r, $a, false) as $b) {
                $candidates[] = $b;
            }

            $input[$r] = $this->swap($input[$r], $a, $candidates[random_int(0, count($candidates) - 1)]);

            return $this->swapDuplicates($input);
        }

        return $input;
    }

    /**
     * @param array $input
     *
     * @return \Generator
     */
    private function duplicates(array &$input): \Generator
    {
        foreach ($input as $r => $row) {
            foreach ($row as $c => $value) {
                if ($this->isDuplicate($input, $r, $c)) {
                    yield [$r, $c];
                }
            }
        }
    }

    /**
     * @param array $input
     * @param int   $row
     * @param int   $column
     *
     * @return bool
     */
    private function isDuplicate(array $input, int $row, int $column): bool
    {
        $candidate = $input[$row][$column];

        if (is_null($candidate)) {
            return false;
        }

        foreach (array_column($input, $column) as $r => $value) {
            if ($r !== $row && $value === $candidate) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $input
     * @param int   $row
     * @param int   $column
     * @param bool  $strict
     *
     * @return \Generator
     */
    private function swapCandidates(array &$input, int $row, int $column, bool $strict): \Generator
    {
        foreach ($input[$row] as $c => $dst) {
            if ((!$strict || !in_array($input[$row][$column], array_column($input, $c), true))
                && (is_null($dst) || !in_array($dst, array_column($input, $column), true))
            ) {
                yield $c;
            }
        }
    }

    /**
     * @param array $input
     * @param int   $from
     * @param int   $to
     *
     * @return array
     */
    private function swap(array $input, int $from, int $to): array
    {
        $tmp = $input[$to];
        $input[$to] = $input[$from];
        $input[$from] = $tmp;

        return $input;
    }
}
