<?php

include_once "Currency_db.php";

$db = new CurrencyDB();

$curNames = ["EUR", "USD"];

if(isset($_GET['selectedValues']))
{
    $curNames = json_decode($_GET['selectedValues']);
}


$valuesList = [];

$dateList = $db->getAllDates();


foreach($curNames as $curName)
{
    array_push($valuesList, $db->getCurrencyValues($curName));
}

echo json_encode([$dateList, $valuesList]);