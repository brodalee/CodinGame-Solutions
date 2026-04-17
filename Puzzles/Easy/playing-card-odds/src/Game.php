<?php

namespace App;

class Game
{
    const RANKS = ['2', '3', '4', '5', '6', '7', '8', '9', 'T', 'J', 'Q', 'K', 'A'];
    const SUITS = ['C', 'D', 'H', 'S'];

    public function start()
    {
        fscanf(STDIN, "%d %d", $R, $S);
        $removed = [];
        for ($i = 0; $i < $R; $i++) {
            $removed[] = trim(stream_get_line(STDIN, 15 + 1, "\n"));
        }

        $sought = [];
        for ($i = 0; $i < $S; $i++) {
            $sought[] = trim(stream_get_line(STDIN, 15 + 1, "\n"));
        }

        $result = $this->computeOdds($removed, $sought);
        echo($result . "\n");
    }

    public function computeOdds(array $removed, array $sought): string
    {
        $allCards = $this->generateDeck();
        $deck = array_fill_keys($allCards, true);

        foreach ($removed as $rem) {
            $toRemove = $this->parseClassification($rem);
            foreach ($toRemove as $card) {
                unset($deck[$card]);
            }
        }

        $remaining = array_keys($deck);
        $totalRemaining = count($remaining);

        $matching = [];
        foreach ($sought as $s) {
            $cards = $this->parseClassification($s);
            foreach ($cards as $card) {
                if (isset($deck[$card])) {
                    $matching[$card] = true;
                }
            }
        }

        $countMatching = count($matching);
        $percentage = round(($countMatching / $totalRemaining) * 100);
        return $percentage . "%";
    }

    private function generateDeck(): array
    {
        $cards = [];
        foreach (self::RANKS as $rank) {
            foreach (self::SUITS as $suit) {
                $cards[] = $rank . $suit;
            }
        }
        return $cards;
    }

    private function parseClassification(string $str): array
    {
        $ranks = [];
        $suits = [];
        $chars = str_split($str);
        foreach ($chars as $char) {
            if (in_array($char, self::RANKS)) {
                $ranks[] = $char;
            } elseif (in_array($char, self::SUITS)) {
                $suits[] = $char;
            }
        }
        $ranks = array_unique($ranks);
        $suits = array_unique($suits);

        if (empty($ranks)) {
            $ranks = self::RANKS;
        }
        if (empty($suits)) {
            $suits = self::SUITS;
        }

        $cards = [];
        foreach ($ranks as $rank) {
            foreach ($suits as $suit) {
                $cards[] = $rank . $suit;
            }
        }
        return $cards;
    }
}