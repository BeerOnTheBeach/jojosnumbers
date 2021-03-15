<?php

namespace Jojosnumbers\src;

// Option
use AlphaVantage\Client;

$option = new AlphaVantage\Options();
$option->setApiKey('DFO0621J5JWADO97');

// Client
$client = new Client($option);
$dailyXaiomi =$client->timeseries()->dailyAdjusted("XIACF")["Time Series (Daily)"];

$symbole = ["XIACF", "AAPL", "SNE"];

foreach ($symbole as $symbol) {
    $result = $client->timeseries()->dailyAdjusted("$symbol")["Time Series (Daily)"];
    $exchangeRate = $client->foreignExchange()->daily("USD", "EUR");

    foreach($result as $date => $item) {
        $closed = $item['4. close'];
        var_dump($exchangeRate[$date]); die();
        $closed2 = $closed*$exchangeRate[$date][['4. close']];
        echo "$date: $closed $<br>";
        echo "$date: $closed2 €";
    }
    echo "Neues symbol <br>";
}

