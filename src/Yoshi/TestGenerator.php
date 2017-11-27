<?php
declare(strict_types=1);

namespace Yoshi;

class TestGenerator
{
    /**
     * @param int $n
     * @param int $num
     *
     * @return \Generator
     */
    public function createGenerator(int $n, int $num = 1): \Generator
    {
        $gen = function (int $n, int $num): \Generator {
            while ($num-- > 0) {
                yield $this->generateTestCase($n, $n, $n);
            }
        };

        return $gen($n, $num);
    }

    /**
     * @param int $n
     * @param int $num
     *
     * @return \Generator
     */
    public function createUniqueGenerator(int $n, int $num = 1): \Generator
    {
        $gen = function (int $n, int $num): \Generator {
            while ($num-- > 0) {
                yield $this->generateTestCaseWithoutRowDuplicates($n, $n, $n);
            }
        };

        return $gen($n, $num);
    }

    /**
     * @param int $numRows
     * @param int $minNumValues
     * @param int $maxNumValues
     *
     * @return array
     */
    public function generateTestCase(int $numRows, int $minNumValues, int $maxNumValues): array
    {
        return array_map(function () use ($minNumValues, $maxNumValues) {
            return $this->randRow(rand($minNumValues, $maxNumValues));
        }, range(1, $numRows));
    }

    /**
     * @param int $num
     *
     * @return array
     */
    public function randRow(int $num): array
    {
        $chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');
        $result = [];

        for ($i = 0; $i < $num; $i += 1) {
            $result[] = $chars[array_rand($chars)];
        }

        return $result;
    }

    /**
     * @param int $numRows
     * @param int $minNumValues
     * @param int $maxNumValues
     *
     * @return array
     */
    public function generateTestCaseWithoutRowDuplicates(int $numRows, int $minNumValues, int $maxNumValues): array
    {
        return array_map(function () use ($minNumValues, $maxNumValues): array {
            return $this->randRowWithoutDuplicates(rand($minNumValues, $maxNumValues));
        }, range(1, $numRows));
    }

    /**
     * @param int $num
     *
     * @return array
     */
    public function randRowWithoutDuplicates(int $num): array
    {
        $chars = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789');

        return array_map(function (int $key) use ($chars): string {
            return $chars[$key];
        }, array_rand($chars, $num));
    }
}
