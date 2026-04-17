<?php

namespace App;

class Game
{
    public function start()
    {
        fscanf(STDIN, "%d %d", $w, $h);
        $grid = [];
        for ($i = 0; $i < $h; $i++) {
            $inputs = explode(" ", trim(fgets(STDIN)));
            for ($j = 0; $j < $w; $j++) {
                $grid[$i][$j] = intval($inputs[$j]);
            }
        }
        fscanf(STDIN, "%d %d", $a, $b);
        fscanf(STDIN, "%d", $t);

        $result = $this->findMinShots($grid, $a, $b, $t);
        echo $result;
    }

    public function findMinShots(array $grid, int $a, int $b, int $t): string
    {
        $h = count($grid);
        $w = count($grid[0]);
        $minShots = PHP_INT_MAX;
        $rects = [[ $a, $b ], [ $b, $a ]];
        foreach ($rects as list($rw, $rh)) {
            if ($rw > $w || $rh > $h) continue;
            for ($y = 0; $y <= $h - $rh; $y++) {
                for ($x = 0; $x <= $w - $rw; $x++) {
                    $sub = [];
                    $maxH = 0;
                    $minH = 100;
                    for ($dy = 0; $dy < $rh; $dy++) {
                        for ($dx = 0; $dx < $rw; $dx++) {
                            $val = $grid[$y+$dy][$x+$dx];
                            $sub[] = $val;
                            if ($val > $maxH) $maxH = $val;
                            if ($val < $minH) $minH = $val;
                        }
                    }
                    for ($target = $minH; $target <= $maxH; $target++) {
                        $shots = 0;
                        foreach ($sub as $v) {
                            if ($v > $target) $shots += $v - $target;
                            elseif ($v < $target) $shots = PHP_INT_MAX;
                        }
                        if ($shots < $minShots) $minShots = $shots;
                    }
                }
            }
        }

        return ($minShots <= $t) ? (string)$minShots : "Not Possible";
    }
}