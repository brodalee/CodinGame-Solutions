<?php

class Game
{
    /** @var int */
    private $n;
    /** @var int */
    private $capacity;
    /** @var int[] */
    private $x = [];
    /** @var int[] */
    private $y = [];
    /** @var int[] */
    private $demand = [];
    /** @var array */
    private $dist = [];

    public function start()
    {
        error_reporting(0);
        $this->readInput();
        $this->computeDistances();

        $routes = $this->clarkeWrightSavings();

        $deadline = microtime(true) + 9.0;

        // Initial 2-opt on all routes
        $numR = count($routes);
        for ($i = 0; $i < $numR; $i++) {
            $this->twoOptRoute($routes[$i]);
        }

        // Deterministic local search (limited time: max 2s)
        $lsEnd = microtime(true) + 2.0;
        if ($lsEnd > $deadline - 6.5) $lsEnd = $deadline - 6.5;
        $routes = $this->deterministicLS($routes, $lsEnd);

        // Simulated annealing for remaining time
        $routes = $this->simulatedAnnealing($routes, $deadline - 0.2);

        $this->output($routes);
    }

    private function readInput()
    {
        $line1 = trim(fgets(STDIN));
        $this->n = (int)$line1;
        $line2 = trim(fgets(STDIN));
        $this->capacity = (int)$line2;
        for ($i = 0; $i < $this->n; $i++) {
            $parts = explode(' ', trim(fgets(STDIN)));
            $index = (int)$parts[0];
            $this->x[$index] = (int)$parts[1];
            $this->y[$index] = (int)$parts[2];
            $this->demand[$index] = (int)$parts[3];
        }
    }

    private function computeDistances()
    {
        $n = $this->n;
        for ($i = 0; $i < $n; $i++) {
            $this->dist[$i] = [];
        }
        for ($i = 0; $i < $n; $i++) {
            $xi = $this->x[$i];
            $yi = $this->y[$i];
            $this->dist[$i][$i] = 0;
            for ($j = $i + 1; $j < $n; $j++) {
                $dx = $xi - $this->x[$j];
                $dy = $yi - $this->y[$j];
                $d = (int)round(sqrt($dx * $dx + $dy * $dy));
                $this->dist[$i][$j] = $d;
                $this->dist[$j][$i] = $d;
            }
        }
    }

    private function clarkeWrightSavings()
    {
        $routes = [];
        $customerRoute = [];
        for ($i = 1; $i < $this->n; $i++) {
            $routes[$i] = [$i];
            $customerRoute[$i] = $i;
        }
        $routeLoad = [];
        foreach ($routes as $k => $r) {
            $routeLoad[$k] = $this->demand[$k];
        }

        $savings = [];
        $dist0 = $this->dist[0];
        for ($i = 1; $i < $this->n; $i++) {
            $d0i = $dist0[$i];
            $disti = $this->dist[$i];
            for ($j = $i + 1; $j < $this->n; $j++) {
                $s = $d0i + $dist0[$j] - $disti[$j];
                if ($s > 0) {
                    $savings[] = [$s, $i, $j];
                }
            }
        }
        usort($savings, function ($a, $b) {
            return $b[0] - $a[0];
        });

        foreach ($savings as $sav) {
            $i = $sav[1];
            $j = $sav[2];

            $ri = $customerRoute[$i];
            $rj = $customerRoute[$j];
            if ($ri === $rj) continue;
            if (!isset($routes[$ri]) || !isset($routes[$rj])) continue;

            $routeI = $routes[$ri];
            $routeJ = $routes[$rj];

            $merged = null;
            $lastI = count($routeI) - 1;
            $lastJ = count($routeJ) - 1;

            if ($routeI[$lastI] === $i && $routeJ[0] === $j) {
                $merged = array_merge($routeI, $routeJ);
            } elseif ($routeJ[$lastJ] === $j && $routeI[0] === $i) {
                $merged = array_merge($routeJ, $routeI);
            } elseif ($routeI[$lastI] === $i && $routeJ[$lastJ] === $j) {
                $merged = array_merge($routeI, array_reverse($routeJ));
            } elseif ($routeI[0] === $i && $routeJ[0] === $j) {
                $merged = array_merge(array_reverse($routeI), $routeJ);
            }

            if ($merged === null) continue;

            $newLoad = $routeLoad[$ri] + $routeLoad[$rj];
            if ($newLoad > $this->capacity) continue;

            unset($routes[$ri], $routes[$rj]);
            $routes[$ri] = $merged;
            $routeLoad[$ri] = $newLoad;
            unset($routeLoad[$rj]);
            foreach ($merged as $c) {
                $customerRoute[$c] = $ri;
            }
        }

        return array_values($routes);
    }

