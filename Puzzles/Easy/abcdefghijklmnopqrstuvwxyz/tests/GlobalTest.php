<?php

class GlobalTest extends \PHPUnit\Framework\TestCase
{
    public function testStep1()
    {
        $this->step(1);
    }

    public function testStep2()
    {
        $this->step(2);
    }

    public function testStep3()
    {
        $this->step(3);
    }

    public function testStep4()
    {
        $this->step(4);
    }

    public function testStep5()
    {
        $this->step(5);
    }

    private function step($stepNumber)
    {
        $result = $this->getStepInput($stepNumber);
        $game = new \App\Game();
        foreach (explode("\n", $result) as $res) {
            $game->lettersByLine[] = $res;
        }

        $exp = str_split($this->getStepResult($stepNumber), 1);
        $res = str_split($game->getResult(), 1);

        foreach ($exp as $i => $ex) {
            $this->assertEquals($ex, $res[$i]);
        }
    }

    private function getStepResult($stepNumber)
    {
        return str_replace(
            "\r",
            '',
            file_get_contents(__DIR__ . '/steps/steps_' . $stepNumber . '_result.txt')
        );
    }

    private function getStepInput($stepNumber)
    {
        return str_replace(
            "\r",
            '',
            file_get_contents(__DIR__ . '/steps/steps_' . $stepNumber . '_input.txt')
        );
    }
}