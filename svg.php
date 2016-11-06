<?php
require_once('helpers.php');
const MOD = "MOD_";

$json = array();
$jsons = glob('G3_World_01\*.json');

if(isset($_GET["mod"]) && (int)$_GET["mod"] === 1) {
    $modprefix = MOD;
    $modsuffix = "_mod";
    $mod = 1;
} else {
    $modprefix = "";
    $modsuffix = "";
    $mod = 0;
}

$colors = json_decode(file_get_contents('colors'.$modsuffix.'.json'), TRUE);

foreach($jsons as $json_file) {
    if($mod === 0 && strpos($json_file, MOD)) continue;
    if($mod === 1 && strpos($json_file, "lrentdat") && !strpos($json_file, MOD)) continue;
    
    $json_new = json_decode(file_get_contents($json_file), TRUE);
    $json = array_merge($json, $json_new);
}

$svg = svg_init(900, 800);

$xyz_scale = 500;

$rect = array("eEWeatherZoneShape_2D_Rect", "eEWeatherZoneShape_3D_Box");
$circle = array("eEWeatherZoneShape_2D_Circle", "eEWeatherZoneShape_3D_Sphere");

foreach($json as $guid => $weatherzone) {
    $x = ($weatherzone["X"] / $xyz_scale) + 600;
    $z = (($weatherzone["Z"] * -1) / $xyz_scale) + 350;
    
    $musiclocation = strtolower($weatherzone["MusicLocation"]);
    $color = $colors["colors"][$musiclocation];
    $style = array('fill' => $color);
    $attributes = array('class' => $musiclocation, 'id' => $guid);
    
    if     (in_array($weatherzone["Shape"], $rect))   $svg = svg_rect($svg, $x, $z, 4, 4, $style, $attributes);
    else if(in_array($weatherzone["Shape"], $circle)) $svg = svg_circle($svg, $x, $z, 2.5, $style, $attributes);
}

$svg = svg_exit($svg);

file_put_contents($modprefix.'SysDyn_{9A103CC2-4190-4DB3-9618-0419E5445AAD}.svg', $svg);
file_put_contents("weatherzones".$modsuffix.".txt", wthrzonearray2string($json));
?>
<html>
    <head>
        <title>G3 WeatherZones</title>
        <link href="svg.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="svg.js"></script>
    </head>
    <body>
        <object id="svg" data="<?php echo $modprefix; ?>SysDyn_{9A103CC2-4190-4DB3-9618-0419E5445AAD}.svg" type="image/svg+xml"></object>
        <?php echo colortable($colors); ?>
    </body>
<html>