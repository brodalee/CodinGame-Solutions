<?php

namespace App;

class Game
{
    private $letters = 'abcdefghijklmnopqrstuvwxyz';
    public $lettersByLine = [];

    public function start()
    {
        fscanf(STDIN, "%d", $n);
        for ($i = 0; $i < $n; $i++) {
            fscanf(STDIN, "%s", $m);
            $this->lettersByLine[] = $m;
        }

        $result = $this->getResult();
        echo $result;
    }

    public function getResult()
    {
        $letters = str_split($this->letters);
        $lettersPositions = [];

        foreach ($letters as $letter) {
            $lettersPositions[$letter] = $this->findAllOccurrenceOf($letter);
        }

        if (count($lettersPositions) !== strlen($this->letters)) {
            throw new \Exception('All instance has not been found');
        }

        $this->checkIfOneLetterHasNoPosition($lettersPositions);

        if ($this->lettersHasOnlyOnePosition($lettersPositions)) {
            return $this->parse($lettersPositions);
        }

        return $this->parse($this->findPath($lettersPositions));
    }

    /**
     * @param array<string, Letter[]> $positions
     * @return array
     */
    private function findPath($positions)
    {
        $path = [];

        foreach ($positions['a'] as $position) {
            $path = [$position];
            if ($this->canDoCompletePath($position, $positions, $path)) {
                return $path;
            }
        }

        return $path;
    }

    private function canDoCompletePath($position, $defaultPositions, &$path)
    {
        $nextLetter = $this->getNextLetter($position->letter);
        if (!$nextLetter) {
            return true; // z === fin
        }

        foreach ($defaultPositions[$nextLetter] as $nextPosition) {
            if ($this->isAdjacent($position, $nextPosition)) {
                if ($this->canDoCompletePath($nextPosition, $defaultPositions, $path)) {
                    $path[] = $nextPosition;
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param Letter $pos1
     * @param Letter $pos2
     * @return Bool
     */
    private function isAdjacent($pos1, $pos2)
    {
        // Si pos1 est à droite de pos2.
        if ($pos1->line === $pos2->line && $pos1->index - 1 === $pos2->index) {
            return true;
        }

        // Si pos1 est à gauche de pos2.
        if ($pos1->line === $pos2->line && $pos1->index + 1 === $pos2->index) {
            return true;
        }

        // Si pos1 est dessous de pos2.
        if ($pos1->index === $pos2->index && $pos1->line - 1 === $pos2->line) {
            return true;
        }

        // Si pos1 est dessus de pos2.
        if ($pos1->index === $pos2->index && $pos1->line + 1 === $pos2->line) {
            return true;
        }

        return false;
    }

    /**
     * @param $currentLetter
     * @return string|null
     */
    private function getNextLetter($currentLetter)
    {
        if ($currentLetter === 'z') {
            return null;
        }

        $asciiValue = ord($currentLetter);
        return chr($asciiValue + 1);
    }


    private function lettersHasOnlyOnePosition($lettersPositions)
    {
        foreach ($lettersPositions as $letter => $positions) {
            if (count($positions) > 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<string, Letter[]> $lettersPositions
     * @return void
     */
    private function checkIfOneLetterHasNoPosition($lettersPositions)
    {
        foreach ($lettersPositions as $letter => $positions) {
            if (count($positions) === 0) {
                throw new \Exception("Letter $letter has no position found");
            }
        }
    }

    /**
     * @param $currentLetter
     * @return Letter[]
     */
    private function findAllOccurrenceOf($currentLetter)
    {
        $occurrences = [];
        foreach ($this->lettersByLine as $lineNumber => $line) {
            $letters = str_split($line, 1);
            foreach ($letters as $column => $letter) {
                if ($letter === $currentLetter) {
                    $occurrences[] = new Letter($lineNumber, $column, $currentLetter);
                }
            }
        }

        return $occurrences;
    }

    /**
     * @param Letter[] $positions
     * @return string
     */
    private function parse($positions)
    {
        $lines = [];
        foreach ($this->lettersByLine as $lineNumber => $lettersByLine) {
            $lines[$lineNumber] = [];
            $count = count(str_split($lettersByLine, 1)) - 1;
            for ($i = 0; $i <= $count; $i++) {
                $lines[$lineNumber][$i] = '-';
            }
        }

        foreach ($positions as $letterPosition) {
            $lines[$letterPosition->line][$letterPosition->index] = $letterPosition->letter;
        }

        $str = '';
        foreach ($lines as $index => $line) {
            $str .= join('', $line);
            if ($index !== count($lines) - 1) {
                $str .= "\n";
            }
        }

        return str_replace(' ', '', $str);
    }
}