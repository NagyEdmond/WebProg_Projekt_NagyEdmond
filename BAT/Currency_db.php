<?php

include '../BACKEND/Currency.php';


//UTILITY FUNCTIONS
function requireDbUpdate($lastDate)
{
    if (is_null($lastDate))
    {
        return true;
    }

    $lastDate = new DateTime($lastDate);

    $today = new DateTime("now");

    $interval = $today->diff($lastDate);

    if($interval->days >= 1)
    {
        return true;
    }
}

function loadAPICurrencies()
{

    $reqUrl = 'https://v6.exchangerate-api.com/v6/4d0b775ee73083318a6db31c/latest/EUR';

    $responseJson = file_get_contents($reqUrl);

    $decodedJson = json_decode($responseJson,true);

    $newDate = $decodedJson['time_last_update_utc'];
    $newDateTime = DateTime::createFromFormat("D, d M Y H:i:s O", $newDate);
    $formattedDate = $newDateTime->format('Y-m-d');

    $rates = $decodedJson['conversion_rates'];

    return [$formattedDate, $rates];

}

//CURRENCY DB CLASS

class CurrencyDB
{
    private mysqli $db;
    
    public function __construct()
    {
        $this->db = new mysqli('localhost', 'projekt_admin', '_qwerty09', 'php_currency');
    }

    public function insertNewCurrency(Currency $currency)
    {
        $currencyName = $currency->getName();
        //INSERT NEW CURRENCY
        $sql = "INSERT INTO currencies (currency_name) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $currencyName);
        $stmt->execute();
        $stmt->close();
    }

    public function insertCurrencyRate(Currency $currency)
    {
        $currencyId       = 0;
        $currencyName     = $currency->getName();
        $currencyRate     = $currency->getRate();
        $currencyRateDate = $currency->getDateOfRate();

        //GET ID OF CURRENCY
        $sql = "SELECT currency_id FROM currencies WHERE currency_name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $currencyName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $currencyId = $row['currency_id'];

        //INSERT RATE AND DATE OF CURRENCY IN DIFFERENT TABLE
        $sql = "INSERT INTO currency_rates (currency_id, rate, date_of_rate) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ids', $currencyId, $currencyRate, $currencyRateDate);
        $stmt->execute();
        $stmt->close();
    }

    public function checkIfExists(Currency $currency)
    {
        $currencyName = $currency->getName();
        //CHECK IF CURRENCY EXISTS
        $sql = "SELECT currency_id FROM currencies WHERE currency_name = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $currencyName);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return (bool) $row;
    }

    public function updateDB()
    {
        //GET LATEST DATE FROM DB
        $sql = "SELECT MAX(date_of_rate) AS latest_date FROM currency_rates";
        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        $latestDate = $row['latest_date'];

        //CHECK IF LATEST DB DATE IS ONE DAY OLDER THAN TODAY
        if(requireDbUpdate($latestDate))
        {
            $decodedJson = loadAPICurrencies();
            $newDate     = $decodedJson[0];
            $rates       = $decodedJson[1];

            foreach ($rates as $key => $value)
            {
                $currency = new Currency($key, $value, $newDate);
                if(!$this->checkIfExists($currency))
                {
                    $this->insertNewCurrency($currency);
                }
                $this->insertCurrencyRate($currency);
            }
        }
    }

    public function getCurrencies()
    {
        $sql    = "SELECT currency_name FROM currencies ORDER BY currency_id";
        $result = $this->db->query($sql);
        $nameRows   = $result->fetch_all();
        $nameArray = array_merge_recursive(...$nameRows);

        $sql    = "SELECT currency_id, rate FROM currency_rates ORDER BY currency_id";
        $result = $this->db->query($sql);
        $rateRows   = $result->fetch_all();

        $rateArray = [];
        foreach($rateRows as $rateRow)
        {
            if(!isset($rateArray[$rateRow[0]]))
            {
                $rateArray[$rateRow[0]] = [];
            }
            array_push($rateArray[$rateRow[0]], $rateRow[1]);
        }

        $rateArray = array_values($rateArray);

        $sql    = "SELECT DISTINCT date_of_rate FROM currency_rates";
        $result = $this->db->query($sql);
        $dateRows   = $result->fetch_all();

        return [$nameArray, $rateArray, $dateRows];
        
    }
}


$db = new CurrencyDB();
$db->updateDB();


