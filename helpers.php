<?php
require_once('colors.php');
require_once('svglib.php');

const PRE = TRUE;

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
    if(PRE === TRUE) echo "<pre>";
    var_dump($var);
    if(PRE === TRUE) echo "</pre>";
}

function dumphex($bin) {
    if(PRE === TRUE) echo "<pre>";
    dump(bin2hex($bin));
    if(PRE === TRUE) echo "<pre>";
}

function dumpa($array) {
    if(PRE === TRUE) echo "<pre>";
    foreach($array as $key => $value) {
        echo str_pad_left($key, 4)." => $value\n";
    }
    if(PRE === TRUE) echo "<pre>";
}

function dumpd($var) { // diedump
    if(PRE === TRUE) echo "<pre>";
    dump($var);
    die();
}

function dd($var) {
    if(PRE === TRUE) echo "<pre>";
    dumpd($var);
}

function str_pad_left($string, $length) {
    return str_pad($string, $length, "0", 0);
}

function key2bin($string, $strtable) {
    $key = array_search($string, $strtable);
    return hex2bin(hexr(str_pad_left(dechex($key), 4)));
}

function wthrzonearray2string($array) {
    $o = "";
    $allowed = array("Name", "MusicLocation");
    $c = 0;
    
    foreach($array as $key => $value) {
        $o.= str_pad_left($c+1, 3)." => ".$key;
        foreach($value as $key2 => $value2) {
            if(in_array($key2, $allowed)) {
                $o.= " => ".str_pad($value2, 37, " ", 1);
            }
        }
        $o = trim($o)."\n";
        $c++;
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