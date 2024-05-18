<?php

class Gra
{
    private $rewards = [
        [-100, -100, -100, -100, -100, 100, -100, -100, -100, -100, -100],
        [-100, -1, -1, -1, -1, -1, -1, -1, -1, -1, -100],
        [-100, -1, -100, -100, -100, -100, -100, -1, -100, -1, -100],
        [-100, -1, -1, -1, -1, -1, -1, -1, -100, -1, -100],
        [-100, -100, -100, -1, -100, -100, -100, -1, -100, -100, -100],
        [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1],
        [-100, -100, -100, -100, -100, -1, -100, -100, -100, -100, -100],
        [-100, -1, -1, -1, -1, -1, -1, -1, -1, -1, -100],
        [-100, -100, -100, -1, -100, -100, -100, -1, -100, -100, -100],
        [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1],
        [-100, -100, -100, -100, -100, -100, -100, -100, -100, -100, -100]
    ];
    private array $actions = ['up', 'right', 'down', 'left'];
    public array $q_values = [];
    private int $iloscStanowSwiata = 0;

    private $stanSwiata = [];
    private Swiat $swiat;


    public function __construct(Swiat $swiat)
    {
        $this->swiat = $swiat;
        foreach ($this->rewards as $k1 => $row) {
            foreach ($row as $k2 => $v) {
                foreach ($this->actions as $k3 => $a) {
                    $this->q_values[$k1][$k2][$k3] = 0;
                }
            }
        }
    }

    public function getStanySwiataCount()
    {
        return $this->rewards;
        //return count($this->rewards);
    }

    public function getActionCount()
    {
        return $this->actions;
        //return count($this->actions);
    }


    public function resetujGre()
    {
        $current_row_index = (int)array_rand($this->rewards, 1);
        $current_column_index = (int)array_rand($this->rewards[0], 1);

        while ($this->czy_gra_dobiegla_konca($current_row_index, $current_column_index)) {
            $current_row_index = array_rand($this->rewards, 1);
            $current_column_index = array_rand($this->rewards[0], 1);
        }
        $this->stanSwiata = [$current_row_index, $current_column_index];
    }

    public function pobierz_stan_swiata()
    {
        return $this->swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata($this->stanSwiata);
        return $this->stanSwiata;
    }

    public function usraw_stan_swiata($current_row_index, $current_column_index)
    {
        $this->stanSwiata = [$current_row_index, $current_column_index];
    }

    public function czy_koniec_gry(): bool
    {
        if ($this->rewards[$this->stanSwiata[0]][$this->stanSwiata[1]] == -1) {
            return False;
        } else {
            return True;
        }
    }

    public function czy_gra_dobiegla_konca($current_row_index, $current_column_index): bool
    {
        if ($this->rewards[$current_row_index][$current_column_index] == -1) {
            return False;
        } else {
            return True;
        }
    }

    public function wykonaj_akcje($action_index)
    {
        $current_row_index = $this->stanSwiata[0];
        $current_column_index = $this->stanSwiata[1];
        $new_row_index = $current_row_index;
        $new_column_index = $current_column_index;
        if ($this->actions[$action_index] == 'up' && $current_row_index > 0) {
            $new_row_index -= 1;
        } elseif ($this->actions[$action_index] == 'right' && $current_column_index < 11 - 1) {
            $new_column_index += 1;
        } elseif ($this->actions[$action_index] == 'down' && $current_row_index < 11 - 1) {
            $new_row_index += 1;
        } elseif ($this->actions[$action_index] == 'left' && $current_column_index > 0) {
            $new_column_index -= 1;
        }
        $this->stanSwiata = [$new_row_index, $new_column_index];
    }

    public function get_shortest_path(QTable  $qTable)
    {

        $start_row_index = $this->stanSwiata[0];
        $start_column_index = $this->stanSwiata[1];
        if ($this->czy_gra_dobiegla_konca($start_row_index, $start_column_index)) {
            return [];
        } else {
            $current_row_index = $start_row_index;
            $current_column_index = $start_column_index;
            $shortest_path = [];
            $shortest_path[] = [$current_row_index, $current_column_index];
            while (!$this->czy_gra_dobiegla_konca($current_row_index, $current_column_index)) {
                $id = $this->swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata([$current_row_index, $current_column_index]);
                $action_index = $qTable->get_next_action($id, 1);
                $this->wykonaj_akcje($action_index);
                $a = $this->pobierz_stan_swiata();
                $current_row_index = $this->stanSwiata[0];
                $current_column_index = $this->stanSwiata[1];
                $shortest_path[] = [$current_row_index, $current_column_index];
            }
            return $shortest_path;
        }
    }