    private function routeCost(&$route)
    {
        if (empty($route)) return 0;
        $dist = &$this->dist;
        $cost = $dist[0][$route[0]];
        $len = count($route);
        for ($i = 1; $i < $len; $i++) {
            $cost += $dist[$route[$i - 1]][$route[$i]];
        }
        $cost += $dist[$route[$len - 1]][0];
        return $cost;
    }

    private function totalCost(&$routes)
    {
        $total = 0;
        foreach ($routes as &$r) {
            $total += $this->routeCost($r);
        }
        return $total;
    }

    private function routeLoadCalc(&$route)
    {
        $load = 0;
        $demand = &$this->demand;
        foreach ($route as $c) {
            $load += $demand[$c];
        }
        return $load;
    }

    private function twoOptRoute(&$route, $maxIter = 100)
    {
        $n = count($route);
        if ($n < 2) return;
        $dist = &$this->dist;
        $iter = 0;
        $improved = true;
        while ($improved && ++$iter <= $maxIter) {
            $improved = false;
            for ($i = 0; $i < $n - 1; $i++) {
                $prevI = ($i === 0) ? 0 : $route[$i - 1];
                $ri = $route[$i];
                $dPrevI = $dist[$prevI];
                for ($j = $i + 1; $j < $n; $j++) {
                    $nextJ = ($j === $n - 1) ? 0 : $route[$j + 1];
                    $rj = $route[$j];
                    if ($dPrevI[$rj] + $dist[$ri][$nextJ] < $dPrevI[$ri] + $dist[$rj][$nextJ]) {
                        $left = $i;
                        $right = $j;
                        while ($left < $right) {
                            $tmp = $route[$left];
                            $route[$left] = $route[$right];
                            $route[$right] = $tmp;
                            $left++;
                            $right--;
                        }
                        $ri = $route[$i];
                        $improved = true;
                    }
                }
            }
        }
    }

