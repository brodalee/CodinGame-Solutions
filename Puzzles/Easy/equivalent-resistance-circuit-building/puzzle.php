<?php
fscanf(STDIN, "%d", $N);
$resistors = [];
for ($i = 0; $i < $N; $i++) {
    fscanf(STDIN, "%s %d", $name, $R);
    $resistors[$name] = (float)$R;
}
$circuit = stream_get_line(STDIN, 500 + 1, "\n");

$tokens = explode(' ', trim($circuit));
$pos    = 0;

function parseExpr(array $tokens, int &$pos, array $resistors): float {
    $token = $tokens[$pos++];

    if ($token === '(') {
        $sum = 0.0;
        while ($tokens[$pos] !== ')') {
            $sum += parseExpr($tokens, $pos, $resistors);
        }
        $pos++;
        return $sum;

    } elseif ($token === '[') {
        $sumInv = 0.0;
        while ($tokens[$pos] !== ']') {
            $sumInv += 1.0 / parseExpr($tokens, $pos, $resistors);
        }
        $pos++;
        return 1.0 / $sumInv;

    } else {
        return $resistors[$token];
    }
}

$result = parseExpr($tokens, $pos, $resistors);

echo number_format($result, 1) . "\n";