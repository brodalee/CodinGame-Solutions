<?php

namespace App;

class Game
{
    public function start()
    {
        $pieces = stream_get_line(STDIN, 256 + 1, "\n");
        $before = [];
        $after = [];
        for ($i = 0; $i < 8; $i++) {
            $before[] = stream_get_line(STDIN, 8 + 1, "\n");
        }
        for ($i = 0; $i < 8; $i++) {
            $after[] = stream_get_line(STDIN, 8 + 1, "\n");
        }
        list($from, $to, $pieceMoved, $capture) = self::findMove($before, $after, $pieces);
        $notation = self::coordToNotation($from) . ($capture ? 'x' : '-') . self::coordToNotation($to);
        $isKnight = self::isKnightMove($from, $to, $pieceMoved);
        echo($notation . "\n");
        echo($isKnight ? "Knight\n" : "Other\n");
    }

    public static function findMove($before, $after, $pieces)
    {
        $from = null;
        $to = null;
        $pieceMoved = null;
        $capture = false;
        $beforeMap = [];
        $afterMap = [];
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $b = $before[$y][$x];
                $a = $after[$y][$x];
                $beforeMap[$y][$x] = $b;
                $afterMap[$y][$x] = $a;
            }
        }

        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $b = $beforeMap[$y][$x];
                $a = $afterMap[$y][$x];
                if ($b !== $a) {
                    if (self::isPiece($b, $pieces) && !self::isPiece($a, $pieces)) {
                        $from = [$x, $y];
                        $pieceMoved = $b;
                    }
                    if (!self::isPiece($b, $pieces) && self::isPiece($a, $pieces)) {
                        $to = [$x, $y];
                        $pieceMoved = $a;
                    }
                    if (self::isPiece($b, $pieces) && self::isPiece($a, $pieces) && strtolower($b) !== strtolower($a)) {
                        $to = [$x, $y];
                        $pieceMoved = $a;
                        $capture = true;
                    }
                }
            }
        }
        if ($from === null) {
            for ($y = 0; $y < 8; $y++) {
                for ($x = 0; $x < 8; $x++) {
                    $b = $beforeMap[$y][$x];
                    $a = $afterMap[$y][$x];
                    if (self::isPiece($b, $pieces) && !self::isPiece($a, $pieces)) {
                        $from = [$x, $y];
                        $pieceMoved = $b;
                    }
                }
            }
        }
        return [$from, $to, $pieceMoved, $capture];
    }

    public static function isPiece($char, $pieces)
    {
        return ctype_alpha($char) && (stripos($pieces, $char) !== false || stripos($pieces, strtolower($char)) !== false);
    }

    public static function coordToNotation($coord)
    {
        $letters = 'abcdefgh';
        $x = $coord[0];
        $y = $coord[1];
        return $letters[$x] . (8 - $y);
    }

    public static function isKnightMove($from, $to, $pieceMoved)
    {
        $dx = abs($from[0] - $to[0]);
        $dy = abs($from[1] - $to[1]);
        if (strtolower($pieceMoved) === 'n') {
            return true;
        }
        return ($dx === 2 && $dy === 1) || ($dx === 1 && $dy === 2);
    }
}