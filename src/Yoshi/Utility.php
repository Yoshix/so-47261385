<?php
declare(strict_types=1);

namespace Yoshi;

class Utility
{
    /**
     * @param array $input
     */
    public static function display(array $input)
    {
        echo self::render($input);
    }

    /**
     * @param array $input
     *
     * @return string
     */
    public static function render(array $input): string
    {
        return implode("\n", array_map(function (array $row): string {
            return implode(' ', array_map(function ($value) {
                return empty($value) ? '-' : $value;
            }, $row));
        }, $input));
    }

    /**
     * @param string $src
     *
     * @return array
     */
    public static function loadTests(string $src): array
    {
        $result = [];
        $test = [];

        foreach (file($src) as $line) {
            $line = trim($line);

            if (empty($line)) {
                $result[] = $test;
                $test = [];
            } else {
                $test[] = array_values(array_filter(array_map('trim', str_split($line))));
            }
        }

        if (count($test) > 0) {
            $result[] = $test;
        }

        return $result;
    }

    /**
     * https://en.wikipedia.org/wiki/Heap%27s_algorithm
     *
     * @param array $elements
     *
     * @return \Generator
     */
    public static function permutations(array $elements): \Generator
    {
        $n = count($elements);
        $c = array_fill(0, $n, 0);

        yield $elements;

        for ($i = 0; $i < $n;) {
            if ($c[$i] < $i) {
                if ($i % 2 === 0) {
                    $tmp = $elements[0];
                    $elements[0] = $elements[$i];
                    $elements[$i] = $tmp;
                } else {
                    $tmp = $elements[$c[$i]];
                    $elements[$c[$i]] = $elements[$i];
                    $elements[$i] = $tmp;
                }

                yield $elements;

                $c[$i] += 1;
                $i = 0;
            } else {
                $c[$i] = 0;
                $i += 1;
            }
        }
    }
}
