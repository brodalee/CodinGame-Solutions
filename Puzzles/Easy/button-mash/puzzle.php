<?php

fscanf(STDIN, "%d", $n);

function minButtonPresses(int $n, array &$memo = []): int {
    if ($n == 0) return 0;
    if ($n == 1) return 1;
    if (isset($memo[$n])) return $memo[$n];
    if ($n % 2 === 0) {
        $res = 1 + minButtonPresses($n / 2, $memo);
    } else {
        $res = 1 + min(
            minButtonPresses($n - 1, $memo),
            minButtonPresses($n + 1, $memo)
        );
    }
    return $memo[$n] = $res;
}

echo minButtonPresses($n) . "\n";
