<?php

class Game
{
    const MAP_W = 96;
    const MAP_H = 54;

    /** @var array [x,y][] */
    private $rabbits = [];
    /** @var array [x,y][] */
    private $snake = [];

    public function start()
    {
        // Read rabbits
        fscanf(STDIN, "%d", $n);
        for ($i = 0; $i < $n; $i++) {
            fscanf(STDIN, "%d %d", $x, $y);
            $this->rabbits[] = [$x, $y];
        }

        // Game loop
        while (true) {
            fscanf(STDIN, "%d", $ns);
            $this->snake = [];
            for ($i = 0; $i < $ns; $i++) {
                fscanf(STDIN, "%d %d", $x, $y);
                $this->snake[] = [$x, $y];
            }

            $headX = $this->snake[0][0];
            $headY = $this->snake[0][1];

            // Remove rabbits that we've caught (head is on rabbit)
            $this->rabbits = array_values(array_filter($this->rabbits, function ($r) use ($headX, $headY) {
                return !($r[0] === $headX && $r[1] === $headY);
            }));

            // Build body set (skip head index 0)
            $bodySet = [];
            for ($i = 1, $len = count($this->snake); $i < $len; $i++) {
                $bodySet[$this->snake[$i][0] . ',' . $this->snake[$i][1]] = true;
            }

            if (empty($this->rabbits)) {
                $next = $this->getSafeMove($headX, $headY, $bodySet);
                echo $next[0] . " " . $next[1] . "\n";
                continue;
            }

            // BFS from head to find nearest rabbit
            $next = $this->bfsToNearestRabbit($headX, $headY, $bodySet);

            if ($next !== null) {
                echo $next[0] . " " . $next[1] . "\n";
            } else {
                $safe = $this->getSafeMove($headX, $headY, $bodySet);
                echo $safe[0] . " " . $safe[1] . "\n";
            }
        }
    }

    /**
     * BFS from head to find nearest rabbit. Returns next step [x,y] or null.
     */
    private function bfsToNearestRabbit($startX, $startY, $bodySet)
    {
        $rabbitSet = [];
        foreach ($this->rabbits as $r) {
            $rabbitSet[$r[0] . ',' . $r[1]] = true;
        }

        $visited = [];
        $visited[$startX . ',' . $startY] = true;

        $queue = new \SplQueue();
        $dirs = [[1, 0], [-1, 0], [0, 1], [0, -1]];

        foreach ($dirs as $d) {
            $nx = $startX + $d[0];
            $ny = $startY + $d[1];
            if ($nx < 0 || $nx >= self::MAP_W || $ny < 0 || $ny >= self::MAP_H) {
                continue;
            }
            $nk = $nx . ',' . $ny;
            if (isset($bodySet[$nk]) || isset($visited[$nk])) {
                continue;
            }
            $visited[$nk] = true;
            if (isset($rabbitSet[$nk])) {
                return [$nx, $ny];
            }
            $queue->enqueue([$nx, $ny, $nx, $ny]);
        }

        while (!$queue->isEmpty()) {
            $item = $queue->dequeue();
            $cx = $item[0];
            $cy = $item[1];
            $fx = $item[2];
            $fy = $item[3];

            foreach ($dirs as $d) {
                $nx = $cx + $d[0];
                $ny = $cy + $d[1];
                if ($nx < 0 || $nx >= self::MAP_W || $ny < 0 || $ny >= self::MAP_H) {
                    continue;
                }
                $nk = $nx . ',' . $ny;
                if (isset($bodySet[$nk]) || isset($visited[$nk])) {
                    continue;
                }
                $visited[$nk] = true;
                if (isset($rabbitSet[$nk])) {
                    return [$fx, $fy];
                }
                $queue->enqueue([$nx, $ny, $fx, $fy]);
            }
        }

        return null;
    }

    /**
     * Get a safe move (not into body, not out of bounds).
     */
    private function getSafeMove($hx, $hy, $bodySet)
    {
        $dirs = [[1, 0], [-1, 0], [0, 1], [0, -1]];
        foreach ($dirs as $d) {
            $nx = $hx + $d[0];
            $ny = $hy + $d[1];
            if ($nx >= 0 && $nx < self::MAP_W && $ny >= 0 && $ny < self::MAP_H) {
                if (!isset($bodySet[$nx . ',' . $ny])) {
                    return [$nx, $ny];
                }
            }
        }
        return [$hx + 1, $hy];
    }
}

$game = new Game();
$game->start();