<?php

fscanf(STDIN, "%d", $N );
$TEMPS=explode(" ", stream_get_line(STDIN, 256, "\n"));
$N == 0 ? $nearest = 0 : $nearest = $TEMPS[0];
foreach($TEMPS as $temp)
{
    if(abs($nearest) > abs($temp) || (abs($nearest) == abs($temp) && $temp > 0)) $nearest = $temp;
}
echo($nearest . "\n");