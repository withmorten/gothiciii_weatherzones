<?php
require_once('helpers.php');

$error = "This file doesn't seem to be formatted properly or isn't a GENOMFLE ;)";

$g3_world_01 = file_get_contents($argv[1]);

$strtable_offset =  bin2dec(substr($g3_world_01, 10, 4));                                           // offset to stringtable is always at 10, 4 bytes
$strtable_deadbeef = bin2hexr(substr($g3_world_01, $strtable_offset, 4));                           // deadbeef string at start of stringtable, 4 bytes
$strtable_count = bin2dec(substr($g3_world_01, $strtable_offset+5, 4));                             // strings in stringtable, 4 bytes
$strtable_pos = $strtable_offset+9;                                                                 // offset to actual start of stringtable
$strtable_array = array();

if($strtable_deadbeef !== "deadbeef") {                                                             // if this isn't deadbeef something is probably wrong
    die($strtable_deadbeef." ".$error);
}

for($c = 0; $c < $strtable_count; $c++) {
    $strlen = bin2dec(substr($g3_world_01, $strtable_pos, 2));                                      // length of current string in stringtable, 2 bytes
    $string = substr($g3_world_01, $strtable_pos+2, $strlen);
    
    $strtable_array[$c] = $string;                                                                  // adds string and index to array
    
    switch($string) {
        case "eCWeatherZone_PS":
            $wthrzone_key = $c;
            break;
        case "MusicLocation":
            $musiclocation_key = $c;
            break;
        case "bCString":
            $bcstring_key = $c;
            break;
    }
    
    $strtable_pos = $strtable_pos+2 + $strlen;                                                      // keeps track of where we are in the stringtable
}

$wthrzone_needle = hex2binr("010001010001").key2hex($wthrzone_key);                                 // WeatherZone class needle, hacky ...
$wthrzone_lastpos = 0;
$wthrzone_count = 0;
$wthrzone_array = array();

$musiclocation_needle = key2hex($musiclocation_key).key2hex($bcstring_key).hex2binr("001E");        // still hacky and weird

while(($wthrzone_lastpos = strpos($g3_world_01, $wthrzone_needle, $wthrzone_lastpos)) !== FALSE) {  // cycle through occurences of WeatherZone class needle in file
    $entity_start = strrpos($g3_world_01, hex2bin("53000100"), -(strlen($g3_world_01) - $wthrzone_lastpos-1))-25; // parent entity of WeatherZone starts here
    $entity_guid = strtoupper(bin2hex(substr($g3_world_01, $entity_start+29, 20)));                 // GUID of parent entity, 20 bytes
    $entity_name = $strtable_array[bin2dec(substr($g3_world_01, $entity_start+66, 2))];             // our entity name, taken from stringtable via ID
    
    $entity_x = bin2float(substr($g3_world_01, $entity_start+66+50, 4));
    $entity_y = bin2float(substr($g3_world_01, $entity_start+66+54, 4));
    $entity_z = bin2float(substr($g3_world_01, $entity_start+66+58, 4));
    
    $wthrzone_start = $wthrzone_lastpos-2;                                                          // actual weatherzone class start
    $wthrzone_size = bin2dec(substr($g3_world_01, $wthrzone_start+17, 4))+21;                       // weatherzone class size until deadcode
    
    $wthrzone_substr = substr($g3_world_01, $wthrzone_start, $wthrzone_size);
    $wthrzone_music_start = strpos($wthrzone_substr, $musiclocation_needle);
    
    $wthrzone_music_size = bin2dec(substr($wthrzone_substr, $wthrzone_music_start+6, 4));
    $wthrzone_music_strkey = bin2dec(substr($wthrzone_substr, $wthrzone_music_start+10, $wthrzone_music_size));
    
    $wthrzone_array[$wthrzone_count] = array("GUID" => $entity_guid,
                                             "Entity" => $entity_name,
                                             "MusicLocation" => $strtable_array[$wthrzone_music_strkey],
                                             "X" => $entity_x,
                                             "Y" => $entity_y,
                                             "Z" => $entity_z);
    
    $wthrzone_count++;
    $wthrzone_lastpos += strlen($wthrzone_needle);
}

file_put_contents($argv[1].".json", json_encode($wthrzone_array));
// file_put_contents("weatherzones.txt", wthrzonearray2string($wthrzone_array));