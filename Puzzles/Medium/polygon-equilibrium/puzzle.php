<?php

class Game
{
    public function start()
    {
        fscanf(STDIN, "%d", $n);
        $vertices = [];
        for ($i = 0; $i < $n; $i++) {
            fscanf(STDIN, "%d %d", $x, $y);
            $vertices[] = [$x, $y];
        }

        $cx = 0.0;
        $cy = 0.0;
        $areaSum = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $j = ($i + 1) % $n;
            $cross = $vertices[$i][0] * $vertices[$j][1] - $vertices[$j][0] * $vertices[$i][1];
            $areaSum += $cross;
            $cx += ($vertices[$i][0] + $vertices[$j][0]) * $cross;
            $cy += ($vertices[$i][1] + $vertices[$j][1]) * $cross;
        }
        $cx /= (3.0 * $areaSum);
        $cy /= (3.0 * $areaSum);

        $hull = $this->convexHull($vertices);
        $hullCount = count($hull);

        $supportingSegments = $hullCount;
        $equilibria = 0;

        for ($i = 0; $i < $hullCount; $i++) {
            $j = ($i + 1) % $hullCount;
            $ax = $hull[$i][0];
            $ay = $hull[$i][1];
            $bx = $hull[$j][0];
            $by = $hull[$j][1];

            $dx = $bx - $ax;
            $dy = $by - $ay;

            $projC = $cx * $dx + $cy * $dy;
            $projA = $ax * $dx + $ay * $dy;
            $projB = $bx * $dx + $by * $dy;

            $lo = min($projA, $projB);
            $hi = max($projA, $projB);

            if ($projC >= $lo - 1e-9 && $projC <= $hi + 1e-9) {
                $equilibria++;
            }
        }

        echo $supportingSegments . "\n";
        echo $equilibria . "\n";
    }

    private function convexHull(array $points)
    {
        $n = count($points);
        if ($n < 2) {
            return $points;
        }

        usort($points, function ($a, $b) {
            if ($a[0] !== $b[0]) {
                return $a[0] - $b[0];
            }
            return $a[1] - $b[1];
        });

        $points = array_values(array_unique($points, SORT_REGULAR));
        $n = count($points);
        if ($n < 2) {
            return $points;
        }

        $lower = [];
        foreach ($points as $p) {
            while (count($lower) >= 2 && $this->cross($lower[count($lower) - 2], $lower[count($lower) - 1], $p) <= 0) {
                array_pop($lower);
            }
            $lower[] = $p;
        }

        $upper = [];
        foreach (array_reverse($points) as $p) {
            while (count($upper) >= 2 && $this->cross($upper[count($upper) - 2], $upper[count($upper) - 1], $p) <= 0) {
                array_pop($upper);
            }
            $upper[] = $p;
        }

        array_pop($lower);
        array_pop($upper);

        return array_merge($lower, $upper);
    }

    private function cross(array $o, array $a, array $b)
    {
        return ($a[0] - $o[0]) * ($b[1] - $o[1]) - ($a[1] - $o[1]) * ($b[0] - $o[0]);
    }
}

$game = new Game();
$game->start();