<?php
spl_autoload_register(function ($class_name) {
    include 'Class/'.$class_name . '.php';
});

$cbrf = new CBRF();

//yesterday
$now = new DateTime();
$now->sub(new DateInterval('P1D'));

//get exchange rates for yesterday
$rates = $cbrf->getCurrencyRates($now->format(CBRF::DATE_FORMAT));

print_r(json_decode($rates, true));

