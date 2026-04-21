<?php

class Game
{
    public function start()
    {
        fscanf(STDIN, "%d", $c);
        $categories = [];
        for ($i = 0; $i < $c; $i++) {
            fscanf(STDIN, "%s %d", $category, $count);
            $categories[] = [$category, $count];
        }
        fscanf(STDIN, "%d", $q);
        $patterns = [];
        for ($i = 0; $i < $q; $i++) {
            fscanf(STDIN, "%s", $pattern);
            $patterns[] = $pattern;
        }

        $catMask = [];
        foreach ($categories as $idx => $cat) {
            $mask = 0;
            for ($p = 0; $p < $q; $p++) {
                if ($this->matches($cat[0], $patterns[$p])) {
                    $mask |= (1 << $p);
                }
            }
            $catMask[$idx] = $mask;
        }

        $total = 60;
        $hand = 7;
        $denom = $this->comb($total, $hand);

        $result = 0.0;
        $fullMask = (1 << $q) - 1;
        for ($s = 0; $s <= $fullMask; $s++) {
            $bits = $this->popcount($s);
            $excluded = 0;
            foreach ($categories as $idx => $cat) {
                if (($catMask[$idx] & $s) !== 0) {
                    $excluded += $cat[1];
                }
            }
            $remaining = $total - $excluded;
            if ($remaining < $hand) {
                $combVal = 0.0;
            } else {
                $combVal = $this->comb($remaining, $hand);
            }
            if ($bits % 2 === 0) {
                $result += $combVal / $denom;
            } else {
                $result -= $combVal / $denom;
            }
        }

        echo(sprintf("%.4f", $result) . "\n");
    }

    private function matches($card, $pattern)
    {
        for ($i = 0; $i < 3; $i++) {
            if ($pattern[$i] !== 'x' && $pattern[$i] !== $card[$i]) {
                return false;
            }
        }
        return true;
    }

    private function comb($n, $k)
    {
        if ($k > $n || $k < 0) return 0.0;
        if ($k === 0 || $k === $n) return 1.0;
        $result = 1.0;
        if ($k > $n - $k) $k = $n - $k;
        for ($i = 0; $i < $k; $i++) {
            $result = $result * ($n - $i) / ($i + 1);
        }
        return $result;
    }

    private function popcount($n)
    {
        $count = 0;
        while ($n) {
            $count += ($n & 1);
            $n >>= 1;
        }
        return $count;
    }
}


$game = new Game();
$game->start();