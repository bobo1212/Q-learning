<?php

class Konto
{

    private int $kupujza;
    private int|float $posiadanesztuki = 0;
    private int $kosztyPozycji = 0;
    private float $totalZysk = 0;
    private int $openCount = 0;
    private int $closeCount = 0;

    public function __construct(
        private float $kasa,
        private int $split
    )
    {
        $this->kupujza = (int)($this->kasa / $this->split);
    }

    public function openLong(array $w)
    {
        if ($this->kasa < $this->kupujza) {
            return;
        }
        $this->openCount++;
        // echo 'Kupiono1  ' . $this->kupujza . ' ' . $this->kasa . "\n";
        $this->kasa -= $this->kupujza;
        $this->posiadanesztuki += $this->kupujza / $w['ask'];
        $this->kosztyPozycji += $this->kupujza;
        // echo 'Kupiono2  ' . $this->kupujza . ' ' . $this->kasa . "\n";
    }

    public function closeLong(array $w): float
    {
        if ($this->posiadanesztuki == 0) {
            return 0;
        }
        $przychod = $this->posiadanesztuki * $w['bid'];
        $this->kasa += $przychod;

        $zysk = $przychod - $this->kosztyPozycji;
        $this->posiadanesztuki = 0;
        $this->kosztyPozycji = 0;
        $this->totalZysk += $zysk;
        $this->closeCount++;
        return $zysk;
    }

    public function getTotalZysk(): float
    {
        return $this->totalZysk;
    }

    public function getKasa(): float
    {
        return $this->kasa;
    }

    public function getOpenCount()
    {
        return $this->openCount;
    }
    public function getCloseCount()
    {
        return $this->closeCount;
    }
    //$closeCount
}