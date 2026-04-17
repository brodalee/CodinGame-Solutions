<?php
$n = trim(stream_get_line(STDIN, 256 + 1, "\n"));

$result = '';
while (bccomp($n, '0') > 0) {
    $mod = bcmod($n, '9');
    if ($mod === '0') {
        $result = '9' . $result;
        $n = bcsub(bcdiv($n, '9', 0), '1');
    } else {
        $result = $mod . $result;
        $n = bcdiv($n, '9', 0);
    }
}

echo $result . "\n";