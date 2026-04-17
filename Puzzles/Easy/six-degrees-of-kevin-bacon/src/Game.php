<?php

namespace App;

class Game
{
    public function start()
    {
        $actorName = stream_get_line(STDIN, 30 + 1, "\n");
        fscanf(STDIN, "%d", $n);
        $movieCasts = [];
        for ($i = 0; $i < $n; $i++) {
            $movieCasts[] = stream_get_line(STDIN, 200 + 1, "\n");
        }
        $result = self::getBaconNumber($actorName, $movieCasts);
        echo($result . "\n");
    }

    /**
     * Calcule le Bacon number d'un acteur à partir d'une liste de castings.
     * @param string $actorName
     * @param array $movieCasts
     * @return int
     */
    public static function getBaconNumber(string $actorName, array $movieCasts): int
    {
        $graph = [];
        foreach ($movieCasts as $castLine) {
            $parts = explode(':', $castLine, 2);
            if (count($parts) < 2) continue;
            $actors = array_map('trim', explode(',', $parts[1]));
            foreach ($actors as $actor1) {
                foreach ($actors as $actor2) {
                    if ($actor1 !== $actor2) {
                        $graph[$actor1][] = $actor2;
                    }
                }
            }
        }

        $queue = [[$actorName, 0]];
        $visited = [$actorName => true];
        while (!empty($queue)) {
            list($current, $depth) = array_shift($queue);
            if ($current === 'Kevin Bacon') {
                return $depth;
            }
            if (!isset($graph[$current])) continue;
            foreach ($graph[$current] as $neighbor) {
                if (!isset($visited[$neighbor])) {
                    $visited[$neighbor] = true;
                    $queue[] = [$neighbor, $depth + 1];
                }
            }
        }

        return -1;
    }
}