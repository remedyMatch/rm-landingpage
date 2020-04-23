<?php

declare(strict_types=1);

namespace App\Util;

final class DateTime
{
    private function __construct()
    {
    }

    public static function dateCompareArrays(array $element1, array $element2): int
    {
        $datetime1 = strtotime($element1['date']);
        $datetime2 = strtotime($element2['date']);

        return $datetime2 - $datetime1;
    }

    public static function prioCompareArrays(array $element1, array $element2): int
    {
        $priority1 =(int)$element1['priority'];
        $priority2 = (int)$element2['priority'];

        return  $priority1-$priority2;
    }
}
