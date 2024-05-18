<?php

require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/InterfaceWStream.php';
require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/CsvFileReader.php';
require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/Wstream/WstreamFileAverage.php';
require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/functions.php';


require_once 'src/WozekWidlowy.php';
require_once 'src/QTable.php';
require_once 'src/CechaSwiata.php';
require_once 'src/Swiat.php';
require_once 'src/QLerningGameInterfae.php';
require_once 'src/Qlearning.php';
require_once 'src/QBot.php';
require_once 'src/Konto.php';
require_once 'src/Konto.php';




$csvFileReader = new Bot\Wstream\WstreamFileAverage('binance', '-BTC-USDT', new DateTime('2023-01-01'),new DateTime('2024-05-15'));

function idStanu(array $row)
{
    $i = (int)($row['w8'] * 100);
    $d = 30;
    $d2 = $d * -1;
    if ($i > $d) {
        $i = $d;
    } elseif ($i < $d2) {
        $i = -$d2;
    }
    return $i;
}

function wybierzAkcje($array)
{
    $randomInt = random_int(1, 100);
    if ($randomInt < 5) {
        return array_rand($array);
    }
    $maxs = array_keys($array, max($array));
    $maxKey = array_rand($maxs);
    return $maxs[$maxKey];
}

function wykonajAkcje($akcja, $row)
{
    global $konto;

    if ($akcja == KUP) {
        $konto->openLong($row);
    } elseif ($akcja == SPRZEDAJ) {
        return $konto->closeLong($row);
    } elseif ($akcja == NIC) {

    } else {
        var_dump('Nieznana akcja');
        exit();
    }
    return 0;
}

$wszystkieStany = [];

const KUP = 0;
const NIC = 1;
const SPRZEDAJ = 2;

foreach ($csvFileReader->getW() as $row) {

    @$wszystkieStany[idStanu($row)] = [0, 0, 0];
}
echo 'Lista stan akcja ' . count($wszystkieStany) . "\n";

$idPoprzedniegoStanu = null;
$idPoprzedniejAkcji = null;

for ($i = 0; $i < 180; $i++) {
    $konto = new Konto(5000, 50);
    foreach ($csvFileReader->getW()  as $row) {
        $idStanu = idStanu($row);

        if ($idStanu == $idPoprzedniegoStanu) {
            //SWIAT NIE PRZESZEDŁ DO NASTEPNEGO STANU CZEKAMY NA NOWY STAN
            continue;
        }

        $akcje = $wszystkieStany[$idStanu];
        $akcja = wybierzAkcje($akcje);

        $nagroda = wykonajAkcje($akcja, $row);


        if($nagroda != 0 ){
            //$this->akcje[$tmpX][$tmpY][$akcja] += $this->akcje[$tmpX][$tmpY][$akcja] * 0.5; // ROSNIE SORAZ WOLNIEJ
            $wszystkieStany[$idPoprzedniegoStanu][$idPoprzedniejAkcji] += $wszystkieStany[$idPoprzedniegoStanu][$idPoprzedniejAkcji] * 0.5;
        }

        $maxs = array_keys($wszystkieStany[$idStanu], max($wszystkieStany[$idStanu]));
        $maxKey = array_rand($maxs);
        $nagroda += $wszystkieStany[$idStanu][$maxs[$maxKey]];



        //echo 'Id Stanu: ' . $idStanu . " id akcja " . $akcja . "\n";
        if ($idPoprzedniegoStanu === null) {
            $idPoprzedniegoStanu = $idStanu;
            $idPoprzedniejAkcji = $akcja;
            continue;
        }
        $wszystkieStany[$idPoprzedniegoStanu][$idPoprzedniejAkcji] = $wszystkieStany[$idStanu][$akcja] * 0.5;
        $idPoprzedniegoStanu = $idStanu;
        $idPoprzedniejAkcji = $akcja;
    }

    ksort($wszystkieStany);
//    foreach ($wszystkieStany as $idStanu => $akcje) {
//
//        echo $idStanu . "\n";
//        echo "\t" . number_format($akcje[KUP], 3, '.', '') . "\n";
//        echo "\t" . number_format($akcje[NIC], 3, '.', '') . "\n";
//        echo "\t" . number_format($akcje[SPRZEDAJ], 3, '.', '') . "\n";
//    }
    var_dump('kasa: ' . $konto->getKasa());
}