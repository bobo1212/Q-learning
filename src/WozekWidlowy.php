<?php

require_once 'src/QLerningGameInterfae.php';

class WozekWidlowy implements QLerningGameInterfae
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


    private Swiat $swiat;


    public function __construct(Swiat $swiat)
    {
        $this->swiat = $swiat;
    }

    public function getStanySwiataCount():int
    {
        return count($this->rewards) * count($this->rewards[0]);
    }

    public function getActionCount():int
    {
        return count($this->actions);
    }


    public function resetujGre():void
    {
        $current_row_index = (int)array_rand($this->rewards, 1);
        $current_column_index = (int)array_rand($this->rewards[0], 1);

        while (!($this->rewards[$current_row_index][$current_column_index] == -1)) {
            $current_row_index = array_rand($this->rewards, 1);
            $current_column_index = array_rand($this->rewards[0], 1);
        }
        $this->stanSwiata = [$current_row_index, $current_column_index];
    }

    public function pobierz_stan_swiata():int
    {
        return $this->swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata($this->stanSwiata);
    }


    public function czy_koniec_gry(): bool
    {
        if ($this->rewards[$this->stanSwiata[0]][$this->stanSwiata[1]] == -1) {
            return False;
        } else {
            return True;
        }
    }

    public function wykonaj_akcje($action_index):void
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

    public function getRevard():float
    {
        return $this->rewards[$this->stanSwiata[0]][$this->stanSwiata[1]];
    }

    public function showResult(QTable $QTable): void
    {
        $this->stanSwiata = [5, 2];
        $start_row_index = $this->stanSwiata[0];
        $start_column_index = $this->stanSwiata[1];

        if (!($this->rewards[$start_row_index][$start_column_index] == -1)) {
            echo 'Jakas lipa'."\m";
        } else {
            $current_row_index = $start_row_index;
            $current_column_index = $start_column_index;
            $shortest_path = [];
            $shortest_path[] = [$current_row_index, $current_column_index];

            while (($this->rewards[$current_row_index][$current_column_index] == -1)) {
                $id = $this->swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata([$current_row_index, $current_column_index]);
                $action_index = $QTable->get_next_action($id, 1);
                $this->wykonaj_akcje($action_index);
                $a = $this->pobierz_stan_swiata();
                $current_row_index = $this->stanSwiata[0];
                $current_column_index = $this->stanSwiata[1];
                $shortest_path[] = [$current_row_index, $current_column_index];
            }
            $path = array_reverse($shortest_path);
            foreach ($path as $row) {
                echo '[' . $row[0] . ', ' . $row[1] . '], ';
            }
            echo "\n";
        }
    }
}
