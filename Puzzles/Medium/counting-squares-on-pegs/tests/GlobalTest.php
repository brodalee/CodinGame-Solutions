<?php

class GlobalTest extends \PHPUnit\Framework\TestCase
{
    public function testStep1()
    {
        $this->assertNumberOfSquare(1);
    }

    public function testStep2()
    {
        $this->assertNumberOfSquare(2);
    }

    public function testStep3()
    {
        $this->assertNumberOfSquare(3);
    }

    private function assertNumberOfSquare($stepNumber)
    {
        $game = new \App\Game();
        $input = $this->getStepInput($stepNumber);

        $game->coordinates = $input['pegs'];
        $result = $game->getNumberOfSquares();

        $this->assertEquals($input['expectedResult'], $result);
    }

    private function getStepInput($stepNumber)
    {
        $path = __DIR__ . '/steps/step' . $stepNumber . '_input.php';
        return require_once $path;
    }
}