<?php

require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/CsvFileReader.php';
require_once '/media/grzegorz/DATA2/projekty/php/BinanceBot/GeneratorSygnalow/src/functions.php';
require_once 'src/Konto.php';

use Bot\CsvFileReader;

class QBot implements QLerningGameInterfae
{
    private $dateFrom = '2024010101';
    private $dateTo = '2024040101';
    private $dir = 'binance-BTC-USDT';
    private Generator $generator;
    private array $actions =
        [
            'pass',
            'sell',
            'buy',

        ];
    private Swiat $swiat;
    private CsvFileReader $csvFileReader;


    private float $revard;
    private int $kasa;
    private int $kupujza;
    private int $posiadanesztuki;
    /**
     * @var false
     */
    private bool $koniec;
    private mixed $w;
    private Konto $konto;
    private int $licznikBuy = 0;


    public function __construct(
        array  $simulationConfig,
        string $outputfilename
    )
    {
        $this->simulationConfig = $simulationConfig;
        $this->outputfilename = $outputfilename;


        $this->csvFileReader = new CsvFileReader();

        $this->swiat = new Swiat();
        $this->swiat->add(new CechaSwiata(-3, 3, 30));//w8
        $this->swiat->add(new CechaSwiata(0, 15, 15));//15pozycji


    }

    public function getStanySwiataCount(): int
    {
        return $this->swiat->porzerzLiczbeStanowSwiata();
    }

    public function getActionCount(): int
    {
        return count($this->actions);
    }

    public function resetujGre(): void
    {
        $this->koniec = false;
        $this->konto = new Konto(5000, 15);
        $this->licznikBuy = 0;
        $this->generator = $this->getGenerator();
    }

    public function pobierz_stan_swiata(): int
    {
        $this->w = $this->generator->current();
        $this->generator->next();

        $ret = $this->swiat->pobierzUNIKALNYIdentyfikatorStanuSwiata([
            $this->w['w8'],
            $this->licznikBuy
        ]);
        //logMsg($this->licznikBuy .' ' .$ret);
        return $ret;
    }

    public function czy_koniec_gry(): bool
    {
        if ($this->koniec) {
            return true;
        }
        return $this->generator->current() === null;
    }

    private int $revardSum = 0;

    public function wykonaj_akcje(int $action_index): void
    {
        $signal['type'] = $this->actions[$action_index];

        $this->revard = -1;

        if ($signal['type'] == 'buy') {
            if ($this->licznikBuy == 15) {
                $this->revard = -20;
                $this->koniec = true;
                //echo 'Przekroczono licznik kara 20 !!! i koniec' . "\n";
                return;
            }
            $this->konto->openLong($this->w);
            $this->licznikBuy++;
            //echo 'Kupiono kara -1' . "\n";

        } else if ($signal['type'] == 'sell') {
            $this->licznikBuy = 0;
            $this->revard = $this->konto->closeLong($this->w);
            if ($this->revard <= 0) {
                $this->revard = -20;
                $this->koniec = true;
                //echo 'Sprzedano ze stratą kara -20 koniec' . "\n";
            } else {
                $this->revard *=10;
            }
        }
    }

    public function getRevard(): float
    {
        return $this->revard;
    }

    public function showResult(QTable $QTable): void
    {
        $sumaZer = 0;
        foreach ($QTable->getQValues() as $id => $akcje) {
            if (array_sum($akcje) == 0) {
                $sumaZer++;
            }
        }
        //var_dump((int)(($sumaZer * 100) / count($QTable->getQValues())));
        echo 'Zarobiona kasa: ' . number_format($this->konto->getTotalZysk(), 2, '.') .';'.$this->konto->getCloseCount() ."\n";
//        echo '          Kasa: ' . $this->konto->getKasa() . "\n";
//        echo '          open: ' . $this->konto->getOpenCount() . "\n";
//        echo '         close: ' . $this->konto->getCloseCount() . "\n";
//        echo '=========================================================' . "\n";

    }


    private function getGenerator(): Generator
    {
        return $this->csvFileReader->getFiles('binance-BTC-USDT', $this->dateFrom, $this->dateTo);
    }
}