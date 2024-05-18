<?php


class Qlearning
{
    private int $episode = 2000;
    private QLerningGameInterfae $gra;
    private float $epsilon;
    private QTable $qTable;

    public function __construct(QLerningGameInterfae $gra, float $epsilon = 0.9,float $discount_factor = 0.9, float $learning_rate=0.9)
    {
        $this->gra = $gra;
        $this->qTable = new QTable($gra->getStanySwiataCount(), $gra->getActionCount(),$discount_factor,$learning_rate);;
        $this->epsilon = $epsilon;
    }

    public function go()
    {
        for ($i = 0; $i < $this->episode; $i++) {
           // echo('Start $episode ' . $i . "\n");
            $this->gra->resetujGre();
            $s1 = $this->gra->pobierz_stan_swiata();
            while (!$this->gra->czy_koniec_gry()) {
                $action_index = $this->qTable->get_next_action($s1, $this->epsilon);
                $s1Tmp = $s1;
                $this->gra->wykonaj_akcje($action_index);
                $s1 = $this->gra->pobierz_stan_swiata();
                $reward = $this->gra->getRevard();
                $this->qTable->lenr($s1Tmp, $s1, $action_index, $reward);
            }
            $this->gra->showResult($this->qTable);
            //echo('End $episode ' . $i . "\n");
        }
    }
}