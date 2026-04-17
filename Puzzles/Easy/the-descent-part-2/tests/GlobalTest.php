<?php

use PHPUnit\Framework\TestCase;

class GlobalTest extends TestCase
{
    public function testSampleInput1()
    {
        $this->testSample('test1');
    }

    public function testSampleInput2()
    {
        $this->testSample('test2');
    }

    public function testSampleInput3()
    {
        $this->testSample('test3');
    }

    public function testSampleInput4()
    {
        $this->testSample('test4');
    }

    public function testSampleInput5()
    {
        $this->testSample('test5');
    }

    public function testSampleInput6()
    {
        $this->testSample('test6');
    }

    public function testSampleInput7()
    {
        $this->testSample('test7');
    }

    public function testSampleInput8()
    {
        $this->testSample('test8');
    }

    public function testSampleInput9()
    {
        $this->testSample('test9');
    }

    public function testSampleInput10()
    {
        $this->testSample('test10');
    }

    private function testSample($testName)
    {
        $input = file_get_contents(__DIR__ . "/inputs/$testName.txt");
        $lines = array_map('trim', explode("\n", trim($input)));
        list($w, $h) = explode(' ', array_shift($lines));
        $grid = [];
        for ($i = 0; $i < $h; $i++) {
            $grid[] = array_map('intval', explode(' ', array_shift($lines)));
        }
        list($a, $b) = explode(' ', array_shift($lines));
        $t = intval(array_shift($lines));

        $game = new App\Game();
        $result = $game->findMinShots($grid, (int)$a, (int)$b, $t);
        $expected = trim(file_get_contents(__DIR__ . "/outputs/$testName.txt"));

        $this->assertEquals($expected, $result);
    }
}