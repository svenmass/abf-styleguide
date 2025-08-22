<?php
/**
 * CPT: ABF-Farben (Palette)
 * - Intern nutzbarer Custom Post Type zur Pflege wiederverwendbarer Farbpaletten
 * - Wird im Admin angezeigt, ist aber nicht öffentlich abfragbar
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Registriere Post Type "abf-farben"
 */
function abf_register_cpt_abf_farben() {
	$labels = array(
		'name' => __('ABF-Farben', 'abf-styleguide'),
		'singular_name' => __('ABF-Farben', 'abf-styleguide'),
		'add_new' => __('Neu hinzufügen', 'abf-styleguide'),
		'add_new_item' => __('Neue Palette hinzufügen', 'abf-styleguide'),
		'edit_item' => __('Palette bearbeiten', 'abf-styleguide'),
		'new_item' => __('Neue Palette', 'abf-styleguide'),
		'view_item' => __('Palette ansehen', 'abf-styleguide'),
		'search_items' => __('Paletten durchsuchen', 'abf-styleguide'),
		'not_found' => __('Keine Paletten gefunden', 'abf-styleguide'),
		'not_found_in_trash' => __('Keine Paletten im Papierkorb', 'abf-styleguide'),
		'menu_name' => __('ABF-Farben', 'abf-styleguide'),
	);

	$args = array(
		'label' => __('ABF-Farben', 'abf-styleguide'),
		'labels' => $labels,
		'public' => false,
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_rest' => false,
		'menu_icon' => 'dashicons-art',
		'supports' => array('title'),
		'rewrite' => false,
	);

	register_post_type('abf-farben', $args);
}
add_action('init', 'abf_register_cpt_abf_farben');

/**
 * ACF Feldgruppe: Palette – Farben (auf CPT "abf-farben")
 * - Defensive Checks und deklarative Validierungen
 */
