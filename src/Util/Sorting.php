<?php

declare(strict_types=1);

namespace App\Util;

final class Sorting
{
    public static function dateCompareArrays(array $element1, array $element2): int
    {
        $datetime1 = strtotime($element1['date']);
        $datetime2 = strtotime($element2['date']);

        return $datetime2 - $datetime1;
    }
}
