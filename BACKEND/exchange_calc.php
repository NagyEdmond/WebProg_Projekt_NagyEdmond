<?php

include_once "Currency_db.php";

$db   = new CurrencyDB();

$cur1 = $_GET['cur1'];
$cur2 = $_GET['cur2'];
$amount = $_GET['amount'];

$cur1Value = $db->getLatestRate($cur1);
$cur2Value = $db->getLatestRate($cur2);


$calcExchange = function($cur1, $cur2, $amount) use($cur1Value, $cur2Value) {
    return $amount * ($cur2Value / $cur1Value);
};

echo $calcExchange($cur1, $cur2, $amount);