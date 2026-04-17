<?php


fscanf(STDIN, "%d",
    $surfaceN
);
for ($i = 0; $i < $surfaceN; $i++){
    fscanf(STDIN, "%d %d",
        $landX,
        $landY
    );
}

while (TRUE){
    fscanf(STDIN, "%d %d %d %d %d %d %d",
        $X,
        $Y,
        $hSpeed,
        $vSpeed,
        $fuel,
        $rotate,
        $power
    );

    if($vSpeed < -39){
        $power++;
    }else{
        $power--;
    }

    $power = ($power < 0 ? 0 : $power);
    $power = ($power > 4 ? 4 : $power);

    echo("0 {$power}\n");
}