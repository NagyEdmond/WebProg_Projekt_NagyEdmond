<?php

include '../../BACKEND/Currency_db.php';

$db = new CurrencyDB();

$rows = $db->getCurrencies();

$nameArray = $rows[0];
$rateArray = $rows[1];
$dateArray = $rows[2];


?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
        <link rel="stylesheet" href="rates_display.css">
    </head>
    <body>
        <h2>Select Currency</h2>
        <div class="singleRate">
            <table class="curInfo">
                <tr>
                    <th>Name</th>
                    <td class="curName">
                        <select id="currencySelector" onchange="displayCurInfo()">
                            <?php
                            for($index = 0; $index < count($nameArray); $index++)
                            {
                                echo "<option value='" . $nameArray[$index] . "'>" . $nameArray[$index] . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Current rate</th>
                    <td id="curRate">
                        <div id="loaderIcon" style="display: none;">
                            <span class="dot1">&bull;</span>
                            <span class="dot2">&bull;</span>
                            <span class="dot3">&bull;</span>
                        </div>
                        <p id="rateValue" style="display: none;"></p>
                    </td>
                </tr>
            </table>
            <table class="curConv">
                <tr>
                    <th colspan="2">Currency conversion</th>
                </tr>
                <tr>
                    <th>
                        <select id="cur1">
                        <?php
                            for($index = 0; $index < count($nameArray); $index++)
                            {
                                echo "<option value='" . $nameArray[$index] . "'>" . $nameArray[$index] . "</option>";
                            }
                        ?>
                        </select>
                    </th>
                    <td>
                        <input id="convValue" type="value" value="1">
                    </td>
                </tr>
                <tr>
                    <th>
                    <select id="cur2">
                        <?php
                            for($index = 0; $index < count($nameArray); $index++)
                            {
                                echo "<option value='" . $nameArray[$index] . "'>" . $nameArray[$index] . "</option>";
                            }
                        ?>
                    </select>
                    </th>
                    <td>
                        <input id="convResult" disabled="true"></input>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="allRates">
            <h1>Rates</h1>
            <div class="curSelector">
            <?php
            foreach($nameArray as $name)
            {
                echo "<div>";

                if($name != "EUR" && $name != "USD")
                {
                    echo "<label for='" . $name . "'>" . $name . "</label>";
                    echo "<input type='checkbox' id='" . $name . "'></input>";
                }

                echo "</div>";
            }

            ?>
            </div>
            <canvas id="rates"></canvas>
        </div>
    </body>
    <script src="rates_display.js"></script>
    <script src="display_exchange_calc.js"></script>
    <script src="cur_info.js"></script>
</html>