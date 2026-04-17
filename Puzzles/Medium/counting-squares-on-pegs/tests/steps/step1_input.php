<?php

use App\Peg;

return [
    'pegs' => [
        new Peg(5,0,0),
        new Peg(5,0,2),
        new Peg(5,1,0),
        new Peg(5,2,0),
        new Peg(5,2,2),
    ],
    'numberOfValidPositions' => 5,
    'expectedResult' => 1
];