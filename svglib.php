<?php
function svg_init($w, $h) {
    $svg = '<?xml version="1.0" encoding="utf-8"?>';
    $svg.= '<svg xmlns="http://www.w3.org/2000/svg" width="'.$w.'" height="'.$h.'">';
    return $svg;
}

function svg_exit($svg, $format = TRUE) {
    $svg.= '</svg>';
    if($format === TRUE) return svg_format($svg);
    else return $svg;
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

function svg_styles($styles_in) {
    $styles_out = "";
    foreach($styles_in as $property => $value) {
        $styles_out.= $property.': '.$value.'; ';
    }
    return trim($styles_out);
}

function svg_attributes($attributes_in) {
    $attribute_out = "";
    foreach($attributes_in as $attribute => $content) {
        $attribute_out.= $attribute.'="'.$content.'" ';
    }
    return $attribute_out;
}

function svg_title($title) {
    if($title !== '') {
        return '<title>'.$title.'</title>';
    } else {
        return '';
    }
}

function svg_rect($svg, $x, $y, $w, $h, $style, $attributes, $title = '') {
    $style = svg_styles($style);
    $attributes = svg_attributes($attributes);
    
    $svg.= '<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" ';
    if($style !== "") $svg.= 'style="'.$style.'" ';
    $svg.= $attributes;
    $svg.= '>';
    $svg.= svg_title($title);
    $svg.= '</rect>';
    return $svg;
}

function svg_circle($svg, $cx, $cy, $r, $style, $attributes, $title = '') {
    $style = svg_styles($style);
    $attributes = svg_attributes($attributes);
    
    $svg.= '<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" ';
    if($style !== "") $svg.= 'style="'.$style.'" ';
    $svg.= $attributes;
    $svg.= '>';
    $svg.= svg_title($title);
    $svg.='</circle>';
    return $svg;
}