<?php
require_once('svglib.php');

error_reporting(E_ALL);
const PRE = TRUE;

const CIRCLE = "circle";
const RECT = "rect";
const TITLE = "title";

const SCALE = 500;
const X_OFF = 600;
const Y_OFF = 350;
const MINR = 2.5;
const MINWH = MINR * 1.6;

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

function xyrwh($weatherzone) {
    $xyrwh = array();
    $radius = $weatherzone["SVGRadius"] / SCALE;
    if($weatherzone["SVGShape"] === CIRCLE) {
        $xyrwh["r"] = ($radius > MINR ? $radius : MINR);
        $xyrwh["cx"] = ($weatherzone["X"] / SCALE) + X_OFF;
        $xyrwh["cy"] = (($weatherzone["Z"] * -1) / SCALE) + Y_OFF;
    } else {
        $xyrwh["width"] = ($radius > MINWH ? $radius : MINWH);
        $xyrwh["height"] = $xyrwh["width"];
        $xyoff = $xyrwh["width"] / 2;
        $xyrwh["x"] = (($weatherzone["X"] / SCALE) - $xyoff) + X_OFF;
        $xyrwh["y"] = ((($weatherzone["Z"] * -1) / SCALE) - $xyoff) + Y_OFF;
    }
    return $xyrwh;
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

function colortable($colors) {

    $html_out = '<table>';
    $c = 1;

    foreach($colors["colors"] as $music => $color) {
        if(($c % 2) == 1) {
            $tr_in = '<tr>';
            $tr_out = '';
        } else {
            $tr_in = '';
            $tr_out = '</tr>';
        }
        
        if(in_array($music, $colors["fontcolors"])) {
            $fontcolor = "black";
        } else {
            $fontcolor = "white";
        }
        
        $html_out.= $tr_in.'<td style="background-color: '.$color.'; color:'.$fontcolor.'">';
        $html_out.= '<label onclick="toggleCheckbox(event);">'.$music;
        $html_out.= '<input type="checkbox" id="'.$music.'" checked />';
        $html_out.= '</label></td>'.$tr_out."\n\t\t";
        
        $c++;
    }
    
    $html_out.= "</table>\n";
    
    return $html_out;
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

function aasort(&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

// debug shit from mapparse.pph

// if(!in_array($wthrzone_shape, $wthrzone_circle)) {
    // if($wthrzone_innerrad !== $wthrzone_outerrad) {
    // echo "<tr><td>$entity_name</td><td>".str_replace("eEWeatherZoneShape_", "", $wthrzone_shape)."</td><td>$wthrzone_innerrad</td><td>$wthrzone_outerrad</td><td>$entity_bcbox</td><td>$wthrzone_svg_radius</td>";
// }}

// <style type="text/css">
    // * {
        // font-family: Courier;
        // font-size: 12px;
    // }
    
    // table {
        // border: 1px solid black;
        // border-collapse: collapse;
    // }
    
    // td {
        // border: 1px solid black;
        // padding: 2px 5px;
        // border-collapse: collapse;
    // }
// </style>
// <table>