    private function deterministicLS($routes, $lsDeadline)
    {
        $dist = &$this->dist;
        $demand = &$this->demand;
        $cap = $this->capacity;
        $improved = true;

        while ($improved && microtime(true) < $lsDeadline) {
            $improved = false;
            $numRoutes = count($routes);
            $loads = [];
            for ($r = 0; $r < $numRoutes; $r++) {
                $loads[$r] = $this->routeLoadCalc($routes[$r]);
            }

            // Relocate
            for ($r1 = 0; $r1 < $numRoutes; $r1++) {
                $len1 = count($routes[$r1]);
                for ($i = 0; $i < $len1; $i++) {
                    $cust = $routes[$r1][$i];
                    $prev = ($i === 0) ? 0 : $routes[$r1][$i - 1];
                    $next = ($i === $len1 - 1) ? 0 : $routes[$r1][$i + 1];
                    $removeSaving = $dist[$prev][$cust] + $dist[$cust][$next] - $dist[$prev][$next];

                    for ($r2 = 0; $r2 < $numRoutes; $r2++) {
                        if ($r1 === $r2) continue;
                        if ($loads[$r2] + $demand[$cust] > $cap) continue;

                        $bestIC = PHP_INT_MAX;
                        $bestPos = 0;
                        $len2 = count($routes[$r2]);
                        for ($j = 0; $j <= $len2; $j++) {
                            $pj = ($j === 0) ? 0 : $routes[$r2][$j - 1];
                            $nj = ($j === $len2) ? 0 : $routes[$r2][$j];
                            $ic = $dist[$pj][$cust] + $dist[$cust][$nj] - $dist[$pj][$nj];
                            if ($ic < $bestIC) {
                                $bestIC = $ic;
                                $bestPos = $j;
                            }
                        }

                        if ($bestIC - $removeSaving < 0) {
                            array_splice($routes[$r1], $i, 1);
                            array_splice($routes[$r2], $bestPos, 0, [$cust]);
                            $loads[$r1] -= $demand[$cust];
                            $loads[$r2] += $demand[$cust];
                            if (empty($routes[$r1])) {
                                array_splice($routes, $r1, 1);
                                array_splice($loads, $r1, 1);
                                $numRoutes--;
                                // Adjust r2 index if needed
                                if ($r1 < $r2) $r2--;
                            }
                            // Only 2-opt the 2 affected routes
                            if (isset($routes[$r1]) && !empty($routes[$r1])) {
                                $this->twoOptRoute($routes[$r1], 5);
                            }
                            if (isset($routes[$r2]) && !empty($routes[$r2])) {
                                $this->twoOptRoute($routes[$r2], 5);
                            }
                            $improved = true;
                            break 3;
                        }
                    }
                }
                if (microtime(true) >= $lsDeadline) break;
            }

            if ($improved) continue;

            // Swap
            for ($r1 = 0; $r1 < $numRoutes - 1; $r1++) {
                $len1 = count($routes[$r1]);
                for ($r2 = $r1 + 1; $r2 < $numRoutes; $r2++) {
                    $len2 = count($routes[$r2]);
                    for ($i = 0; $i < $len1; $i++) {
                        $c1 = $routes[$r1][$i];
                        $prev1 = ($i === 0) ? 0 : $routes[$r1][$i - 1];
                        $next1 = ($i === $len1 - 1) ? 0 : $routes[$r1][$i + 1];
                        $dc1 = $dist[$c1];
                        for ($j = 0; $j < $len2; $j++) {
                            $c2 = $routes[$r2][$j];
                            $nl1 = $loads[$r1] - $demand[$c1] + $demand[$c2];
                            if ($nl1 > $cap) continue;
                            $nl2 = $loads[$r2] - $demand[$c2] + $demand[$c1];
                            if ($nl2 > $cap) continue;

                            $prev2 = ($j === 0) ? 0 : $routes[$r2][$j - 1];
                            $next2 = ($j === $len2 - 1) ? 0 : $routes[$r2][$j + 1];

                            $dc2 = $dist[$c2];
                            $delta = $dist[$prev1][$c2] + $dc2[$next1]
                                + $dist[$prev2][$c1] + $dc1[$next2]
                                - $dist[$prev1][$c1] - $dc1[$next1]
                                - $dist[$prev2][$c2] - $dc2[$next2];

                            if ($delta < 0) {
                                $routes[$r1][$i] = $c2;
                                $routes[$r2][$j] = $c1;
                                $loads[$r1] = $nl1;
                                $loads[$r2] = $nl2;
                                $this->twoOptRoute($routes[$r1], 5);
                                $this->twoOptRoute($routes[$r2], 5);
                                $improved = true;
                                break 4;
                            }
                        }
                    }
                }
                if (microtime(true) >= $lsDeadline) break;
            }
        }

        return $routes;
    }

