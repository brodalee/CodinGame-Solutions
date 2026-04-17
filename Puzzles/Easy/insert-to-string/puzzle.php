<?php

$s = stream_get_line(STDIN, 10000 + 1, "\n");
fscanf(STDIN, "%d", $changeCount);

$changes = [];
for ($i = 0; $i < $changeCount; $i++) {
    $rawChange = stream_get_line(STDIN, 10000 + 1, "\n");
    $parts = explode('|', $rawChange, 3);
    $changes[] = [
        'line' => (int)$parts[0],
        'col'  => (int)$parts[1],
        'add'  => $parts[2]
    ];
}

$lines = explode('\n', $s);

$changesByLine = [];
foreach ($changes as $change) {
    $changesByLine[$change['line']][] = $change;
}

foreach ($changesByLine as $lineNum => $lineChanges) {
    usort($lineChanges, function($a, $b) {
        return $b['col'] - $a['col'];
    });

    foreach ($lineChanges as $change) {
        $col   = $change['col'];
        $toAdd = $change['add'];
        $lines[$lineNum] = substr($lines[$lineNum], 0, $col)
            . $toAdd
            . substr($lines[$lineNum], $col);
    }
}

$result = implode('\n', $lines);
$result = str_replace('\n', "\n", $result);

echo $result . "\n";