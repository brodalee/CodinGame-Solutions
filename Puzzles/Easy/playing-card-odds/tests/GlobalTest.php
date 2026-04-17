<?php

use PHPUnit\Framework\TestCase;

class GlobalTest extends TestCase
{
    public function test1()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test1.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test1.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test2()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test2.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test2.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test3()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test3.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test3.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test4()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test4.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test4.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test5()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test5.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test5.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test6()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test6.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test6.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test7()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test7.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test7.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test8()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test8.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test8.txt'));
        $this->assertEquals($expected, $result);
    }

    public function test9()
    {
        $game = new \App\Game();
        $input = file_get_contents(__DIR__ . '/inputs/test9.txt');
        $lines = explode("\n", trim($input));
        list($R, $S) = explode(' ', $lines[0]);
        $removed = array_slice($lines, 1, $R);
        $sought = array_slice($lines, 1 + $R, $S);
        $result = $game->computeOdds($removed, $sought);
        $expected = trim(file_get_contents(__DIR__ . '/outputs/test9.txt'));
        $this->assertEquals($expected, $result);
    }
}