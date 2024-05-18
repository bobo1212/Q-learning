<?php


class World
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
    private array $q_values = [];

    public function __construct()
    {
        foreach ($this->rewards as $k1 => $row) {
            foreach ($row as $k2 => $v) {
                foreach ($this->actions as $k3 => $a) {
                    $this->q_values[$k1][$k2][$k3] = 0;
                }
            }
        }
    }

    public function czy_gra_dobiegla_konca(int $current_row_index, int $current_column_index)
    {
        if ($this->rewards[$current_row_index][$current_column_index] == -1) {
            return False;
        } else {
            return True;
        }
    }

    public function pobierz_poczatkowy_stan_swiata()
    {

        $current_row_index = (int)array_rand($this->rewards, 1);
        $current_column_index = (int)array_rand($this->rewards[0], 1);


        while ($this->czy_gra_dobiegla_konca($current_row_index, $current_column_index)) {
            $current_row_index = array_rand($this->rewards, 1);
            $current_column_index = array_rand($this->rewards[0], 1);
        }
        return [$current_row_index, $current_column_index];
    }

    /**
     * @throws Exception
     */
    public function get_next_action($current_row_index, $current_column_index, $epsilon)
    {
        #if a randomly chosen value between 0 and 1 is less than epsilon,
        #then choose the most promising value from the Q-table for this state.
        if ($this->rand_float() < $epsilon) {
            $maxValue= max($this->q_values[$current_row_index][$current_column_index]);
            return array_keys($this->q_values[$current_row_index][$current_column_index], $maxValue)[0];
        } else {
            return random_int(0, 3);
        }

    }

    public function wykonaj_akcje($current_row_index, $current_column_index, $action_index)
    {
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
        return [$new_row_index, $new_column_index];
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

    #Define a function that will get the shortest path between any location within the warehouse that
#the robot is allowed to travel and the item packaging location.
    public function get_shortest_path($start_row_index, $start_column_index)
    {
        if ($this->czy_gra_dobiegla_konca($start_row_index, $start_column_index)) {
            return [];
        } else {
            $current_row_index = $start_row_index;
            $current_column_index = $start_column_index;
            $shortest_path = [];
            $shortest_path[] = [$current_row_index, $current_column_index];
            while (!$this->czy_gra_dobiegla_konca($current_row_index, $current_column_index)) {
                $action_index = $this->get_next_action($current_row_index, $current_column_index, 1);
                $a = $this->wykonaj_akcje($current_row_index, $current_column_index, $action_index);
                $current_row_index = $a[0];
                $current_column_index = $a[1];
                $shortest_path[] = [$current_row_index, $current_column_index];
            }
            return $shortest_path;
        }
    }

    public function go()
    {
        $epsilon = 0.9;  # the percentage of time when we should take the best action (instead of a random action)
        $discount_factor = 0.9;  # discount factor for future rewards
        $learning_rate = 0.9;  # the rate at which the AI agent should learn
        for ($episode = 0; $episode < 1000; $episode++) {
            $s = $this->pobierz_poczatkowy_stan_swiata();
            $stan_swiata_1 = $s[0];
            $stan_swiata_2 = $s[1];
            while (!$this->czy_gra_dobiegla_konca($stan_swiata_1, $stan_swiata_2)) {
                $action_index = $this->get_next_action($stan_swiata_1, $stan_swiata_2, $epsilon);
                $old_stan_swiata_1 = $stan_swiata_1;
                $old_stan_swiata_2 = $stan_swiata_2;
                $a = $this->wykonaj_akcje($old_stan_swiata_1, $old_stan_swiata_2, $action_index);
                $stan_swiata_1 = $a[0];
                $stan_swiata_2 = $a[1];

                $reward = $this->rewards[$stan_swiata_1][$stan_swiata_2];


                $old_q_value = $this->q_values[$old_stan_swiata_1][$old_stan_swiata_2][$action_index];
                $max_q_value = max($this->q_values[$stan_swiata_1][$stan_swiata_2]);

                $temporal_difference = $reward + ($discount_factor * $max_q_value) - $old_q_value;

                $new_q_value = $old_q_value + ($learning_rate * $temporal_difference);
                $this->q_values[$old_stan_swiata_1][$old_stan_swiata_2][$action_index] = $new_q_value;

            }
        }

        $path = $this->get_shortest_path(5, 2); #go to row 5, column 2
        $path = array_reverse($path);
        foreach($path as $row){
            echo '['.$row[0].', '.$row[1].'], ';
        }
        echo "\n";
    }
}
//[0,5][1,5][1,6][1,7][2,7][3,7][4,7][5,7][5,6][5,5][5,4][5,3][5,2]
//[0, 5], [1, 5], [1, 6], [1, 7], [2, 7], [3, 7], [3, 6], [3, 5], [3, 4], [3, 3], [4, 3], [5, 3], [5, 2],
//[0, 5], [1, 5], [1, 6], [1, 7], [2, 7], [3, 7], [4, 7], [5, 7], [5, 6], [5, 5], [5, 4], [5, 3], [5, 2]
//[0, 5], [1, 5], [1, 6], [1, 7], [2, 7], [3, 7], [4, 7], [5, 7], [5, 6], [5, 5], [5, 4], [5, 3], [5, 2]


//
////3
////9
////27
//
//$a1 = [1, 2, 3];
//$a2 = [4, 5, 6];
//$a3 = [7, 8, 9];
//
//$k = [];
//$lp = 1;
//foreach ($a1 as $k1 => $v1) {
//    foreach ($a2 as $k2 => $v2) {
//        foreach ($a3 as $k3 => $v3) {
//            //  echo $v1 . ' ' . $v2 . ' ' . $v3 . ' = ' . $lp . "\n";
//            $k[$v1][$v2][$v3] = $lp;
//            $lp++;
//        }
//    }
//}
//// var_dump($k);
//$nk = 3;
//$nj = 3;
//$ni = 3;
////https://stackoverflow.com/questions/27161809/efficiently-accessing-a-3d-array-stored-as-a-1d-array
//for ($k = 0; $k < $nk; $k++) { // Loop through the height.
//    for ($j = 0; $j < $nj; $j++) { // Loop through the rows.
//        for ($i = 0; $i < $ni; $i++) // Loop through the columns.
//        {
//            $ijk = $i + ($ni * $j) + ($ni * $nj * $k);
//            var_dump($ijk);
//            //$my3Darray[$ijk] = 1.0;
//        }
//    }
//}
//
//26 = i + 3j + 9k;
//



$w = new World();
$w->go();