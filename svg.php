<?php
require_once('helpers.php');

use JangoBrick\SVG\SVGImage;
use JangoBrick\SVG\Nodes\Shapes\SVGRect;

$json = array();
$jsons = glob('G3_World_01\*.json');

foreach($jsons as $json_file) {
    $json_new = json_decode(file_get_contents($json_file), TRUE);
    $json = array_merge($json, $json_new);
}

$svg = new SVGImage(900, 800);
$doc = $svg->getDocument();

$xyz_scale = 500;

foreach($json as $guid => $weatherzone) {
    $x = ($weatherzone["X"] / $xyz_scale) + 500;
    $z = (($weatherzone["Z"] * -1) / $xyz_scale) + 400;
    
    $marker = new SVGRect($x, $z, 4, 4);
    
    $musiclocation = strtolower($weatherzone["MusicLocation"]);
    $color = $colors["colors"][$musiclocation];
    
    $marker->setStyle('fill', $color);
    $doc->addChild($marker);
}

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