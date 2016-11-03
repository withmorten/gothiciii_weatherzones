<?php
include("helpers.php");
use JangoBrick\SVG\SVGImage;
use JangoBrick\SVG\Nodes\Shapes\SVGRect;
    
    // return (($xyz * -1) / $xyz_scale) + $xyz_offset;
// }

$xyz_samples[] = "AA493E0CE37A734B98B3EC4C3BE3BCF600000000";    // ArdeaLighthouse
$xyz_samples[] = "7E4589036752D24E9E5D875D42FAD17F00000000";    // MusicZone_Ardea
$xyz_samples[] = "D2139502E22280439EDA96F96F53691600000000";    // MusicZone_KapDun
$xyz_samples[] = "992F8C1D90C83542AD6489BB39ED37DD00000000";    // MusicZone_KapDun
$xyz_samples[] = "F7D6BE8A9CAD4543909BFCAC9418408200000000";    // MusicZone_KapDun

$json = json_decode(file_get_contents($argv[1].".json"), TRUE);

$svg = new SVGImage(1500, 1500);
$doc = $svg->getDocument();

$xyz_scale = 500;
$xyz_offset = 900;

foreach($json as $entity) {
    if(!in_array($entity["GUID"], $xyz_samples)) continue;
    
    $x = ($entity["X"] / $xyz_scale) + $xyz_offset;
    $z = (($entity["Z"] * -1) / $xyz_scale) + $xyz_offset;
    
    // $x = scalexyz($entity["X"]);
    // $z = scalexyz($entity["Z"] * -1);
    
    dump($x);
    dump($z);
    
    $marker = new SVGRect($x, $z, 4, 4);
    $marker->setStyle('fill', '#000000');
    $doc->addChild($marker);
}

file_put_contents($argv[1].".svg", $svg);