    private function simulatedAnnealing(&$routes, $deadline)
    {
        $dist = &$this->dist;
        $demand = &$this->demand;
        $cap = $this->capacity;

        $bestRoutes = [];
        foreach ($routes as $r) {
            $bestRoutes[] = $r; // copy
        }
        $bestCost = $this->totalCost($bestRoutes);
        $currentCost = $bestCost;
        $currentRoutes = $routes;

        if ($bestCost <= 0) return $bestRoutes;

        $temp = $bestCost * 0.015;
        $startTime = microtime(true);
        $timeLeft = $deadline - $startTime;
        if ($timeLeft <= 0) return $bestRoutes;

        $loads = [];
        $numRoutes = count($currentRoutes);
        for ($r = 0; $r < $numRoutes; $r++) {
            $loads[$r] = $this->routeLoadCalc($currentRoutes[$r]);
        }

        $iter = 0;
        $now = $startTime;

        while (true) {
            $iter++;

            // Check time every 2048 iterations
            if (($iter & 2047) === 0) {
                $now = microtime(true);
                if ($now >= $deadline) break;
                // Adaptive temperature based on time fraction
                $elapsed = $now - $startTime;
                $frac = $elapsed / $timeLeft;
                $temp = $bestCost * 0.015 * (1.0 - $frac);
                if ($temp < 0.01) $temp = 0.01;
            }

            $numRoutes = count($currentRoutes);
            if ($numRoutes === 0) break;

            $moveType = mt_rand(0, 2);

            if ($moveType === 0 && $numRoutes >= 2) {
                // Relocate
                $r1 = mt_rand(0, $numRoutes - 1);
                $r2 = mt_rand(0, $numRoutes - 2);
                if ($r2 >= $r1) $r2++;
                $len1 = count($currentRoutes[$r1]);
                if ($len1 === 0) continue;
                $i = mt_rand(0, $len1 - 1);

                $cust = $currentRoutes[$r1][$i];
                if ($loads[$r2] + $demand[$cust] > $cap) continue;

                $prev = ($i === 0) ? 0 : $currentRoutes[$r1][$i - 1];
                $next = ($i === $len1 - 1) ? 0 : $currentRoutes[$r1][$i + 1];
                $removeSaving = $dist[$prev][$cust] + $dist[$cust][$next] - $dist[$prev][$next];

                // Find best insert position in r2
                $bestIC = PHP_INT_MAX;
                $bestPos = 0;
                $len2 = count($currentRoutes[$r2]);
                // Sample positions for large routes
                if ($len2 > 20) {
                    // Check a few random positions + endpoints
                    $positions = [0, $len2];
                    for ($s = 0; $s < 5; $s++) {
                        $positions[] = mt_rand(0, $len2);
                    }
                    foreach ($positions as $j) {
                        $pj = ($j === 0) ? 0 : $currentRoutes[$r2][$j - 1];
                        $nj = ($j >= $len2) ? 0 : $currentRoutes[$r2][$j];
                        $ic = $dist[$pj][$cust] + $dist[$cust][$nj] - $dist[$pj][$nj];
                        if ($ic < $bestIC) {
                            $bestIC = $ic;
                            $bestPos = $j;
                        }
                    }
                } else {
                    for ($j = 0; $j <= $len2; $j++) {
                        $pj = ($j === 0) ? 0 : $currentRoutes[$r2][$j - 1];
                        $nj = ($j === $len2) ? 0 : $currentRoutes[$r2][$j];
                        $ic = $dist[$pj][$cust] + $dist[$cust][$nj] - $dist[$pj][$nj];
                        if ($ic < $bestIC) {
                            $bestIC = $ic;
                            $bestPos = $j;
                        }
                    }
                }

                $delta = $bestIC - $removeSaving;

                if ($delta < 0 || ($temp > 0.01 && $delta < $temp * 9.21 && mt_rand(0, 10000) < (int)(10000.0 * exp(-$delta / $temp)))) {
                    array_splice($currentRoutes[$r1], $i, 1);
                    if ($bestPos > $i && $r1 === $r2) $bestPos--; // safety
                    array_splice($currentRoutes[$r2], $bestPos, 0, [$cust]);
                    $loads[$r1] -= $demand[$cust];
                    $loads[$r2] += $demand[$cust];
                    if (empty($currentRoutes[$r1])) {
                        array_splice($currentRoutes, $r1, 1);
                        array_splice($loads, $r1, 1);
                    }
                    $currentCost += $delta;
                }
            } elseif ($moveType === 1 && $numRoutes >= 2) {
                // Swap
                $r1 = mt_rand(0, $numRoutes - 1);
                $r2 = mt_rand(0, $numRoutes - 2);
                if ($r2 >= $r1) $r2++;
                $len1 = count($currentRoutes[$r1]);
                $len2 = count($currentRoutes[$r2]);
                if ($len1 === 0 || $len2 === 0) continue;
                $i = mt_rand(0, $len1 - 1);
                $j = mt_rand(0, $len2 - 1);

                $c1 = $currentRoutes[$r1][$i];
                $c2 = $currentRoutes[$r2][$j];
                $nl1 = $loads[$r1] - $demand[$c1] + $demand[$c2];
                $nl2 = $loads[$r2] - $demand[$c2] + $demand[$c1];
                if ($nl1 > $cap || $nl2 > $cap) continue;

                $prev1 = ($i === 0) ? 0 : $currentRoutes[$r1][$i - 1];
                $next1 = ($i === $len1 - 1) ? 0 : $currentRoutes[$r1][$i + 1];
                $prev2 = ($j === 0) ? 0 : $currentRoutes[$r2][$j - 1];
                $next2 = ($j === $len2 - 1) ? 0 : $currentRoutes[$r2][$j + 1];

                $delta = $dist[$prev1][$c2] + $dist[$c2][$next1]
                    + $dist[$prev2][$c1] + $dist[$c1][$next2]
                    - $dist[$prev1][$c1] - $dist[$c1][$next1]
                    - $dist[$prev2][$c2] - $dist[$c2][$next2];

                if ($delta < 0 || ($temp > 0.01 && $delta < $temp * 9.21 && mt_rand(0, 10000) < (int)(10000.0 * exp(-$delta / $temp)))) {
                    $currentRoutes[$r1][$i] = $c2;
                    $currentRoutes[$r2][$j] = $c1;
                    $loads[$r1] = $nl1;
                    $loads[$r2] = $nl2;
                    $currentCost += $delta;
                }
            } else {
                // 2-opt move within a route
                $r = mt_rand(0, $numRoutes - 1);
                $len = count($currentRoutes[$r]);
                if ($len < 3) continue;
                $i = mt_rand(0, $len - 2);
                $j = mt_rand($i + 1, $len - 1);

                $prevI = ($i === 0) ? 0 : $currentRoutes[$r][$i - 1];
                $nextJ = ($j === $len - 1) ? 0 : $currentRoutes[$r][$j + 1];
                $ri = $currentRoutes[$r][$i];
                $rj = $currentRoutes[$r][$j];
                $delta = $dist[$prevI][$rj] + $dist[$ri][$nextJ]
                    - $dist[$prevI][$ri] - $dist[$rj][$nextJ];

                if ($delta < 0 || ($temp > 0.01 && $delta < $temp * 9.21 && mt_rand(0, 10000) < (int)(10000.0 * exp(-$delta / $temp)))) {
                    $left = $i;
                    $right = $j;
                    while ($left < $right) {
                        $tmp = $currentRoutes[$r][$left];
                        $currentRoutes[$r][$left] = $currentRoutes[$r][$right];
                        $currentRoutes[$r][$right] = $tmp;
                        $left++;
                        $right--;
                    }
                    $currentCost += $delta;
                }
            }

            if ($currentCost < $bestCost) {
                $bestCost = $currentCost;
                $bestRoutes = [];
                foreach ($currentRoutes as $r) {
                    $bestRoutes[] = $r;
                }
            }

            // Recalculate to avoid drift every 32768 iterations
            if (($iter & 32767) === 0) {
                $currentCost = $this->totalCost($currentRoutes);
            }
        }

        // Final 2-opt on best
        $cnt = count($bestRoutes);
        for ($i = 0; $i < $cnt; $i++) {
            $this->twoOptRoute($bestRoutes[$i]);
        }

        return $bestRoutes;
    }

    private function output($routes)
    {
        $parts = [];
        foreach ($routes as $route) {
            if (!empty($route)) {
                $parts[] = implode(' ', $route);
            }
        }
        echo implode(';', $parts) . "\n";
    }
}

$game = new Game();
$game->start();