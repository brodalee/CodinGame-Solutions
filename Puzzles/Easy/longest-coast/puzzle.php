<?php

fscanf(STDIN, "%d", $n);
$grid = [];
for ($i = 0; $i < $n; $i++) {
    $grid[] = stream_get_line(STDIN, $n + 1, "\n");
}

$visited = array_fill(0, $n, array_fill(0, $n, false));
$dirs = [[0, 1], [0, -1], [1, 0], [-1, 0]];

$islandIndex = 0;
$bestIndex = 0;
$bestCoast = -1;

for ($r = 0; $r < $n; $r++) {
    for ($c = 0; $c < $n; $c++) {
        if ($grid[$r][$c] === '#' && !$visited[$r][$c]) {
            $islandIndex++;

            $queue = [[$r, $c]];
            $visited[$r][$c] = true;
            $islandCells = [[$r, $c]];
            $head = 0;

            while ($head < count($queue)) {
                list($cr, $cc) = $queue[$head++];
                foreach ($dirs as $d) {
                    $nr = $cr + $d[0];
                    $nc = $cc + $d[1];
                    if ($nr >= 0 && $nr < $n && $nc >= 0 && $nc < $n
                        && $grid[$nr][$nc] === '#' && !$visited[$nr][$nc]) {
                        $visited[$nr][$nc] = true;
                        $queue[] = [$nr, $nc];
                        $islandCells[] = [$nr, $nc];
                    }
                }
            }

            $waterTiles = [];
            foreach ($islandCells as $cell) {
                list($cr, $cc) = $cell;
                foreach ($dirs as $d) {
                    $nr = $cr + $d[0];
                    $nc = $cc + $d[1];
                    if ($nr >= 0 && $nr < $n && $nc >= 0 && $nc < $n
                        && $grid[$nr][$nc] === '~') {
                        $waterTiles[$nr . ',' . $nc] = true;
                    }
                }
            }

            $coast = count($waterTiles);

            if ($coast > $bestCoast) {
                $bestCoast = $coast;
                $bestIndex = $islandIndex;
            }
        }
    }
}

echo $bestIndex . ' ' . $bestCoast . "\n";