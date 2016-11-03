<?php
function bin2dec($bin) {
    return hexdec(bin2hex(strrev($bin)));
}

function bin2hexr($bin) {
    return bin2hex(strrev($bin));
}

function hex2binr($hex) {
    return strrev(hex2bin($hex));
}

function hexr($hex) {
    return bin2hexr(hex2bin($hex));
}

function dump($var) {
    var_dump($var);
}

function dumphex($bin) {
    dump(bin2hex($bin));
}

function dumpa($array) {
    foreach($array as $key => $value) {
        echo str_pad_left($key, 4)." => $value\n";
    }
}

function dumpd($var) { // diedump
    dump($var);
    die();
}

function dd($var) {
    dumpd($var);
}

function str_pad_left($string, $length) {
    return str_pad($string, $length, "0", 0);
}

function key2hex($key) {
    return hex2bin(hexr(str_pad_left(dechex($key), 4)));
}

function wthrzonearray2string($array) {
    $o = "";
    foreach($array as $key => $value) {
    $o.= str_pad_left($key+1, 3);
    foreach($value as $key2 => $value2) {
        $o.= " => ".str_pad($value2, 31, " ", 1);
    }
    $o = trim($o)."\n";
    return $o;
}
}