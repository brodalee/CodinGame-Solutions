<?php

namespace App;

class Letter
{
    public $index; // column
    public $line; // line
    public $letter;

    public function __construct($line, $column, $letter)
    {
        $this->line = $line;
        $this->index = $column;
        $this->letter = $letter;
    }
}