<?php
declare(strict_types=1);

namespace Yoshi;

class Validator
{
    /**
     * @param array $source
     * @param array $solution
     *
     * @return bool
     */
    public static function isValid(array $source, array $solution): bool
    {
        return self::validateSameElements($source, $solution)
            && self::validateNoDuplicates($solution)
        ;
    }

    /**
     * @param array $source
     * @param array $solution
     *
     * @return bool
     */
    public static function validateSameElements($source, $solution): bool
    {
        foreach ($source as $r => $row) {
            if (count(array_diff($row, $solution[$r])) > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $solution
     *
     * @return bool
     */
    public static function validateNoDuplicates($solution): bool
    {
        $numRows = count($solution);

        foreach ($solution as $r => $row) {
            foreach ($row as $c => $value) {
                for ($i = $r + 1; $i < $numRows; $i += 1) {
                    if (!empty($value) && isset($solution[$i][$c]) && $solution[$i][$c] === $value) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
