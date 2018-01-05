<?php
function xrange($start, $limit, $step = 1) {
    $var = [];

    for ($i = $start; $i <= $limit; $i += $step) {
        $var[] = $i;
    }

    return $var;
}

echo 'Single digit odd numbers: ';

/*
 * Note that an array is never created or returned,
 * which saves memory.
 */
foreach (xrange(1, 9, 2) as $number) {
    echo "$number ";
}

echo "\n";