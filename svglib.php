<?php
function svg_init($w, $h) {
    $svg = '<?xml version="1.0" encoding="utf-8"?>';
    $svg.= '<svg xmlns="http://www.w3.org/2000/svg" width="'.$w.'" height="'.$h.'">';
    return $svg;
}

function svg_exit() {
    return '</svg>';
}

function svg_node($tag, $attributes, $innerhtml = '') {
    $svg = '<'.$tag;
    if($attributes !== 0) foreach($attributes as $name => $value) $svg.= ' '.$name.'="'.$value.'"';
    if($innerhtml === '') $svg.= ' />';
    else $svg.= '>'.$innerhtml.'</'.$tag.'>';
    return $svg;
}

function svg_format($svg) {
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($svg);
    $dom->formatOutput = TRUE;
    $svg = $dom->saveXml();
    $svg = str_replace('/>', ' />', str_replace(' />', '  />', $svg)); // ugly AF, but svg is more readable
    return $svg;
}

// function svg_styles($styles_in) {
    // $styles_out = "";
    // foreach($styles_in as $property => $value) {
        // $styles_out.= $property.': '.$value.'; ';
    // }
    // return trim($styles_out);
// }