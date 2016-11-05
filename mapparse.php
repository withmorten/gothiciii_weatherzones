<?php
require_once('helpers.php');

$g3_world_01_files = array();
$g3_world_01_files = glob('G3_World_01\*.node');
$g3_world_01_files = array_merge($g3_world_01_files, glob('G3_World_01\*.lrentdat'));

foreach($g3_world_01_files as $g3_world_01_file) {

    $g3_world_01 = file_get_contents($g3_world_01_file);

    $strtable_offset =  bin2dec(substr($g3_world_01, 10, 4));                                           // offset to stringtable is always at 10, 4 bytes
    $strtable_deadbeef = bin2hexr(substr($g3_world_01, $strtable_offset, 4));                           // deadbeef string at start of stringtable, 4 bytes
    $strtable_count = bin2dec(substr($g3_world_01, $strtable_offset+5, 4));                             // strings in stringtable, 4 bytes
    $strtable_pos = $strtable_offset+9;                                                                 // offset to actual start of stringtable
    $strtable = array();
    $strtable_important[] = "InnerRadius";
    $strtable_important[] = "OuterRadius";

    if($strtable_deadbeef !== "deadbeef") {                                                             // if this isn't deadbeef something is probably wrong
        die("<pre>This file doesn't seem to be formatted properly or isn't a GENOMFLE ;)</pre>");
    }

    for($c = 0; $c < $strtable_count; $c++) {
        $strlen = bin2dec(substr($g3_world_01, $strtable_pos, 2));                                      // length of current string in stringtable, 2 bytes
        $string = substr($g3_world_01, $strtable_pos+2, $strlen);
        
        $strtable[$c] = $string;                                                                        // adds string and index to array
        
        $strtable_pos = $strtable_pos+2 + $strlen;                                                      // keeps track of where we are in the stringtable
    }

    $wthrzone_needle = hex2binr("010001010001").key2bin("eCWeatherZone_PS", $strtable);                 // WeatherZone class needle, hacky ...
    $wthrzone_lastpos = 0;
    $wthrzone_count = 0;
    $wthrzone_array = array();
    $wthrzone_shapes = array("2D_Circle", "2D_Rect", "3D_Sphere", "3D_Box");

    $musiclocation_needle = key2bin("MusicLocation", $strtable).key2bin("bCString", $strtable).hex2binr("001E");  // still hacky and weird
    $wthrzone_shape_needle = key2bin("Shape", $strtable).key2bin("bTPropertyContainer<enum eEWeatherZoneShape>", $strtable);
    $float_needle = key2bin("float", $strtable);
    $innerradius_needle = key2bin("InnerRadius", $strtable).$float_needle;
    $outerradius_needle = key2bin("OuterRadius", $strtable).$float_needle;

    while(($wthrzone_lastpos = strpos($g3_world_01, $wthrzone_needle, $wthrzone_lastpos)) !== FALSE) {  // occurences of WeatherZone class needle in file
        $entity_start = strrpos($g3_world_01, hex2bin("53000100"), -(strlen($g3_world_01) - $wthrzone_lastpos-1))-25; // parent entity of WeatherZone starts here
        $entity_guid = strtoupper(bin2hex(substr($g3_world_01, $entity_start+29, 20)));                 // GUID of parent entity, 20 bytes
        $entity_name = $strtable[bin2dec(substr($g3_world_01, $entity_start+66, 2))];             // our entity name, taken from stringtable via ID
        
        $entity_x = bin2float(substr($g3_world_01, $entity_start+66+50+0, 4));
        $entity_y = bin2float(substr($g3_world_01, $entity_start+66+50+4, 4));
        $entity_z = bin2float(substr($g3_world_01, $entity_start+66+50+8, 4));
        
        $wthrzone_start = $wthrzone_lastpos-2;                                                          // actual weatherzone class start
        $wthrzone_size = bin2dec(substr($g3_world_01, $wthrzone_start+17, 4))+21;                       // weatherzone class size until deadcode
        $wthrzone_substr = substr($g3_world_01, $wthrzone_start, $wthrzone_size);
        
        $wthrzone_music_start = strpos($wthrzone_substr, $musiclocation_needle);
        $wthrzone_music_size = bin2dec(substr($wthrzone_substr, $wthrzone_music_start+6, 4));
        $wthrzone_music_strkey = bin2dec(substr($wthrzone_substr, $wthrzone_music_start+10, $wthrzone_music_size));
        
        $wthrzone_shape_start = strpos($wthrzone_substr, $wthrzone_shape_needle);
        $wthrzone_shape_key = bin2dec(substr($wthrzone_substr, $wthrzone_shape_start+12, 4));
        
        $wthrzone_innerrad_start = strpos($wthrzone_substr, $innerradius_needle);
        $wthrzone_outerrad_start = strpos($wthrzone_substr, $outerradius_needle);
        $wthrzone_innerrad = bin2float(substr($wthrzone_substr, $wthrzone_innerrad_start+10, 4));
        $wthrzone_outerrad = bin2float(substr($wthrzone_substr, $wthrzone_outerrad_start+10, 4));
        
        $wthrzone_array[$entity_guid] = array("Name" => $entity_name,
                                              "MusicLocation" => $strtable[$wthrzone_music_strkey],
                                              "Shape" => 'eEWeatherZoneShape_'.$wthrzone_shapes[$wthrzone_shape_key],
                                              "X" => $entity_x,
                                              "Y" => $entity_y,
                                              "Z" => $entity_z,
                                              "InnerRadius" => $wthrzone_innerrad,
                                              "OuterRadius" => $wthrzone_outerrad);
        
        $wthrzone_count++;
        $wthrzone_lastpos += strlen($wthrzone_needle);
    }
    
    // dd(json_encode($wthrzone_array));

    file_put_contents($g3_world_01_file.".json", json_encode($wthrzone_array));
}