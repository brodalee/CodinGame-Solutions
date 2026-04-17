<?php

fscanf(STDIN, "%d %d",
    $W,
    $H
);
fscanf(STDIN, "%d",
    $N
);
fscanf(STDIN, "%d %d",
    $X0,
    $Y0
);
$minx = $miny  = 0;
$maxx = $W - 1;
$maxy = $H - 1;

while (TRUE)
{
    fscanf(STDIN, "%s",
        $bombDir
    );
    if(strpos($bombDir, "U")!==false)
        $maxy = $Y0 - 1;
    elseif (strpos($bombDir, "D")!==false)
        $miny = $Y0 + 1;
    if(strpos($bombDir, "L")!==false)
        $maxx = $X0 - 1;
    elseif (strpos($bombDir, "R")!==false)
        $minx = $X0 + 1;
    $X0 = $minx + ceil(($maxx  - $minx)/2);
    $Y0 = $miny + ceil(($maxy  - $miny)/2);
    echo("$X0 $Y0\n");
}