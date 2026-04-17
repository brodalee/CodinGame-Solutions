<?php
fscanf(STDIN, "%d %d %d", $vampireCount, $zombieCount, $ghostCount);
fscanf(STDIN, "%d", $size);

$top = array_map('intval', explode(" ", trim(fgets(STDIN))));
$bottom = array_map('intval', explode(" ", trim(fgets(STDIN))));
$left = array_map('intval', explode(" ", trim(fgets(STDIN))));
$right = array_map('intval', explode(" ", trim(fgets(STDIN))));

$grid = [];
$emptyCells = [];
for ($i = 0; $i < $size; $i++) {
    $row = trim(fgets(STDIN));
    $grid[$i] = str_split($row);
    for ($j = 0; $j < $size; $j++) {
        if ($grid[$i][$j] === '.') {
            $emptyCells[] = [$i, $j];
        }
    }
}

function tracePath($grid, $size, $startRow, $startCol, $dRow, $dCol) {
    $path = [];
    $r = $startRow;
    $c = $startCol;
    $dr = $dRow;
    $dc = $dCol;
    $inMirror = false;
    
    while ($r >= 0 && $r < $size && $c >= 0 && $c < $size) {
        $cell = $grid[$r][$c];
        if ($cell === '\\') {
            $tmp = $dr; $dr = $dc; $dc = $tmp;
            $inMirror = true;
        } elseif ($cell === '/') {
            $tmp = $dr; $dr = -$dc; $dc = -$tmp;
            $inMirror = true;
        } else {
            $path[] = [$r, $c, $inMirror];
        }
        $r += $dr;
        $c += $dc;
    }
    return $path;
}

$clues = [];
for ($j = 0; $j < $size; $j++) {
    $clues[] = [$top[$j], tracePath($grid, $size, 0, $j, 1, 0)];
}
for ($j = 0; $j < $size; $j++) {
    $clues[] = [$bottom[$j], tracePath($grid, $size, $size - 1, $j, -1, 0)];
}
for ($i = 0; $i < $size; $i++) {
    $clues[] = [$left[$i], tracePath($grid, $size, $i, 0, 0, 1)];
}
for ($i = 0; $i < $size; $i++) {
    $clues[] = [$right[$i], tracePath($grid, $size, $i, $size - 1, 0, -1)];
}

$cellIndex = [];
for ($k = 0; $k < count($emptyCells); $k++) {
    $cellIndex[$emptyCells[$k][0] * $size + $emptyCells[$k][1]] = $k;
}

$clueData = [];
for ($ci = 0; $ci < count($clues); $ci++) {
    list($expected, $path) = $clues[$ci];
    $entries = [];
    foreach ($path as $p) {
        $key = $p[0] * $size + $p[1];
        $entries[] = [$cellIndex[$key], $p[2]];
    }
    $clueData[] = [$expected, $entries];
}

$cellClues = array_fill(0, count($emptyCells), []);
for ($ci = 0; $ci < count($clueData); $ci++) {
    foreach ($clueData[$ci][1] as $entry) {
        $cellClues[$entry[0]][] = $ci;
    }
}

$n = count($emptyCells);
$assignment = array_fill(0, $n, null);
$remaining = ['V' => $vampireCount, 'Z' => $zombieCount, 'G' => $ghostCount];

function countVisible(&$assignment, &$clueData, $ci) {
    $count = 0;
    $maxCount = 0;
    foreach ($clueData[$ci][1] as $entry) {
        $idx = $entry[0];
        $inMirror = $entry[1];
        $t = $assignment[$idx];
        if ($t === null) {
            $maxCount++;
            continue;
        }
        if ($t === 'Z' || ($t === 'V' && !$inMirror) || ($t === 'G' && $inMirror)) {
            $count++;
        }
    }
    return [$count, $maxCount];
}

function isConsistent(&$assignment, &$clueData, &$cellClues, $cellIdx, &$remaining) {
    foreach ($remaining as $type => $rem) {
        if ($rem < 0) return false;
    }
    $checked = [];
    foreach ($cellClues[$cellIdx] as $ci) {
        if (isset($checked[$ci])) continue;
        $checked[$ci] = true;
        $expected = $clueData[$ci][0];
        list($count, $maxPossible) = countVisible($assignment, $clueData, $ci);
        if ($count > $expected) return false;
        if ($count + $maxPossible < $expected) return false;
    }
    return true;
}

function solve(&$assignment, $pos, &$emptyCells, &$clueData, &$cellClues, &$remaining, $n) {
    if ($pos === $n) {
        for ($ci = 0; $ci < count($clueData); $ci++) {
            list($count, $max) = countVisible($assignment, $clueData, $ci);
            if ($count !== $clueData[$ci][0]) return false;
        }
        return true;
    }
    foreach (['V', 'Z', 'G'] as $type) {
        if ($remaining[$type] <= 0) continue;
        $assignment[$pos] = $type;
        $remaining[$type]--;
        if (isConsistent($assignment, $clueData, $cellClues, $pos, $remaining)) {
            if (solve($assignment, $pos + 1, $emptyCells, $clueData, $cellClues, $remaining, $n)) {
                return true;
            }
        }
        $assignment[$pos] = null;
        $remaining[$type]++;
    }
    return false;
}

solve($assignment, 0, $emptyCells, $clueData, $cellClues, $remaining, $n);

for ($k = 0; $k < $n; $k++) {
    $grid[$emptyCells[$k][0]][$emptyCells[$k][1]] = $assignment[$k];
}

for ($i = 0; $i < $size; $i++) {
    echo implode('', $grid[$i]) . "\n";
}
