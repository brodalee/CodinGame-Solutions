<?php
fscanf(STDIN, "%d", $N);
$set = [];
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%d", $Pi);
    $set[] = $Pi;
}
sort($set);
$min = null;
for ($i = 0; $i < $N - 1; $i++) {
    $diff = $set[$i + 1] - $set[$i];
    if ($min === null || $min > $diff) {
        $min = $diff;
    }
}
echo $min . "\n";