function abf_register_acf_palette_fields() {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_abf_palette_colors',
		'title' => 'Palette – Farben',
		'fields' => array(
			array(
				'key' => 'field_palette_name',
				'label' => 'Palettenname',
				'name' => 'palette_name',
				'type' => 'text',
				'instructions' => 'Interner Name der Palette.',
				'required' => 0,
			),
			array(
				'key' => 'field_palette_colors',
				'label' => 'Farben',
				'name' => 'colors',
				'type' => 'repeater',
				'layout' => 'row',
				'button_label' => 'Farbe hinzufügen',
				'sub_fields' => array(
					array(
						'key' => 'field_color_label',
						'label' => 'Bezeichnung',
						'name' => 'label',
						'type' => 'text',
						'required' => 1,
					),
					array(
						'key' => 'field_color_slug',
						'label' => 'Slug (Anker)',
						'name' => 'slug',
						'type' => 'text',
						'instructions' => 'Automatisch aus Bezeichnung. Zum Überschreiben leer lassen oder anpassen.',
					),
					array(
						'key' => 'field_color_hex_100',
						'label' => 'HEX 100%',
						'name' => 'hex_100',
						'type' => 'text',
						'required' => 1,
						'placeholder' => '#RRGGBB',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
					),
					array(
						'key' => 'field_color_text_color_100',
						'label' => 'Schriftfarbe 100%',
						'name' => 'text_color_100',
						'type' => 'select',
						'choices' => array(
							'auto' => 'Auto',
							'black' => 'Schwarz',
							'white' => 'Weiß',
						),
						'allow_null' => 1,
						'default_value' => 'auto',
					),
					array(
						'key' => 'field_color_cmyk_100',
						'label' => 'CMYK 100%',
						'name' => 'cmyk_100',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
					),
					array(
						'key' => 'field_color_pantone',
						'label' => 'Pantone',
						'name' => 'pantone',
						'type' => 'text',
					),
					// Optional Overrides pro Stufe
					array(
						'key' => 'field_color_hex_80',
						'label' => 'HEX 80%',
						'name' => 'hex_80',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
					),
					array(
						'key' => 'field_color_hex_60',
						'label' => 'HEX 60%',
						'name' => 'hex_60',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
					),
					array(
						'key' => 'field_color_hex_40',
						'label' => 'HEX 40%',
						'name' => 'hex_40',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
					),
					array(
						'key' => 'field_color_hex_25',
						'label' => 'HEX 25%',
						'name' => 'hex_25',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
					),
					array(
						'key' => 'field_color_rgb_80',
						'label' => 'RGB 80% (Override)',
						'name' => 'rgb_80',
						'type' => 'text',
						'placeholder' => 'R|G|B',
					),
					array(
						'key' => 'field_color_rgb_60',
						'label' => 'RGB 60% (Override)',
						'name' => 'rgb_60',
						'type' => 'text',
						'placeholder' => 'R|G|B',
					),
					array(
						'key' => 'field_color_rgb_40',
						'label' => 'RGB 40% (Override)',
						'name' => 'rgb_40',
						'type' => 'text',
						'placeholder' => 'R|G|B',
					),
					array(
						'key' => 'field_color_rgb_25',
						'label' => 'RGB 25% (Override)',
						'name' => 'rgb_25',
						'type' => 'text',
						'placeholder' => 'R|G|B',
					),
					array(
						'key' => 'field_color_cmyk_80',
						'label' => 'CMYK 80% (Override)',
						'name' => 'cmyk_80',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
					),
					array(
						'key' => 'field_color_cmyk_60',
						'label' => 'CMYK 60% (Override)',
						'name' => 'cmyk_60',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
					),
					array(
						'key' => 'field_color_cmyk_40',
						'label' => 'CMYK 40% (Override)',
						'name' => 'cmyk_40',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
					),
					array(
						'key' => 'field_color_cmyk_25',
						'label' => 'CMYK 25% (Override)',
						'name' => 'cmyk_25',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
					),
					array(
						'key' => 'field_color_text_color_80',
						'label' => 'Schriftfarbe 80% (Override)',
						'name' => 'text_color_80',
						'type' => 'select',
						'choices' => array(
							'auto' => 'Auto',
							'black' => 'Schwarz',
							'white' => 'Weiß',
						),
						'allow_null' => 1,
					),
					array(
						'key' => 'field_color_text_color_60',
						'label' => 'Schriftfarbe 60% (Override)',
						'name' => 'text_color_60',
						'type' => 'select',
						'choices' => array(
							'auto' => 'Auto',
							'black' => 'Schwarz',
							'white' => 'Weiß',
						),
						'allow_null' => 1,
					),
					array(
						'key' => 'field_color_text_color_40',
						'label' => 'Schriftfarbe 40% (Override)',
						'name' => 'text_color_40',
						'type' => 'select',
						'choices' => array(
							'auto' => 'Auto',
							'black' => 'Schwarz',
							'white' => 'Weiß',
						),
						'allow_null' => 1,
					),
					array(
						'key' => 'field_color_text_color_25',
						'label' => 'Schriftfarbe 25% (Override)',
						'name' => 'text_color_25',
						'type' => 'select',
						'choices' => array(
							'auto' => 'Auto',
							'black' => 'Schwarz',
							'white' => 'Weiß',
						),
						'allow_null' => 1,
					),
					array(
						'key' => 'field_color_notes',
						'label' => 'Notizen',
						'name' => 'notes',
						'type' => 'textarea',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'abf-farben',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'active' => true,
	));
}
add_action('acf/init', 'abf_register_acf_palette_fields');

/**
 * Generiere fehlende/uneindeutige Slugs je Farben‑Zeile automatisch aus dem Label
 */
function abf_palette_generate_color_slugs($post_id) {
	// Nur für unseren CPT
	if (get_post_type($post_id) !== 'abf-farben') {
		return;
	}

	if (!function_exists('have_rows')) {
		return;
	}

	if (!have_rows('colors', $post_id)) {
		return;
	}

	$used = array();
	$index = 0;
	while (have_rows('colors', $post_id)) { the_row(); $index++; }

	// Zweiter Durchlauf zum Setzen (ACF benötigt 1-basierte Indizes)
	for ($i = 1; $i <= $index; $i++) {
		$label = get_sub_field('label', $post_id);
	}

	// Hole alle Zeilen als Array, verarbeite, schreibe zurück
	$rows = get_field('colors', $post_id);
	if (!is_array($rows)) {
		return;
	}

	foreach ($rows as $k => $row) {
		$base = isset($row['label']) ? sanitize_title($row['label']) : '';
		$slug = isset($row['slug']) && $row['slug'] ? sanitize_title($row['slug']) : '';
		if (!$base && !$slug) {
			continue;
		}
		$target = $slug ? $slug : $base;
		$unique = $target;
		$suffix = 2;
		while (in_array($unique, $used, true)) {
			$unique = $target . '-' . $suffix;
			$suffix++;
		}
		$rows[$k]['slug'] = $unique;
		$used[] = $unique;
	}

	update_field('colors', $rows, $post_id);
}
add_action('acf/save_post', 'abf_palette_generate_color_slugs', 20);


