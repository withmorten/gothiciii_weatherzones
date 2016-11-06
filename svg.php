<?php
require_once('helpers.php');

$json = array();
$jsons = glob('G3_World_01\*.json');
$colors = json_decode(file_get_contents("colors.json"), TRUE);

foreach($jsons as $json_file) {
    $json_new = json_decode(file_get_contents($json_file), TRUE);
    $json = array_merge($json, $json_new);
}

$svg = svg_init(900, 800);

$xyz_scale = 500;

$rect = array("eEWeatherZoneShape_2D_Rect", "eEWeatherZoneShape_3D_Box");
$circle = array("eEWeatherZoneShape_2D_Circle", "eEWeatherZoneShape_3D_Sphere");

foreach($json as $guid => $weatherzone) {
    $x = ($weatherzone["X"] / $xyz_scale) + 500;
    $z = (($weatherzone["Z"] * -1) / $xyz_scale) + 400;
    
    $musiclocation = strtolower($weatherzone["MusicLocation"]);
    $color = $colors["colors"][$musiclocation];
    $style = array('fill' => $color);
    $attributes = array('class' => $musiclocation, 'id' => $guid);
    
    if     (in_array($weatherzone["Shape"], $rect))   $svg = svg_rect($svg, $x, $z, 4, 4, $style, $attributes);
    else if(in_array($weatherzone["Shape"], $circle)) $svg = svg_circle($svg, $x, $z, 2.5, $style, $attributes);
}

$svg = svg_exit($svg);

file_put_contents('SysDyn_{9A103CC2-4190-4DB3-9618-0419E5445AAD}.svg', $svg);
file_put_contents("weatherzones.txt", wthrzonearray2string($json));
?>
<html>
    <head>
        <title>G3 WeatherZones</title>
        <style type="text/css">
            body {
                margin:2px;
                font-family: Courier;
            }
            
            * { font-size: 12px; }
            
            object { position: absolute; }
            
            table { position: relative; }
        </style>
    </head>
    <body>
        <object data="SysDyn_{9A103CC2-4190-4DB3-9618-0419E5445AAD}.svg" type="image/svg+xml"></object>
        <?php echo colortable($colors); ?>
    </body>
<html>