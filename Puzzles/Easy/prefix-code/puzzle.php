<?php

function decodePrefixCode($s, $codeToChar, &$charToCode = null) {
    $result = '';
    $i = 0;
    $len = strlen($s);
    $maxCodeLen = 0;
    foreach ($codeToChar as $code => $char) {
        $maxCodeLen = max($maxCodeLen, strlen($code));
        if ($charToCode !== null) $charToCode[$char] = $code;
    }
    while ($i < $len) {
        $found = false;
        for ($l = 1; $l <= $maxCodeLen && $i + $l <= $len; ++$l) {
            $sub = substr($s, $i, $l);
            if (isset($codeToChar[$sub])) {
                $result .= chr($codeToChar[$sub]);
                $i += $l;
                $found = true;
                break;
            }
        }
        if (!$found) {
            return "DECODE FAIL AT INDEX $i";
        }
    }
    return $result;
}

fscanf(STDIN, "%d", $n);
$codeToChar = [];
for ($i = 0; $i < $n; $i++) {
    fscanf(STDIN, "%s %d", $b, $c);
    $codeToChar[$b] = $c;
}
fscanf(STDIN, "%s", $s);

echo(decodePrefixCode($s, $codeToChar) . "\n");