    public function get_next_action($stanSwiata, $epsilon)
    {
        #if a randomly chosen value between 0 and 1 is less than epsilon,
        #then choose the most promising value from the Q-table for this state.
        if ($this->rand_float() < $epsilon) {
            $maxValue = max($this->q_values[$stanSwiata[0]][$stanSwiata[1]]);
            return array_keys($this->q_values[$stanSwiata[0]][$stanSwiata[1]], $maxValue)[0];
        } else {
            return random_int(0, 3);
        }

    }

    public function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
    {
        // Check if the start number is greater than the end number
        if ($st_num > $end_num) {
            return false; // Return false if start number is greater than end number
        }
        // Generate a random integer between the multiplied start and end numbers,
        // then divide it by the multiplication factor to get a random float value
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
    }

    public function getRevard()
    {
        return $this->rewards[$this->stanSwiata[0]][$this->stanSwiata[1]];
    }
}

class QTable
{
    private array $q_values = [];

    public function __construct($rewards, $actions)
    {
        //$this->q_values = array_fill(0, $iloscStanowSwiata * $iloscAkcji, array_fill(0, $iloscAkcji, 0));
        $lp = 0;
        foreach ($rewards as $k1 => $row) {
            foreach ($row as $k2 => $v) {

                $this->q_values[$lp] = [0, 0, 0, 0];
                $lp++;

            }
        }

    }

    public function lenr($s1Tmp, $s1, $action_index, $reward)
    {
        $epsilon = 0.9;  # the percentage of time when we should take the best action (instead of a random action)
        $discount_factor = 0.9;  # discount factor for future rewards
        $learning_rate = 0.9;  # the rate at which the AI agent should learn

        $old_q_value = $this->q_values[$s1Tmp][$action_index];
        $max_q_value = max($this->q_values[$s1]);
        $temporal_difference = $reward + ($discount_factor * $max_q_value) - $old_q_value;
        $new_q_value = $old_q_value + ($learning_rate * $temporal_difference);
        $this->q_values[$s1Tmp][$action_index] = $new_q_value;
    }

    public function get_next_action($stanSwiata, $epsilon)
    {
        #if a randomly chosen value between 0 and 1 is less than epsilon,
        #then choose the most promising value from the Q-table for this state.
        if ($this->rand_float() < $epsilon) {
            $maxValue = max($this->q_values[$stanSwiata]);
            return array_keys($this->q_values[$stanSwiata], $maxValue)[0];
        } else {
            return random_int(0, 3);
        }

    }

    public function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
    {
        // Check if the start number is greater than the end number
        if ($st_num > $end_num) {
            return false; // Return false if start number is greater than end number
        }
        // Generate a random integer between the multiplied start and end numbers,
        // then divide it by the multiplication factor to get a random float value
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
    }
}

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
        foreach ($this->cechyswiata as $cecha) {
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


function gox()
{
    $swiat = new Swiat();
    $swiat->add(new CechaSwiata(0, 11, 11));//w8
    $swiat->add(new CechaSwiata(0, 11, 11));//pozycja otwarta zamknieta

    $gra = new Gra($swiat);
    $epsilon = 0.9;  # the percentage of time when we should take the best action (instead of a random action)
    $discount_factor = 0.9;  # discount factor for future rewards
    $learning_rate = 0.9;  # the rate at which the AI agent should learn


    $qTable = new QTable($gra->getStanySwiataCount(), $gra->getActionCount());

    for ($episode = 0; $episode < 1000; $episode++) {
        $gra->resetujGre();
        $s1 = $gra->pobierz_stan_swiata();

        //echo('start $episode ' . $episode . "\n");
        while (!$gra->czy_koniec_gry()) {
            $action_index = $qTable->get_next_action($s1, $epsilon);
            $s1Tmp = $s1;
            $gra->wykonaj_akcje($action_index);
            $s1 = $gra->pobierz_stan_swiata();
            $reward = $gra->getRevard();
            $qTable->lenr($s1Tmp, $s1, $action_index, $reward);
        }
    }
    $gra->usraw_stan_swiata(5, 2);
    $path = $gra->get_shortest_path($qTable); #go to row 5, column 2
    $path = array_reverse($path);
    foreach ($path as $row) {
        echo '[' . $row[0] . ', ' . $row[1] . '], ';
    }
    echo "\n";
}


gox();