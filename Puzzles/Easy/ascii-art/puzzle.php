<?php

fscanf(STDIN, "%d",
    $L
);
fscanf(STDIN, "%d",
    $H
);
$T = stream_get_line(STDIN, 256, "\n");
$aChars = range('A', 'Z');
for ($i = 0; $i < $H; $i++)
{
    $ROW = stream_get_line(STDIN, 1024, "\n");
    $aLetters[] = str_split($ROW, $L);
}
$charT = str_split($T);
$answer = null;
for ($i = 0; $i < $H; $i++)
{
    foreach ($charT as $char) {
        $iLetters = array_search(strtoupper($char), $aChars);
        if ($iLetters === false) {
            $answer .= end($aLetters[$i]);
        } else {
            $answer .= $aLetters[$i][$iLetters];
        }
    }
    $answer .= "\n";
}

echo("$answer\n");
