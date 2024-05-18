<?php

interface QLerningGameInterfae
{

    //liczba wszystkich możliwych stanów świata
    public function getStanySwiataCount():int;

    //liczba wszyskich akcji jakie można wykonać
    public function getActionCount():int;

    //przywraca stan gry do stanu początkowego
    public function resetujGre():void;

    //zwraca unikalny identyfikator stanu świata (liczba od 0 do ...)
    public function pobierz_stan_swiata():int;

    // czy gra jest w takim stanie rze nalerzy ją zakńczyć
    public function czy_koniec_gry(): bool;

    // wykonanie akcji pobranej z Qtable lub od sieci neuronowej
    public function wykonaj_akcje(int $action_index):void;

    // pobranie nagrody za wykonaną akcję
    public function getRevard(): float;

    public function showResult(QTable $QTable):void;
}