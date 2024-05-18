<?php

$stanySwiataAkcjaNagroda = [
    [0,0,0.941,0]
];

$maxs = array_keys($stanySwiataAkcjaNagroda[0], max($stanySwiataAkcjaNagroda[0]));
$maxKey = array_rand($maxs);

var_dump($maxs[$maxKey]);
exit();

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













////$f = require '/media/grzegorz/DATA2/projekty/php/Q-learning/Readme';
//$f = file_get_contents('/media/grzegorz/DATA2/projekty/php/Q-learning/Readme');
//var_dump($f);