<?php
require_once("C:\msys\home\morten\php-svg\autoloader.php");
require_once('colors.php');

function bin2dec($bin) {
    return hexdec(bin2hex(strrev($bin)));
}

function bin2float($bin) {
    return hex2float(bin2hex(strrev($bin)));
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

function dd($var, $pre = FALSE) {
    if($pre !== FALSE) {
        $pre_in = "<pre>";
        $pre_out = "</pre>";
    }
    echo $pre_in;
    dumpd($var);
    echo $pre_out;
}

function str_pad_left($string, $length) {
    return str_pad($string, $length, "0", 0);
}

function key2hex($key) {
    return hex2bin(hexr(str_pad_left(dechex($key), 4)));
}

function wthrzonearray2string($array) {
    $o = "";
    $forbidden = array("X", "Y", "Z");
    
    foreach($array as $key => $value) {
        $o.= str_pad_left($key+1, 3);
        foreach($value as $key2 => $value2) {
            if(!in_array($key2, $forbidden)) {
                $o.= " => ".str_pad($value2, 31, " ", 1);
            }
        }
        $o = trim($o)."\n";
    }
    
    return $o;
}

function hex2float($number) {
    $binfinal = sprintf("%032b",hexdec($number));
    $sign = substr($binfinal, 0, 1);
    $exp = substr($binfinal, 1, 8);
    $mantissa = "1".substr($binfinal, 9);
    $mantissa = str_split($mantissa);
    $exp = bindec($exp)-127;
    $significand=0;
    for ($i = 0; $i < 24; $i++) {
        $significand += (1 / pow(2,$i))*$mantissa[$i];
    }
    return $significand * pow(2,$exp) * ($sign*-2+1);
}