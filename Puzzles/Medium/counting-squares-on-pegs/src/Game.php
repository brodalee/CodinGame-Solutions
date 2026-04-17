<?php

namespace App;

class Game
{
    /**
     * @var Peg[]
     */
    public $coordinates = [];
    private $coordinateMap = [];

    public function start()
    {
        //$start = microtime(true);
        fscanf(STDIN, "%d", $N);
        for ($i = 0; $i < $N; $i++)
        {
            fscanf(STDIN, "%d %d", $X, $Y);
            $this->coordinates[] = new Peg($N, $X, $Y);
        }

        $this->buildCoordinateMap();
        $result = $this->getNumberOfSquares();
        //$end = microtime(true);
        //Debugger::debug(
        //    sprintf("Time execution : %s ms", (($end - $start) * 1000))
        //);
        echo $result;
    }

    /**
     * @return int
     */
    public function getNumberOfSquares()
    {
        $numberOfSquare = 0;
        foreach ($this->coordinates as $peg) {
            foreach ($this->coordinates as $peg2) {
                $dx = $peg2->x - $peg->x;
                $dy = $peg2->y - $peg->y;

                if ($peg == $peg2
                    || ($dx < 0 || $dy < 0)
                    || !$this->some($peg2, $dy, $dx)
                    || (
                        !$this->some($peg, $dy, $dx)
                        || $dx === 0 && $peg->x - $dy < $peg2->x
                    )
                ) {
                    continue;
                }

                $numberOfSquare++;
            }
        }

        return $numberOfSquare;
    }

    private function buildCoordinateMap()
    {
        foreach ($this->coordinates as $peg) {
            $this->coordinateMap[$peg->x][$peg->y] = true;
        }
    }

    /**
     * @param Peg $p2
     * @param int $dy
     * @param int $dx
     * @return bool
     */
    private function some($p2, $dy, $dx)
    {
        return isset($this->coordinateMap[$p2->x - $dy][$p2->y + $dx]);
    }
}