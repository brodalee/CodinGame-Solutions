<?php

namespace App;

class Peg
{
    public $numberOfValidPositions;
    public $x;
    public $y;

    public function __construct($numberOfValidPositions, $x, $y)
    {
        $this->numberOfValidPositions = $numberOfValidPositions;
        $this->x = $x;
        $this->y = $y;
    }
}