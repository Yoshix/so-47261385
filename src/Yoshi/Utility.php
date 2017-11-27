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
}
