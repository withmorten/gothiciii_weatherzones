<?php
require_once('helpers.php');

$colors = json_decode(file_get_contents("colors.json"), TRUE);

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
        
        $html_out.= $tr_in.'<td style="background-color: '.$color.'; width: 130px; color:'.$fontcolor.'">'.$music.'</td>'.$tr_out;
        
        $c++;
    }
    
    $html_out.= "</table>\n";
    
    return $html_out;
}