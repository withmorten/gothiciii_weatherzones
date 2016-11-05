<?php
function svg_init($w, $h) {
    $svg = '<?xml version="1.0" encoding="utf-8"?>';
    $svg.= '<svg xmlns="http://www.w3.org/2000/svg" width="'.$w.'" height="'.$h.'">';
    return $svg;
}

function svg_exit($svg) {
    $svg.= '</svg>';
    return svg_format($svg);
}

function svg_format($svg) {
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($svg);
    $dom->formatOutput = TRUE;
    return $dom->saveXml();
}

function svg_style($styles_in) {
    $styles_out = "";
    foreach($styles_in as $property => $value) {
        $styles_out.= $property.': '.$value.'; ';
    }
    return trim($styles_out);
}

function svg_rect($svg, $x, $y, $w, $h, $style) {
    $style = svg_style($style);
    $svg.= '<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" ';
    if($style !== "") $svg.= 'style="'.$style.'" ';
    $svg.= '/>';
    return $svg;
}

function svg_circle($svg, $cx, $cy, $r, $style) {
    $style = svg_style($style);
    $svg.= '<circle cx="'.$cx.'" cy="'.$cy.'" r="'.$r.'" ';
    if($style !== "") $svg.= 'style="'.$style.'" ';
    $svg.= '/>';
    return $svg;
}