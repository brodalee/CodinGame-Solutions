<?php

use PHPUnit\Framework\TestCase;

class GlobalTest extends TestCase
{
    /**
     * Teste la méthode getBaconNumber sur tous les fichiers d'entrée/sortie fournis.
     */
    public function testBaconNumbersFromFiles()
    {
        $testCases = [
            1 => 2,
            2 => 1,
            3 => 3,
            4 => 0,
            5 => 6,
            6 => 4,
        ];
        foreach ($testCases as $num => $expected) {
            $inputFile = __DIR__ . "/inputs/test{$num}.txt";
            $lines = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $actorName = array_shift($lines);
            $n = (int)array_shift($lines);
            $movieCasts = array_slice($lines, 0, $n);
            $result = \App\Game::getBaconNumber($actorName, $movieCasts);
            $this->assertSame($expected, $result, "Echec sur test{$num}.txt");
        }
    }

}