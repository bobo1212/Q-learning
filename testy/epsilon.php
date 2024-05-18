<?php


function rand_float($st_num = 0, $end_num = 1, $mul = 1000000)
{
    if ($st_num > $end_num) {
        return false;
    }
    return mt_rand($st_num * $mul, $end_num * $mul) / $mul;
}

$epsilon = 0.9; // akcja najlepsza
$epsilon = 0.1; // akcja losowa

while(true){

    if (rand_float() < $epsilon) {
        echo 'max'."\n";
    } else {
        echo 'los!!!!!!'."\n";
    }
}