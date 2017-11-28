<?php
declare(strict_types=1);

namespace Yoshi;

class TestGenerator
{
    const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';

    /**
     * @param int $n
     *
     * @return \Generator
     */
    public function createGenerator(int $n)
    {
        $gen = function ($n): \Generator {
            while (true) {
                yield $this->generateTestCase($n, $n, $n);
            }
        };

        return $gen($n);
    }

    /**
     * @param int $n
     *
     * @return \Generator
     */
    public function createUniqueGenerator(int $n)
    {
        $gen = function ($n): \Generator {
            while (true) {
                yield $this->generateTestCaseWithoutRowDuplicates($n, $n, $n);
            }
        };

        return $gen($n);
    }

    /**
     * @param int $n
     *
     * @return \Generator
     */
    public function createEqualRowGenerator(int $n)
    {
        $gen = function ($n): \Generator {
            while (true) {
                yield $this->generateEqualRowTestCase($n, $n, $n);
            }
        };

        return $gen($n);
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
        $chars = str_split(self::CHARS);
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
        return array_map(function () use ($minNumValues, $maxNumValues) {
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
        $chars = str_split(self::CHARS);

        return array_map(
            function ($key) use ($chars) {
                return $chars[$key];
            },
            array_rand($chars, $num)
        );
    }

    /**
     * @param int $numRows
     * @param int $minNumValues
     * @param int $maxNumValues
     *
     * @return array
     */
    public function generateEqualRowTestCase(int $numRows, int $minNumValues, int $maxNumValues): array
    {
        return array_fill(0, $numRows, $this->randRowWithoutDuplicates(rand($minNumValues, $maxNumValues)));
    }
}
