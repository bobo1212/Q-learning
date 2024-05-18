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

class Swiat
{
    private $cechyswiata = [];

    public function add(CechaSwiata $cechaSwiata)
    {
        $this->cechyswiata[] = $cechaSwiata;
    }

    public function pobierzKlucze(array $wartosciCech)
    {
        $klucze = [];
        foreach ($wartosciCech as $k => $v) {
            $klucze[] = $this->cechyswiata[$k]->getKey($v);
        }
        return $klucze;
    }

    private function pobierzRozmiary()
    {
        $size = [];
        foreach ($this->cechyswiata as  $cecha) {
            $size[] = $cecha->getSize();
        }
        return $size;
    }

    function pobierzUNIKALNYIdentyfikatorStanuSwiata(array $wartosciCech)
    {
        $klucze = $this->pobierzKlucze($wartosciCech);
        $ozmiary = $this->pobierzRozmiary();
        return $this->przygotujKluczStanuSwiata($ozmiary, $klucze);
    }

    function przygotujKluczStanuSwiata(array $sizes, array $keys)
    {
        if (count($sizes) != count($keys)) {
            throw new Exception('ZŁE ROZMIARY ' . count($sizes) . ' ' . count($keys));
        }
        foreach ($sizes as $k => $size) {
            if ($keys[$k] >= $size) {
                throw new Exception('lipa', $k, $keys[$k], $size);;
            }
        }
        $lp = 0;
        foreach ($keys as $keyPos => $key) {
            $sizePos = $keyPos - 1;
            $m = 1;
            if ($sizePos >= 0) {
                $m = array_product(array_slice($sizes, 0, $sizePos + 1));
            }
            $lp += ($key * $m);
        }
        return $lp;
    }
}

$swiat = new Swiat();
$swiat->add(new CechaSwiata(0, 11, 11));//w8
$swiat->add(new CechaSwiata(0, 11, 11));//pozycja otwarta zamknieta
$id = $swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata([0,0]);
var_dump($id);



