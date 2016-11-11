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

file_put_contents("weatherzones".$modsuffix.".txt", wthrzonearray2string($json));

aasort($json, "SVGRadius");
$json = array_reverse($json);

$svg = svg_init(1400, 800);

foreach($json as $guid => $weatherzone) {
    $musiclocation = strtolower($weatherzone["MusicLocation"]);
    
    $attributes = xyrwh($weatherzone);
    $attributes['fill'] = $colors["colors"][$musiclocation];
    $attributes['class'] = $musiclocation;
    $attributes['id'] = $guid;
    $attributes['title'] = $weatherzone["Name"];
    $tag = $weatherzone["SVGShape"] === CIRCLE ? CIRCLE : RECT;
    $title = svg_node(TITLE, 0, $weatherzone["Name"]."\n".$musiclocation."\n".$guid);
    
    $svg.= svg_node($tag, $attributes, $title);
}

$svg.= svg_exit();
$svg = svg_format($svg);

file_put_contents($modprefix.'SysDyn_{9A103CC2-4190-4DB3-9618-0419E5445AAD}.svg', $svg);
?>
<html>
    <head>
        <title>G3 WeatherZones</title>
        <link href="svg.css" rel="stylesheet" type="text/css" />
        <script src="svg.js" type="text/javascript"></script>
    </head>
    <body>
        <!--<img id="g3_hud_maps_03" src="g3_hud_maps_03_waifu2x_art_noise1_scale_tta_1.png" />-->
        <object id="svg" data="<?php echo $modprefix; ?>SysDyn_{9A103CC2-4190-4DB3-9618-0419E5445AAD}.svg" type="image/svg+xml"></object>
        <?php echo colortable($colors); ?>
    </body>
<html>