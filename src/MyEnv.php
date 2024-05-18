<?php

class MyEnv
{


    private array $plansza;
    private int $posX;
    private int $posY;

    private int $poprzedniX;
    private int $poprzedniY;


    private $akcje;
    private int $i;
    private int $j;
    private int $tmpX;
    private int $tmpY;
    private $tmpAkcja;

    public function reset()
    {
        $this->i = 5;
        $this->j = 5;
        $this->plansza = [];
        for ($ii = 0; $ii <= $this->i; $ii++) {
            for ($jj = 0; $jj <= $this->j; $jj++) {
                $this->plansza[$ii][$jj] = '';
                $z = number_format(0, 1, '.', '');
                $z = str_pad($z, 4, " ", STR_PAD_LEFT);
                $this->akcje[$ii][$jj] = [$z, $z, $z, $z];
            }
        }
        $this->posX = 0;
        $this->posY = 0;
        $this->plansza[$this->posX][$this->posY] = ' X ';
    }

    private function wykonajAkcjePobierzNagrode($akcja)
    {
        if ($akcja == 0) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posX += 1;
            if ($this->posX > $this->i) {
                $this->posX = 0;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';

        } elseif ($akcja == 1) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posX -= 1;
            if ($this->posX < 0) {
                $this->posX = $this->i;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';
        } elseif ($akcja == 2) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posY += 1;
            if ($this->posY > $this->j) {
                $this->posY = 0;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';
        } elseif ($akcja == 3) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posY -= 1;
            if ($this->posY < 0) {
                $this->posY = $this->j;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';
        }
        if ($this->tmpAkcja !== null) {

            var_dump($this->posX . ' :: ' . $this->posY);
            $r = 0;
            if ($this->posX == 5 && $this->posY == 5) {
                $r = 1;
            }


            $tmpQ = $this->akcje[$this->tmpX][$this->tmpY][$this->tmpAkcja];

            $maxs = array_keys($this->akcje[$this->posX][$this->posY], max($this->akcje[$this->posX][$this->posY]));
            $maxKey = array_rand($maxs);
            $Q = $this->akcje[$this->posX][$this->posY][$maxs[$maxKey]];

            $wynik = $tmpQ + 0.9 * ($r + 0.6 * $Q - $tmpQ);
            if ($wynik != 0) {
                $this->akcje[$this->tmpX][$this->tmpY][$akcja] = $wynik;
            }

            // NAGRODA JUŻ PRZYPISANA DO AKCJI
//        $maxs = array_keys($this->akcje[$this->posX][$this->posY], max($this->akcje[$this->posX][$this->posY]));
//        $maxKey = array_rand($maxs);
//        $nagroda = $this->akcje[$this->posX][$this->posY][$maxs[$maxKey]];


            // MAGRODA GŁÓWNA ZA PODJĘTE DZIAŁANIE
            if ($this->posX == 5 && $this->posY == 5) {
//            //$this->akcje[$tmpX][$tmpY][$akcja] += $this->akcje[$tmpX][$tmpY][$akcja] * 0.8;   // ROSNIE CORAZ SZYBCIEJ
//            //$this->akcje[$tmpX][$tmpY][$akcja] += $this->akcje[$tmpX][$tmpY][$akcja] * 0.5; // ROSNIE SORAZ WOLNIEJ
//            $nagroda += 1;
//            //RESET POZYCJI
                $this->posX = 0;
                $this->posY = 0;
            }
        }
        $this->tmpX = $this->posX;
        $this->tmpY = $this->posY;
        $this->tmpAkcja = $akcja;
    }

    public function step($akcja)
    {
//        $tmpX = $this->posX;
//        $tmpY = $this->posY;
//
        $nagroda = $this->wykonajAkcjePobierzNagrode($akcja);
//
//
//        if ($nagroda != 0) {
//            $this->akcje[$tmpX][$tmpY][$akcja] = $nagroda * 0.99;
//            //$this->akcje[$tmpX][$tmpY][$akcja] += $nagroda * 0.1;
//        }
    }

    public
    function show()
    {
        // system('clear');
        echo '====================================================' . "\n";
        foreach ($this->plansza as $k => $row) {
            foreach ($row as $v) {
                echo '[' . str_pad($v, 3, ' ', STR_PAD_LEFT) . "]";
            }
            echo '';
            foreach ($this->akcje[$k] as $v) {
                echo '[' . str_pad(implode("|", $v), 15, ' ', STR_PAD_LEFT) . "] ";
            }
            echo "\n";
        }
        //usleep(500000);
        sleep(1);
    }

    function wybierzAkcje()
    {
        $randomInt = random_int(1, 100);
        if ($randomInt < 5) {
            //  return array_rand($this->akcje[$this->posX][$this->posY]);
        }
        $maxs = array_keys($this->akcje[$this->posX][$this->posY], max($this->akcje[$this->posX][$this->posY]));
        $maxKey = array_rand($maxs);
        return $maxs[$maxKey];
    }
}

$e = new MyEnv();
$e->reset();
$e->show();


$e->step(2);
$e->show();
$e->step(2);
$e->show();
$e->step(2);
$e->show();
$e->step(2);
$e->show();
$e->step(2);
$e->show();

$e->step(0);
$e->show();
$e->step(0);
$e->show();
$e->step(0);
$e->show();
$e->step(0);
$e->show();
$e->step(0);
$e->show();


$e->step(2);
$e->show();
$e->step(2);
$e->show();
$e->step(2);
$e->show();
$e->step(2);
$e->show();
$e->step(2);
$e->show();

$e->step(0);
$e->show();
$e->step(0);
$e->show();
$e->step(0);
$e->show();
$e->step(0);
$e->show();
$e->step(0);
$e->show();
$lp = 0;
while (true) {
    $e->step(2);
    $e->show();
    $e->step(2);
    $e->show();
    $e->step(2);
    $e->show();
    $e->step(2);
    $e->show();
    $e->step(2);
    $e->show();

    $e->step(0);
    $e->show();
    $e->step(0);
    $e->show();
    $e->step(0);
    $e->show();
    $e->step(0);
    $e->show();
    $e->step(0);
    $e->show();
    $lp++;
    if ($lp >= 20) {
        exit();
    }
}