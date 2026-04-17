<?php

namespace App;

class Game
{
    /**
     * Permet d'émuler le programme MiniCPU à partir d'une chaîne d'octets hexadécimaux.
     * Utile pour les tests unitaires.
     * @param string $programLine
     * @return int[]
     */
    public function emulate($programLine)
    {
        $bytes = array_map(function($b) { return hexdec($b); }, preg_split('/\s+/', trim($programLine)));
        $registers = [0, 0, 0, 0];
        $i = 0;
        $len = count($bytes);
        while ($i < $len) {
            $opcode = $bytes[$i];
            switch ($opcode) {
                case 0x01: // MOV X V
                    $x = $bytes[$i+1];
                    $v = $bytes[$i+2];
                    $registers[$x] = $v & 0xFF;
                    $i += 3;
                    break;
                case 0x02: // ADD X Y
                    $x = $bytes[$i+1];
                    $y = $bytes[$i+2];
                    $registers[$x] = ($registers[$x] + $registers[$y]) & 0xFF;
                    $i += 3;
                    break;
                case 0x03: // SUB X Y
                    $x = $bytes[$i+1];
                    $y = $bytes[$i+2];
                    $registers[$x] = ($registers[$x] - $registers[$y]) & 0xFF;
                    if ($registers[$x] < 0) $registers[$x] += 256;
                    $i += 3;
                    break;
                case 0x04: // MUL X Y
                    $x = $bytes[$i+1];
                    $y = $bytes[$i+2];
                    $registers[$x] = ($registers[$x] * $registers[$y]) & 0xFF;
                    $i += 3;
                    break;
                case 0x05: // INC X
                    $x = $bytes[$i+1];
                    $registers[$x] = ($registers[$x] + 1) & 0xFF;
                    $i += 2;
                    break;
                case 0x06: // DEC X
                    $x = $bytes[$i+1];
                    $registers[$x] = ($registers[$x] - 1) & 0xFF;
                    if ($registers[$x] < 0) $registers[$x] += 256;
                    $i += 2;
                    break;
                case 0xFF: // HLT
                    $i = $len; // Sortie immédiate
                    break;
                default:
                    $i = $len;
                    break;
            }
        }
        return $registers;
    }

    public function start()
    {
        $program = stream_get_line(STDIN, 500 + 1, "\n"); // Space-separated hex bytes representing CPU instructions
        $registers = $this->emulate($program);

        foreach ($registers as $reg) {
            echo($reg . "\n");
        }
    }
}