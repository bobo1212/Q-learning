<?php

class QTable
{
    private array $q_values = [];
    private float $discount_factor;
    private float $learning_rate;
    /**
     * @var array|int
     */
    private $actionLastKey;

    public function __construct($rewards, $actions, float $discount_factor, float $learning_rate)
    {
        $this->actionLastKey = $actions - 1;
        $this->learning_rate = $learning_rate;
        $this->discount_factor = $discount_factor;

        $actions = array_fill(0, $actions, 0);

        $this->q_values = array_fill(0, $rewards, $actions);

//        foreach ($this->q_values as $id => $actions) {
//            $actions = array_map(function () {
//                return rand(0, $this->actionLastKey);
//            }, $actions);
//            $this->q_values[$id] =  $actions;
//        }

    }

    public function lenr($s1Tmp, $s1, $action_index, $reward)
    {
        //$discount_factor = 0.9;  # discount factor for future rewards
        //$learning_rate = 0.9;  # the rate at which the AI agent should learn

        $old_q_value = $this->q_values[$s1Tmp][$action_index];
        $max_q_value = max($this->q_values[$s1]);
        $temporal_difference = $reward + ($this->discount_factor * $max_q_value) - $old_q_value;
        $new_q_value = $old_q_value + ($this->learning_rate * $temporal_difference);
        $this->q_values[$s1Tmp][$action_index] = $new_q_value;

        $sumaZer = 0;
//        foreach ($this->q_values as $id => $akcje) {
//            if(array_sum($akcje) == 0){
//                $sumaZer++;
//            }
//        }
//        var_dump((int)(($sumaZer*100)/count($this->q_values)));
//         print("\033[2J\033[;H");
//        foreach ($this->q_values as $id => $akcje) {
//            foreach ($akcje as $k => $a) {
//                $akcje[$k] = (int)$a;
//            }
//            logMsg($id . ' [' . implode("][", $akcje) . ']');
//        }
        // usleep(1000);

    }

    public function get_next_action(int $stanSwiata, float $epsilon)
    {
        //$epsilon = 0.9;  # the percentage of time when we should take the best action (instead of a random action)
        #if a randomly chosen value between 0 and 1 is less than epsilon,
        #then choose the most promising value from the Q-table for this state.
        if ($this->rand_float() < $epsilon) {
            $maxValue = max($this->q_values[$stanSwiata]);
            return array_keys($this->q_values[$stanSwiata], $maxValue)[0];
        } else {
            return rand(0, $this->actionLastKey);
        }

    }

    public function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
    {
        if ($st_num > $end_num) {
            return false;
        }
        return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
    }

    public function getQValues()
    {
        return $this->q_values;
    }
}
