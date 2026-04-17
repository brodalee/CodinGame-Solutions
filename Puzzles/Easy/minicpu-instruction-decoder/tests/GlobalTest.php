<?php

use PHPUnit\Framework\TestCase;

class GlobalTest extends TestCase
{
    /**
     * Teste l'émulateur MiniCPU sur tous les couples input/output présents dans tests/inputs et tests/outputs.
     */
    public function testMiniCpuSamples()
    {
        $inputsDir = __DIR__ . '/inputs';
        $outputsDir = __DIR__ . '/outputs';
        $game = new \App\Game();
        $inputFiles = glob($inputsDir . '/*.txt');
        foreach ($inputFiles as $inputFile) {
            $basename = basename($inputFile);
            $outputFile = $outputsDir . '/' . $basename;
            if (!file_exists($outputFile)) {
                continue;
            }
            $input = trim(file_get_contents($inputFile));
            $expected = array_map('trim', explode("\n", trim(file_get_contents($outputFile))));
            $actual = $game->emulate($input);
            $actual = array_map('strval', $actual);
            $this->assertSame($expected, $actual, "Erreur sur $basename");
        }
    }
}