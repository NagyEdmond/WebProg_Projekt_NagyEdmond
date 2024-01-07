<?php

include '../BACKEND/Currency_db.php';

$db = new CurrencyDB();

$currencyName = "EUR";

if(isset($_GET['curName']))
{
    $currencyName = $_GET['curName'];
}

$response = $db->getLatestRate($currencyName);

echo $response;