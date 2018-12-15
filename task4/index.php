<?php

//вспомогательная функция
function generateRandomNumber($length = 9) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return preg_replace("/^0+/", "", $randomString) ?: 0;
}

//вариант 1
function sum1($num1, $num2) {
    $num1 = preg_replace("/\D/", "", $num1);
    $num2 = preg_replace("/\D/", "", $num2);
    $num1_rev = strrev($num1);
    $num2_rev = strrev($num2);
    $length = max(strlen($num1_rev), strlen($num2_rev));
    $result_rev = "";
    $in_mind = 0;
    for ($i = 0; $i <= $length; $i++) {
        $sum = (strlen($num1_rev) > $i ? $num1_rev[$i] : 0) + (strlen($num2_rev) > $i ? $num2_rev[$i] : 0) + $in_mind;
        $in_mind = floor($sum / 10);
        $result_rev .= $sum % 10;
    }
    return preg_replace("/^0+/", "", strrev($result_rev));
}

//вариант 2 (в среднем чуть быстрей)
function sum2($num1, $num2) {
    $num1 = preg_replace("/\D/", "", $num1);
    $num2 = preg_replace("/\D/", "", $num2);
    $max_length = max(strlen($num1), strlen($num2));
    $num1 = str_pad($num1, $max_length, "0", STR_PAD_LEFT);
    $num2 = str_pad($num2, $max_length, "0", STR_PAD_LEFT);
    $result_rev = "";
    $in_mind = 0;
    for ($i = $max_length; $i >= 0; $i--) {
        $sum = ($i >= 1 ? ($num1[$i - 1] + $num2[$i - 1]) : 0) + $in_mind;
        $in_mind = floor($sum / 10);
        $result_rev .= $sum % 10;
        // вариант
        // $result = $sum % 10 . $result;
        // работает намного дольше по времени
    }
    return preg_replace("/^0+/", "", strrev($result_rev));
}

$rand1 = generateRandomNumber(rand(1, 100));
$rand2 = generateRandomNumber(rand(1, 100));

echo $rand1 . "<br />";
echo "+<br />";
echo $rand2 . "<br />";
echo "=<br />";
echo sum1($rand1, $rand2) . "<br />";
echo "<br />";
echo sum2($rand1, $rand2) . "<br />";

/*
$curTime = microtime(true);
for ($i = 1; $i <= 500; $i++) {
    sum1(generateRandomNumber(rand(1, 10000)), generateRandomNumber(rand(1, 10000)));
}

$timeConsumed = round(microtime(true) - $curTime, 3) * 1000;
echo "функция sum1 - ".$timeConsumed . " ms<br />";

$curTime = microtime(true);
for ($i = 1; $i <= 500; $i++) {
    sum2(generateRandomNumber(rand(1, 10000)), generateRandomNumber(rand(1, 10000)));
}
$timeConsumed = round(microtime(true) - $curTime, 3) * 1000;

echo "функция sum2 - ".$timeConsumed . " ms<br />";
*/
