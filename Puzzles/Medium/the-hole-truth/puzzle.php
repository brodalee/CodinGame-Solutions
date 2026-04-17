<?php

fscanf(STDIN, "%d %d", $W, $H);
$grid = [];
for ($i = 0; $i < $H; $i++) {
    $grid[] = str_split(stream_get_line(STDIN, 256, "\n"));
}

$queue = new SplQueue();
for ($y = 0; $y < $H; $y++) {
    for ($x = 0; $x < $W; $x++) {
        if ($grid[$y][$x] === '.' && ($y === 0 || $y === $H - 1 || $x === 0 || $x === $W - 1)) {
            $grid[$y][$x] = 'B';
            $queue->enqueue([$x, $y]);
        }
    }
}
$dirs = [[0,1],[0,-1],[1,0],[-1,0]];
while (!$queue->isEmpty()) {
    [$cx, $cy] = $queue->dequeue();
    foreach ($dirs as $d) {
        $nx = $cx + $d[0];
        $ny = $cy + $d[1];
        if ($nx >= 0 && $nx < $W && $ny >= 0 && $ny < $H && $grid[$ny][$nx] === '.') {
            $grid[$ny][$nx] = 'B';
            $queue->enqueue([$nx, $ny]);
        }
    }
}

$holes = 0;
for ($y = 0; $y < $H; $y++) {
    for ($x = 0; $x < $W; $x++) {
        if ($grid[$y][$x] === '.') {
            $holes++;
            $queue->enqueue([$x, $y]);
            $grid[$y][$x] = 'H';
            while (!$queue->isEmpty()) {
                [$cx, $cy] = $queue->dequeue();
                foreach ($dirs as $d) {
                    $nx = $cx + $d[0];
                    $ny = $cy + $d[1];
                    if ($nx >= 0 && $nx < $W && $ny >= 0 && $ny < $H && $grid[$ny][$nx] === '.') {
                        $grid[$ny][$nx] = 'H';
                        $queue->enqueue([$nx, $ny]);
                    }
                }
            }
        }
    }
}

echo($holes . "\n");
