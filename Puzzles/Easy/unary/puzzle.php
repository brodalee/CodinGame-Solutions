<?php
$MESSAGE = stream_get_line(STDIN, 100, "\n");
$ascii = '';
foreach (str_split($MESSAGE) as $chr) {
    $ascii .= sprintf('%07b', ord($chr));
}
$out = '';
$prev = '';
foreach (str_split($ascii) as $s) {
    if ($prev != $s) {
        $out .= (($prev = $s) == 1) ? ' 0 ' : ' 00 ';
    }
    $out .= '0';
}
echo ltrim($out);