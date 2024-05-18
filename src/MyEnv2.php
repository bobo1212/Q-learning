<?php

interface Game
{
    public function reset(): array;

    public function getDimensions(): array;

    public function getActions(): int;

    public function step($action): array;

    public function isTerminated(): bool;

    public function show(): void;
}

class ZnajdzNagrode implements Game
{
    private array $plansza;
    private int $posX;
    private int $posY;

    private int $poprzedniX;
    private int $poprzedniY;


    private $akcje;
    private int $i;
    private int $j;

    public function reset(): array
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

        return ['szerokosc' => $this->posX, 'wysokosc' => $this->posY];
    }

    public function getDimensions(): array
    {
        return [
            'szerokosc' => ['num_intervals' => 6, 'min_val' => 0, 'max_val' => 5],
            'wysokosc' => ['num_intervals' => 6, 'min_val' => 0, 'max_val' => 5]
        ];
    }

    public function getActions(): int
    {
        return 4;
    }

    public function step($action): array
    {
        if ($action == 0) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posX += 1;
            if ($this->posX > $this->i) {
                $this->posX = 0;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';

        } elseif ($action == 1) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posX -= 1;
            if ($this->posX < 0) {
                $this->posX = $this->i;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';
        } elseif ($action == 2) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posY += 1;
            if ($this->posY > $this->j) {
                $this->posY = 0;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';
        } elseif ($action == 3) {
            $this->plansza[$this->posX][$this->posY] = '';
            $this->posY -= 1;
            if ($this->posY < 0) {
                $this->posY = $this->j;
            }
            $this->plansza[$this->posX][$this->posY] = ' X ';
        }
        $terminated = false;
        $nagroda = 0;
        if ($this->posX == 5 && $this->posY == 5) {
            $nagroda = 1;
            $terminated = true;
        }


        return ['reward' => $nagroda, 'row' => ['szerokosc' => $this->posX, 'wysokosc' => $this->posY], 'terminated' => $terminated];
    }

    public function isTerminated(): bool
    {
        return false;
    }

    function show(): void
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
}

class MyEnv2
{
    private array $dimensions;
    private int $actions;
    private $stanySwiataAkcjaNagroda = [];

    private int $tmpIdStanu;
    private int|null $tmpIdAction = null;

