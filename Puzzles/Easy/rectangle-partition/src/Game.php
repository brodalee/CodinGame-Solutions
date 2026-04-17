<?php

namespace App;

class Game
{
    public $width;
    public $height;

    public $arrayOfX = [];
    public $arrayOfY = [];

    public $countX;
    public $countY;

    public function start()
    {
        fscanf(STDIN, "%d %d %d %d", $w, $h, $countX, $countY);
        $this->width = $w;
        $this->height = $h;
        $this->countX = $countX;
        $this->countY = $countY;

        $inputs = explode(" ", fgets(STDIN));
        for ($i = 0; $i < $countX; $i++)
        {
            $this->arrayOfX[] = intval($inputs[$i]);
        }
        $this->arrayOfX[] = $w;

        $inputs = explode(" ", fgets(STDIN));
        for ($i = 0; $i < $countY; $i++)
        {
            $this->arrayOfY[] = intval($inputs[$i]);
        }
        $this->arrayOfY[] = $h;

        Debugger::debug($this->arrayOfY, $this->arrayOfX, $w, $h, $countX, $countY);
        $result = $this->getResult();
        echo "$result\n";
    }

    /**
     * @return int
     */
    public function getResult()
    {
        for ($i = 0; $i < $this->countX; $i++) {
            for ($j = $i+1; $j <= $this->countX; $j++) {
                $this->arrayOfX[] = $this->arrayOfX[$j] - $this->arrayOfX[$i];
            }
        }

        for ($i = 0; $i < $this->countY; $i++) {
            for ($j = $i+1; $j <= $this->countY; $j++) {
                $this->arrayOfY[] = $this->arrayOfY[$j] - $this->arrayOfY[$i];
            }
        }

        sort($this->arrayOfX);
        sort($this->arrayOfY);


        $totalCount = 0;
        foreach ($this->arrayOfX as $x) {
            foreach ($this->arrayOfY as $y) {
                if ($y == $x) {
                    $totalCount++;
                } else if ($x < $y) {
                    break;
                }
            }
        }

        return $totalCount;
    }
}