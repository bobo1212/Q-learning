<?php

class Swiat
{
    private $cechyswiata = [];

    public function add(CechaSwiata $cechaSwiata)
    {
        $this->cechyswiata[] = $cechaSwiata;
    }

    public function pobierzUNIKALNYIdentyfikatorStanuSwiata(array $wartosciCech)
    {
        $klucze = $this->pobierzKlucze($wartosciCech);
        $ozmiary = $this->pobierzRozmiary();
        return $this->przygotujKluczStanuSwiata($ozmiary, $klucze);
    }

    public function porzerzLiczbeStanowSwiata(): int
    {
        return array_product($this->pobierzRozmiary());
    }

    private function przygotujKluczStanuSwiata(array $sizes, array $keys)
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

    private function pobierzKlucze(array $wartosciCech)
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
        foreach ($this->cechyswiata as $cecha) {
            $size[] = $cecha->getSize();
        }
        return $size;
    }
}