    private Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->dimensions = $game->getDimensions();
        $this->actions = $game->getActions();

    }

    public function resetTablicyStanySwiataAkcjaNagroda()
    {
        $produst = array_product(array_column($this->dimensions, 'num_intervals'));
        $this->stanySwiataAkcjaNagroda = array_fill(0, $produst, array_fill(0, $this->actions, 0));
    }

    public function reset()
    {
        $row = $this->game->reset();
        $this->tmpIdStanu = $this->getIdStanuDlaDanejObserwacji($row);
        return $this->tmpIdStanu;
    }

    private function getIdStanuDlaDanejObserwacji(array $row)
    {
        if (count($row) != count($this->dimensions)) {
            throw new Exception('Definicja świata niezgodna z damumi opisującymi świat ' . count($row) . ' != ' . count($this->dimensions));
        }
        $intervalIds = [];
        $dimensions = [];
        foreach ($this->dimensions as $name => $value) {
            $intervalIds[] = $this->getIntervalId($value['min_val'], $value['max_val'], $value['num_intervals'], $row[$name]);
            $dimensions[] = $value['num_intervals'];
        }
        return $this->getIdStanu($intervalIds, $dimensions);
    }

    private function getIdStanu(array $indices, $dimensions): int
    {
        // Sprawdzamy, czy liczba indeksów i wymiarów jest zgodna
        if (count($indices) != count($dimensions)) { //"Liczba indeksów musi być równa liczbie wymiarów"
            throw new Exception('LIPA!!!');
        }

        foreach ($indices as $k => $v) {
            if ($v > $dimensions[$k] - 1) {
                throw new Exception('LIPA!!! INDEX POZA zAKRESEM zadurzy!! ' . $v);
            }
        }

        # Obliczamy indeks jednowymiarowy
        $index = 0;
        $product = 1;
        foreach (array_reverse($indices) as $i => $v) {
            $index += $indices[$i] * $product;
            $product *= $dimensions[$i];
        }
        return $index;
    }

    private function getIntervalId($min_val, $max_val, $num_intervals, $number)
    {
        if ($min_val >= $max_val) {
            throw new Exception("Niepoprawne dane wejsciowe");
        }

        if ($number < $min_val) {
            $number = $min_val;
        }
        if ($number > $max_val) {
            $number = $max_val;
        }

        $interval_range = $max_val - $min_val;
        $interval_size = $interval_range / $num_intervals;

        # Znalezienie indeksu przedziału dla danej liczby
        if ($number == $max_val) {
            # Przypadek szczególny dla maksymalnej wartości, by nie wyjść poza zakres
            $interval_index = $num_intervals - 1;
        } else {
            $interval_index = (int)(($number - $min_val) / $interval_size);
        }
        return $interval_index;
    }

    public function selectAction(int $idStanu)
    {
        $akcje = $this->stanySwiataAkcjaNagroda[$idStanu];
        $randomInt = random_int(1, 100);
        if ($randomInt < 5) {
            // return array_rand($akcje);
        }
        $maxs = array_keys($akcje, max($akcje));
        $maxKey = array_rand($maxs);
        return $maxs[$maxKey];
    }

    public function step(int $action)
    {
      //  echo 'SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS'."\n";
      //  echo 'akcja ' . $action ."\n";
        $stepInfo = $this->game->step($action);
        $nagrodaZaAkcje = $stepInfo['reward'];
       // echo 'nagroda za akcje: ' . $nagrodaZaAkcje ."\n";


        $idStanu = $this->getIdStanuDlaDanejObserwacji($stepInfo['row']);
       // echo 'id poprzedniedo stanu  : ' . $this->tmpIdStanu ."\n";
       // echo 'id aktualnrgo stanu po akcji : ' . $idStanu . '  ::  ' . json_encode($stepInfo['row'])."\n";

        if($idStanu == 24){
            //var_dump($this->stanySwiataAkcjaNagroda[$idStanu]);
        }
        $maxs = array_keys($this->stanySwiataAkcjaNagroda[$idStanu], max($this->stanySwiataAkcjaNagroda[$idStanu]));
        $maxKey = array_rand($maxs);
        $maxKey = $maxs[$maxKey];

        $nagroda = $this->stanySwiataAkcjaNagroda[$idStanu][$maxKey];
       // echo 'nagroda wszesniej posiadana: ' . $nagroda ."\n";
        $nagroda += $nagrodaZaAkcje;
        //var_dump($action);
        if ($this->stanySwiataAkcjaNagroda[$this->tmpIdStanu][$action] == 0) {
            $this->stanySwiataAkcjaNagroda[$this->tmpIdStanu][$action] = $nagroda * 0.99;
        }

        $this->tmpIdStanu = $idStanu;

       // echo '+++++++++++++++++++++++++++++++++++++++++++++'."\n";
        return $this->tmpIdStanu;
    }

    public function show()
    {
        $this->game->show();


        foreach ($this->stanySwiataAkcjaNagroda as $idStanu => $akcje) {
            $suma = array_sum($akcje);
            if ($suma) {
                var_dump($idStanu . ' ' . json_encode($akcje));
            }
        }
    }
}

$iloscEpizodow = 10;

$e2 = new MyEnv2(new ZnajdzNagrode());
$e2->resetTablicyStanySwiataAkcjaNagroda();

for ($i = 0; $i < $iloscEpizodow; $i++) {
    $state = $e2->reset();
    $e2->show();

    $lp = 0;
    while (true) {
        $e2->step(2);
        $e2->show();
        $e2->step(2);
        $e2->show();
        $e2->step(2);
        $e2->show();
        $e2->step(2);
        $e2->show();
        $e2->step(2);
        $e2->show();

        $e2->step(0);
        $e2->show();
        $e2->step(0);
        $e2->show();
        $e2->step(0);
        $e2->show();
        $e2->step(0);
        $e2->show();
        $e2->step(0);
        $e2->show();
        $state = $e2->reset();
        $e2->show();
        $lp++;
        if ($lp > 9) {
            break;
        }
    }
    echo "!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!" . "\n";
    while (true) {
        $action = $e2->selectAction($state);
        $state = $e2->step($action);
        $e2->show();
    }
}
















