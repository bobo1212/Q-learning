<?php


$size1 = 3;
$size2 = 3;
$size3 = 3;
////https://stackoverflow.com/questions/27161809/efficiently-accessing-a-3d-array-stored-as-a-1d-array
for ($k = 0; $k < $size1; $k++) { // Loop through the height.
    for ($j = 0; $j < $size2; $j++) { // Loop through the rows.
        for ($i = 0; $i < $size3; $i++) // Loop through the columns.
        {
            $ijk = $i + ($size3 * $j) + ($size3 * $size2 * $k);
            //var_dump($ijk);
            //$my3Darray[$ijk] = 1.0;
        }
    }
}
//
//26 = i + 3j + 9k;
//


$size1 = 2;
$size2 = 3;
$size3 = 4;
$size4 = 5;
////https://stackoverflow.com/questions/27161809/efficiently-accessing-a-3d-array-stored-as-a-1d-array
for ($k4 = 0; $k4 < $size4; $k4++) { // Loop through the height.
    for ($k3 = 0; $k3 < $size3; $k3++) { // Loop through the height.
        for ($k2 = 0; $k2 < $size2; $k2++) { // Loop through the height.
            for ($k1 = 0; $k1 < $size1; $k1++) { // Loop through the height.
                $lp = $k1 + ($k2 * $size1) + ($k3 * $size1 * $size2) + ($k4 * $size1 * $size2 * $size3);
                var_dump($lp);
            }
        }
    }
}

$world = [
    [1, 2, 3],

    [4, 5, 6, 7],

    [8, 8]
];
//

function pobierzNumerStanySwiat(array $sizes, array $keys)
{
    if(count($sizes) != count($keys)){
        var_dump('ZŁE ROZMIARY ' . count($sizes) .' '. count($keys));
        exit();
    }
    foreach($sizes as $k => $size){
        if($keys[$k] >= $size){
            var_dump('lipa', $k, $keys[$k] , $size);exit();
        }
    }
    $lp = 0;
    foreach ($keys as $keyPos => $key) {
        $sizePos = $keyPos - 1;
        $m = 1;
        if ($sizePos >= 0) {
            $m = array_product(array_slice($sizes, 0, $sizePos + 1));
//            foreach ($sizes as $ks => $v) {
//                $m *= $v;
//                if ($ks == $sizePos) {
//                    break;
//                }
//            }
        }

        $lp += ($key * $m);
    }
    return $lp;
}

var_dump(pobierzNumerStanySwiat([3, 4, 2], [1, 0, 0]));