<?php

require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/CsvFileReader.php';
require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/functions.php';

require_once 'src/WozekWidlowy.php';
require_once 'src/QTable.php';
require_once 'src/CechaSwiata.php';
require_once 'src/Swiat.php';
require_once 'src/QLerningGameInterfae.php';
require_once 'src/Qlearning.php';
require_once 'src/QBot.php';
require_once 'src/Konto.php';

 $dateFrom = '2024010101';
 $dateTo = '2024020101';

$csvFileReader = new Bot\CsvFileReader();
$files  = $csvFileReader->getFiles('binance-BTC-USDT', $dateFrom, $dateTo);
$konto = new Konto(5000,50);


$konto->openLong(['ask' => 100]);
$konto->openLong(['ask' => 100]);
$zysk =$konto->closeLong(['bid' => 101]);
var_dump($zysk);

$konto->openLong(['ask' => 100]);
$konto->openLong(['ask' => 100]);
$zysk =$konto->closeLong(['bid' => 101]);
var_dump($zysk);
var_dump($konto->getTotalZysk());



//foreach($files as $row){
//
//    var_dump($row);
//}