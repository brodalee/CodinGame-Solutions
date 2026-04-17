<?php

fscanf(STDIN, "%d",
    $N
);
fscanf(STDIN, "%d",
    $Q
);

$arr = array();

for ($i = 0; $i < $N; $i++)
{
    fscanf(STDIN, "%s %s",
        $EXT,
        $MT
    );
    $arr[strtolower($EXT)] = $MT;
}

$FNAME = array();
for ($i = 0; $i < $Q; $i++)
{
    $FNAME[] = stream_get_line(STDIN, 500, "\n");
    $tmp = explode(".",$FNAME[$i]);
    $ext[] = array_pop($tmp);

}


for ($i = 0; $i < $Q; $i++)
{
    if (count(explode(".",$FNAME[$i])) > 1)
    {
        if(array_key_exists(strtolower($ext[$i]),$arr))
        {
            echo ($arr[strtolower($ext[$i])]."\n");
        }
        else echo ("UNKNOWN\n");

    } else echo ("UNKNOWN\n");

}