<?php

require_once 'src/WozekWidlowy.php';
require_once 'src/QTable.php';
require_once 'src/CechaSwiata.php';
require_once 'src/Swiat.php';
require_once 'src/QLerningGameInterfae.php';
require_once 'src/Qlearning.php';
require_once 'src/QBot.php';


$swiat = new Swiat();

$cechu = [
     ['od' => -3, 'do' => 3, 'przedzialy' => 3, 'nazwa' => 'w8'],
   // ['od' => 0, 'do' => 15, 'przedzialy' => 15, 'nazwa' => 'pozycje'],
];

foreach ($cechu as $cecha) {
    $swiat->add(new CechaSwiata($cecha['od'], $cecha['do'], $cecha['przedzialy']));//w8
}

//for ($i = 0; $i < 15; $i++) {
//    $klucz = $swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata([$i]);
//    echo 'poz:' . $i . ' klucz: ' . $klucz . "\n";
//}

for ($i = -3; $i < 3; $i++) {
    $klucz = $swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata([$i]);
    echo 'w8:' . $i . ' klucz: ' . $klucz . "\n";
}