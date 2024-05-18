<?php

class CechaSwiata
{
    private float $start;
    private float $dlugosc;
    private int $iloscPrzediaow;
    private float $rozmiarPrzedzialu;


    public function __construct(float $start, float $end, int $iloscPrzediaow)
    {
        $this->start = $start;
        $this->dlugosc = $end - $start;;
        $this->iloscPrzediaow = $iloscPrzediaow - 1;
        $this->rozmiarPrzedzialu = $this->dlugosc / $iloscPrzediaow;

        echo 'Start: 0 end ' . $this->dlugosc . ' iloscPrzediaow: ' . $iloscPrzediaow . ' rozmiar ' . $this->rozmiarPrzedzialu . "\n";
    }

    public function getKey(float $v)
    {
        $v -= $this->start;

        $key = (int)($v / $this->rozmiarPrzedzialu);
        if ($key < 0) {
            return 0;
        }
        if ($key > $this->iloscPrzediaow) {
            return $this->iloscPrzediaow;
        }
        return $key;
    }

    public function getNumerPrzedzialu(float $v)
    {
        return $this->getKey($v) + 1;
    }

    public function getSize()
    {
        return $this->iloscPrzediaow + 1;
    }
}
