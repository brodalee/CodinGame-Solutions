<?php

while (TRUE){
    $a = 0;
    $b = 0;
    for ($i = 0; $i < 8; $i++){
        fscanf(STDIN, "%d",
            $mountainH
        );
        if($mountainH > $a){
            $b = $i;
            $a = $mountainH;
        }
    }

    echo("{$b}\n"); // The number of the mountain to fire on.
}