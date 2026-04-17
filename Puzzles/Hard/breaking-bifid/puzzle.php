<?php
$plainText1 = trim(stream_get_line(STDIN, 500, "\n"));
$cipherText1 = trim(stream_get_line(STDIN, 500, "\n"));
$cipherText2 = trim(stream_get_line(STDIN, 500, "\n"));

function preprocess($s) {
    return str_replace('J', 'I', str_replace(' ', '', strtoupper($s)));
}

function li($ch) { $o = ord($ch) - 65; return $o > 9 ? $o - 1 : $o; }
function il($i) { return chr(65 + ($i >= 9 ? $i + 1 : $i)); }

$p1 = preprocess($plainText1);
$c1 = preprocess($cipherText1);
$c2 = preprocess($cipherText2);
$n = strlen($p1);

$par = range(0, 49);
function find($x) { global $par; while ($par[$x]!=$x) { $par[$x]=$par[$par[$x]]; $x=$par[$x]; } return $x; }
function unite($a,$b) { global $par; $a=find($a);$b=find($b); if($a!=$b) $par[$b]=$a; }

for ($j = 0; $j < $n; $j++) {
    $ci = li($c1[$j]);
    for ($d = 0; $d < 2; $d++) {
        $pos = 2*$j + $d;
        if ($pos < $n) {
            $pi = li($p1[$pos]);
            unite($ci*2+$d, $pi*2);
        } else {
            $pi = li($p1[$pos - $n]);
            unite($ci*2+$d, $pi*2+1);
        }
    }
}

$need = array_fill(0, 25, false);
for ($i=0;$i<strlen($p1);$i++) $need[li($p1[$i])]=true;
for ($i=0;$i<strlen($c1);$i++) $need[li($c1[$i])]=true;
for ($i=0;$i<strlen($c2);$i++) $need[li($c2[$i])]=true;

$coords = array_fill(0, 25, null);
$grid = array_fill(0, 25, -1);
$classVal = [];

$order = [];
foreach (range(0,24) as $l) { if ($need[$l]) $order[] = $l; }
foreach (range(0,24) as $l) { if (!$need[$l]) $order[] = $l; }

$needCount = count(array_filter($need));

function solve($idx) {
    global $order, $coords, $grid, $classVal, $needCount;
    if ($idx >= 25) return tryDecrypt();
    // If all needed letters assigned, try decrypt
    if ($idx >= $needCount) {
        $res = tryDecrypt();
        if ($res !== null) return $res;
        return null;
    }
    
    $let = $order[$idx];
    $rr = find($let*2);
    $cr = find($let*2+1);
    $rf = isset($classVal[$rr]) ? $classVal[$rr] : -1;
    $cf = isset($classVal[$cr]) ? $classVal[$cr] : -1;
    
    $rows = $rf >= 0 ? [$rf] : [0,1,2,3,4];
    $cols = $cf >= 0 ? [$cf] : [0,1,2,3,4];
    
    foreach ($rows as $r) {
        foreach ($cols as $c) {
            $gc = $r*5+$c;
            if ($grid[$gc] >= 0) continue;
            
            $grid[$gc] = $let;
            $coords[$let] = [$r, $c];
            $sr = $rf < 0; $sc = $cf < 0;
            if ($sr) $classVal[$rr] = $r;
            if ($sc) $classVal[$cr] = $c;
            
            $res = solve($idx+1);
            if ($res !== null) return $res;
            
            $grid[$gc] = -1;
            $coords[$let] = null;
            if ($sr) unset($classVal[$rr]);
            if ($sc) unset($classVal[$cr]);
        }
    }
    return null;
}

function tryDecrypt() {
    global $coords, $grid, $c2;
    $n2 = strlen($c2);
    $seq = [];
    for ($i = 0; $i < $n2; $i++) {
        $idx = li($c2[$i]);
        if ($coords[$idx] === null) return null;
        $seq[] = $coords[$idx][0];
        $seq[] = $coords[$idx][1];
    }
    $plain = '';
    for ($i = 0; $i < $n2; $i++) {
        $r = $seq[$i];
        $c = $seq[$n2 + $i];
        $gc = $r*5+$c;
        if ($grid[$gc] < 0) return null;
        $plain .= il($grid[$gc]);
    }
    return $plain;
}

$result = solve(0);
echo $result . "\n";
