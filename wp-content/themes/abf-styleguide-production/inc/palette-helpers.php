<?php
/**
 * Palette Helper: Farb-Berechnungen (HEX/RGB/Tints/CMYK/WCAG)
 */

if (!defined('ABSPATH')) {
	exit;
}

// --- HEX / RGB ---

function abf_hex_to_rgb($hex) {
	$hex = trim((string)$hex);
	if ($hex === '') { return null; }
	if ($hex[0] === '#') { $hex = substr($hex, 1); }
	if (strlen($hex) !== 6) { return null; }
	$r = hexdec(substr($hex, 0, 2));
	$g = hexdec(substr($hex, 2, 2));
	$b = hexdec(substr($hex, 4, 2));
	return array('r' => $r, 'g' => $g, 'b' => $b);
}

function abf_rgb_to_hex($r, $g, $b) {
	$r = max(0, min(255, (int)$r));
	$g = max(0, min(255, (int)$g));
	$b = max(0, min(255, (int)$b));
	return '#' . strtoupper(str_pad(dechex($r), 2, '0', STR_PAD_LEFT)
		. str_pad(dechex($g), 2, '0', STR_PAD_LEFT)
		. str_pad(dechex($b), 2, '0', STR_PAD_LEFT));
}

// --- Tints (auf Weiß, keine Opacity) ---

function abf_tint_on_white($rgb_base, $t) {
	if (!is_array($rgb_base) || !isset($rgb_base['r'])) { return null; }
	$t = (float)$t;
	$r = round(255 - (255 - (int)$rgb_base['r']) * $t);
	$g = round(255 - (255 - (int)$rgb_base['g']) * $t);
	$b = round(255 - (255 - (int)$rgb_base['b']) * $t);
	return array('r' => (int)$r, 'g' => (int)$g, 'b' => (int)$b);
}

// --- CMYK Skalierung ---

function abf_parse_cmyk($cmyk_text) {
	if (!$cmyk_text) { return null; }
	$parts = explode('|', $cmyk_text);
	if (count($parts) !== 4) { return null; }
	return array(
		'c' => (int)$parts[0],
		'm' => (int)$parts[1],
		'y' => (int)$parts[2],
		'k' => (int)$parts[3],
	);
}

function abf_format_cmyk($cmyk) {
	if (!is_array($cmyk)) { return ''; }
	return sprintf('%d|%d|%d|%d', (int)$cmyk['c'], (int)$cmyk['m'], (int)$cmyk['y'], (int)$cmyk['k']);
}

function abf_cmyk_scale($cmyk_100, $t) {
	$cmyk = is_string($cmyk_100) ? abf_parse_cmyk($cmyk_100) : $cmyk_100;
	if (!is_array($cmyk)) { return null; }
	$t = (float)$t;
	return array(
		'c' => (int)round($cmyk['c'] * $t),
		'm' => (int)round($cmyk['m'] * $t),
		'y' => (int)round($cmyk['y'] * $t),
		'k' => (int)round($cmyk['k'] * $t),
	);
}

// --- WCAG Kontrast ---

function abf_srgb_to_linear($c) {
	$c = $c / 255;
	return ($c <= 0.03928) ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
}

function abf_relative_luminance($rgb) {
	if (!is_array($rgb)) { return 0; }
	$r = abf_srgb_to_linear((int)$rgb['r']);
	$g = abf_srgb_to_linear((int)$rgb['g']);
	$b = abf_srgb_to_linear((int)$rgb['b']);
	return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

function abf_contrast_ratio($rgb1, $rgb2) {
	$l1 = abf_relative_luminance($rgb1);
	$l2 = abf_relative_luminance($rgb2);
	$light = max($l1, $l2);
	$dark = min($l1, $l2);
	return ($light + 0.05) / ($dark + 0.05);
}

function abf_recommended_text_color($rgb_bg) {
	$white = array('r' => 255, 'g' => 255, 'b' => 255);
	$black = array('r' => 0, 'g' => 0, 'b' => 0);
	$contrast_white = abf_contrast_ratio($rgb_bg, $white);
	$contrast_black = abf_contrast_ratio($rgb_bg, $black);
	return ($contrast_white > $contrast_black)
		? array('color' => 'white', 'ratio' => $contrast_white)
		: array('color' => 'black', 'ratio' => $contrast_black);
}

// Hilfsfunktion: Wert je Stufe vorbereiten (inkl. Overrides)
function abf_palette_compute_shades($color_row) {
	$hex100 = isset($color_row['hex_100']) ? $color_row['hex_100'] : '';
	$rgb100 = abf_hex_to_rgb($hex100);
	$cmyk100 = isset($color_row['cmyk_100']) ? $color_row['cmyk_100'] : '';

	$stufen = array(
		'100' => 1.00,
		'80' => 0.80,
		'60' => 0.60,
		'40' => 0.40,
		'25' => 0.25,
	);

	$result = array();
	foreach ($stufen as $key => $t) {
		$hex_key = 'hex_' . $key;
		$rgb_key = 'rgb_' . $key;
		$cmyk_key = 'cmyk_' . $key;
		$txt_key = 'text_color_' . $key;

		$rgb = null;
		$hex = null;
		$cmyk = null;

		if (!empty($color_row[$hex_key])) {
			$hex = $color_row[$hex_key];
			$rgb = abf_hex_to_rgb($hex);
		} elseif ($key === '100') {
			$rgb = $rgb100;
			$hex = $hex100;
		} else {
			$rgb = abf_tint_on_white($rgb100, $t);
			$hex = abf_rgb_to_hex($rgb['r'], $rgb['g'], $rgb['b']);
		}

		if (!empty($color_row[$cmyk_key])) {
			$cmyk = abf_parse_cmyk($color_row[$cmyk_key]);
		} elseif ($key === '100') {
			$cmyk = abf_parse_cmyk($cmyk100);
		} else {
			$cmyk = abf_cmyk_scale($cmyk100, $t);
		}

		$rec = abf_recommended_text_color($rgb);
		$text_color = isset($color_row[$txt_key]) && $color_row[$txt_key] ? $color_row[$txt_key] : 'auto';
		if ($text_color === 'auto') {
			$text_color = $rec['color'];
		}

		$result[$key] = array(
			'hex' => $hex,
			'rgb' => $rgb,
			'cmyk' => $cmyk,
			'text_color' => $text_color,
			'contrast_white' => abf_contrast_ratio($rgb, array('r'=>255,'g'=>255,'b'=>255)),
			'contrast_black' => abf_contrast_ratio($rgb, array('r'=>0,'g'=>0,'b'=>0)),
			'recommended' => $rec,
		);
	}

	return $result;
}


