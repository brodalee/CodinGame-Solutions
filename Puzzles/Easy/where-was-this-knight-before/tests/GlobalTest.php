<?php

use PHPUnit\Framework\TestCase;

class GlobalTest extends TestCase
{
    public function testAllSamples()
    {
        $testCases = [
            1, 2, 3, 4, 5, 6
        ];
        foreach ($testCases as $num) {
            $input = file(__DIR__ . "/inputs/test{$num}.txt", FILE_IGNORE_NEW_LINES);
            $output = file(__DIR__ . "/outputs/test{$num}.txt", FILE_IGNORE_NEW_LINES);
            $pieces = array_shift($input);
            $before = array_slice($input, 0, 8);
            $after = array_slice($input, 8, 8);
            list($from, $to, $pieceMoved, $capture) = \App\Game::findMove($before, $after, $pieces);
            $notation = \App\Game::coordToNotation($from) . ($capture ? 'x' : '-') . \App\Game::coordToNotation($to);
            $isKnight = \App\Game::isKnightMove($from, $to, $pieceMoved);
            $expectedNotation = trim($output[0]);
            $expectedType = trim($output[1]);
            $this->assertEquals($expectedNotation, $notation, "Erreur sur le test $num (notation)");
            $this->assertEquals($expectedType, $isKnight ? 'Knight' : 'Other', "Erreur sur le test $num (type)");
        }
    }
}