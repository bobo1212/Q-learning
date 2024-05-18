<?php


require_once 'src/WozekWidlowy.php';
require_once 'src/QTable.php';
require_once 'src/CechaSwiata.php';
require_once 'src/Swiat.php';
require_once 'src/QLerningGameInterfae.php';
require_once 'src/Qlearning.php';


    $swiat = new Swiat();
    $swiat->add(new CechaSwiata(0, 11, 11));//w8
    $swiat->add(new CechaSwiata(0, 11, 11));//pozycja otwarta zamknieta

    $gra = new WozekWidlowy($swiat);
    // $epsilon decyduje o wyborze akcji 0.9 - akcja najlepsza, 0.1 - akcja losowa
    $epsilon = 0.9;  # the percentage of time when we should take the best action (instead of a random action)

    //oczekujemy nagrody aktualnie czy oczekujemy nagrody odwleczonej w czasie
    $discount_factor = 0.9;  # discount factor for future rewards

    // jak bardzo modyfikować w procesie nauki wartość q_value 0.9 bardzo, 0.1 trochę
    $learning_rate = 0.9;  # the rate at which the AI agent should learn


    $q = new Qlearning($gra,$epsilon,$discount_factor,$learning_rate);
    $q->go();






