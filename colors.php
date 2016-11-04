<?php
require_once('helpers.php');

$colors["vistapoint"] = "#008000";
$colors["vistaharp"] = "#00FF00";
$colors["slaves"] = "#800080";
$colors["vengard"] = "#FF0000";
$colors["sadstrings"] = "#800000";
$colors["braga"] = "#CCCC00";
$colors["ishtar"] = "#CCCC33";
$colors["varant"] = "#FFFF00";
$colors["PathToVarant"] = "#FFFF99";
$colors["dungeon"] = "#C0C0C0";
$colors["beliartemple"] = "#808080";
$colors["deathvalley"] = "#FFFF66";
$colors["ruinfields"] = "#CCCC33";
$colors["thedig"] = "#CC6600";
$colors["orcmine01"] = "#000099";
$colors["locationidyllic"] = "#33FF00";
$colors["locationspooky"] = "#660066";
$colors["xardasoutdoor"] = "#6600CC";
$colors["trelis"] = "#FF0000";
$colors["faring"] = "#FF0000";
$colors["myrtana"] = "#000000";
$colors["silden"] = "#99FF99";
$colors["locationgothic1theme"] = "#66FFFF";
$colors["highlands"] = "#00CCFF";
$colors["xardasindoor"] = "#FF0000";
$colors["northmarhigh"] = "#0000FF";
$colors["northmarlow"] = "#0066CC";
$colors["monastery"] = "#800000";
$colors["crystalcave"] = "#E0E0E0";
$colors["orccamp"] = "#FF0000";
$colors["ominouswoods"] = "#33CC00";
$colors["arena"] = "#FF9966";

$html_out = '<table>';
$c = 1;

foreach($colors as $music => $color) {
    if(($c % 2) == 1) {
        $tr_in = '<tr>';
        $tr_out = '';
    } else {
        $tr_in = '';
        $tr_out = '</tr>';
    }
    
    $hsl = RGBToHSL(HTMLToRGB($color));
    
    if($hsl->lightness > 125) {
        $fontcolor = "black";
    } else {
        $fontcolor = "white";
    }
    
    $html_out.= $tr_in.'<td style="background-color: '.$color.'; width: 130px; color:'.$fontcolor.'">'.$music.'</td>'.$tr_out;
    
    $c++;
}

// echo $html_out;