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
						'key' => 'field_color_preview',
						'label' => 'Vorschau',
						'name' => 'color_preview',
						'type' => 'message',
						'esc_html' => 0,
						'message' => '<div class="abf-color-preview" data-preview></div>',
						'wrapper' => array('class' => 'is-hidden'),
					),
					array(
						'key' => 'field_color_slug',
						'label' => 'Slug (Anker)',
						'name' => 'slug',
						'type' => 'text',
						'instructions' => 'Automatisch aus Bezeichnung. Zum Überschreiben leer lassen oder anpassen.',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
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
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
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
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_hex_60',
						'label' => 'HEX 60%',
						'name' => 'hex_60',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_hex_40',
						'label' => 'HEX 40%',
						'name' => 'hex_40',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_hex_25',
						'label' => 'HEX 25%',
						'name' => 'hex_25',
						'type' => 'text',
						'pattern' => '^#([A-Fa-f0-9]{6})$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_rgb_80',
						'label' => 'RGB 80% (Override)',
						'name' => 'rgb_80',
						'type' => 'text',
						'placeholder' => 'R|G|B',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_rgb_60',
						'label' => 'RGB 60% (Override)',
						'name' => 'rgb_60',
						'type' => 'text',
						'placeholder' => 'R|G|B',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_rgb_40',
						'label' => 'RGB 40% (Override)',
						'name' => 'rgb_40',
						'type' => 'text',
						'placeholder' => 'R|G|B',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_rgb_25',
						'label' => 'RGB 25% (Override)',
						'name' => 'rgb_25',
						'type' => 'text',
						'placeholder' => 'R|G|B',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_cmyk_80',
						'label' => 'CMYK 80% (Override)',
						'name' => 'cmyk_80',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_cmyk_60',
						'label' => 'CMYK 60% (Override)',
						'name' => 'cmyk_60',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_cmyk_40',
						'label' => 'CMYK 40% (Override)',
						'name' => 'cmyk_40',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_cmyk_25',
						'label' => 'CMYK 25% (Override)',
						'name' => 'cmyk_25',
						'type' => 'text',
						'placeholder' => 'C|M|Y|K',
						'pattern' => '^\d{1,3}\|\d{1,3}\|\d{1,3}\|\d{1,3}$',
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
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
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
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
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
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
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
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
						'conditional_logic' => array(
							array(
								array('field' => 'field_color_advanced','operator' => '==','value' => 1),
							),
						),
					),
					array(
						'key' => 'field_color_advanced',
						'label' => 'Weitere Einstellungen anzeigen',
						'name' => 'field_color_advanced_toggle',
						'type' => 'true_false',
						'ui' => 1,
						'default_value' => 0,
					),
					array(
						// Notizen entfernt
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

/**
 * Admin Preview: Render compact swatches for each repeater row
 */
add_action('admin_head', function () {
    echo '<style>
    .abf-color-preview{display:flex;gap:4px;align-items:center;margin:6px 0}
    .abf-color-preview .sw{width:18px;height:18px;border:1px solid #ddd}
    .abf-color-preview .tx{font-size:12px;color:#666;margin-left:6px}
    </style>';
});

add_filter('acf/prepare_field/name=color_preview', function ($field) {
    // Hole aktuelle Zeilendaten sicher
    $hex100 = get_sub_field('hex_100');
    if (!$hex100 || !function_exists('abf_hex_to_rgb')) {
        $field['message'] = '<div class="abf-color-preview">Bitte HEX 100% angeben.</div>';
        return $field;
    }

    $row = array(
        'hex_100' => $hex100,
        'hex_80' => get_sub_field('hex_80'),
        'hex_60' => get_sub_field('hex_60'),
        'hex_40' => get_sub_field('hex_40'),
        'hex_25' => get_sub_field('hex_25'),
        'cmyk_100' => get_sub_field('cmyk_100'),
        'text_color_100' => get_sub_field('text_color_100') ?: 'auto',
        'text_color_80' => get_sub_field('text_color_80') ?: 'auto',
        'text_color_60' => get_sub_field('text_color_60') ?: 'auto',
        'text_color_40' => get_sub_field('text_color_40') ?: 'auto',
        'text_color_25' => get_sub_field('text_color_25') ?: 'auto',
    );

    if (function_exists('abf_palette_compute_shades')) {
        $sh = abf_palette_compute_shades($row);
        $steps = array('100','80','60','40','25');
        $html = '<div class="abf-color-preview">';
        foreach ($steps as $s) {
            $hex = isset($sh[$s]['hex']) ? $sh[$s]['hex'] : '';
            $html .= '<span class="sw" style="background:' . esc_attr($hex) . '"></span>';
        }
        $html .= '<span class="tx">' . esc_html($hex100) . '</span>';
        $html .= '</div>';
        $field['message'] = $html;
    }
    return $field;
});

// Render-Variante: dynamische Vorschau im Message-Feld
add_action('acf/render_field/name=color_preview', function ($field) {
    $hex100 = function_exists('get_sub_field') ? get_sub_field('hex_100') : '';
    if (!$hex100 || !function_exists('abf_palette_compute_shades')) {
        echo '<div class="abf-color-preview" style="margin:6px 0;color:#666">Bitte HEX 100% angeben.</div>';
        return;
    }
    $row = array(
        'hex_100' => $hex100,
        'hex_80' => get_sub_field('hex_80'),
        'hex_60' => get_sub_field('hex_60'),
        'hex_40' => get_sub_field('hex_40'),
        'hex_25' => get_sub_field('hex_25'),
        'cmyk_100' => get_sub_field('cmyk_100'),
        'text_color_100' => get_sub_field('text_color_100') ?: 'auto',
        'text_color_80' => get_sub_field('text_color_80') ?: 'auto',
        'text_color_60' => get_sub_field('text_color_60') ?: 'auto',
        'text_color_40' => get_sub_field('text_color_40') ?: 'auto',
        'text_color_25' => get_sub_field('text_color_25') ?: 'auto',
    );
    $sh = abf_palette_compute_shades($row);
    $steps = array('100','80','60','40','25');
    echo '<div class="abf-color-preview" style="display:flex;gap:6px;align-items:center;margin:6px 0;flex-wrap:wrap">';
    foreach ($steps as $s) {
        $hex = isset($sh[$s]['hex']) ? $sh[$s]['hex'] : '';
        $txt = isset($sh[$s]['text_color']) ? $sh[$s]['text_color'] : 'auto';
        $ratio = ($txt === 'white') ? ($sh[$s]['contrast_white'] ?? 0) : ($sh[$s]['contrast_black'] ?? 0);
        $aa = ($ratio >= 4.5) ? '✓' : '✕';
        $title = $s . '% ' . $hex . ' · ' . ($txt === 'white' ? 'Weiß' : 'Schwarz') . ' · ' . number_format((float)$ratio,1) . ':1';
        echo '<span title="' . esc_attr($title) . '" style="display:inline-flex;flex-direction:column;align-items:center;gap:2px">';
        echo '<span class="sw" style="width:24px;height:16px;border:1px solid #ddd;background:' . esc_attr($hex) . '"></span>';
        echo '<span style="font-size:10px;color:#666">' . esc_html($s) . '% ' . esc_html($aa) . '</span>';
        echo '</span>';
    }
    echo '<span class="tx" style="font-size:12px;color:#666;margin-left:6px">' . esc_html($hex100) . '</span>';
    echo '</div>';
}, 10, 1);

/**
 * CPT Liste: Spalten mit Vorschau
 */
add_filter('manage_edit-abf_palette_columns', function ($cols) {
    $cols['abf_colors_preview'] = __('Farben', 'abf-styleguide');
    return $cols;
});

add_action('manage_abf_palette_posts_custom_column', function ($col, $post_id) {
    if ($col !== 'abf_colors_preview') { return; }
    $rows = get_field('colors', $post_id);
    if (!$rows || !is_array($rows)) { return; }
    $first = $rows[0] ?? null;
    if (!$first || !isset($first['hex_100'])) { return; }
    $shades = function_exists('abf_palette_compute_shades') ? abf_palette_compute_shades($first) : array();
    $steps = array('100','80','60','40','25');
    echo '<div style="display:flex;gap:4px;align-items:center">';
    foreach ($steps as $s) {
        $hex = isset($shades[$s]['hex']) ? $shades[$s]['hex'] : '';
        echo '<span style="width:14px;height:14px;border:1px solid #ddd;background:' . esc_attr($hex) . '"></span>';
    }
    echo '</div>';
}, 10, 2);

/**
 * Repeater-Zeilenüberschrift verbessern (Bezeichnung — HEX100)
 */
add_filter('acf/fields/repeater/row_title/name=colors', function ($title, $field, $row, $i) {
    $label = isset($row['label']) ? $row['label'] : '';
    $hex = isset($row['hex_100']) ? $row['hex_100'] : '';
    $title = trim($label) ? $label : $title;
    if ($hex) { $title .= ' — ' . strtoupper($hex); }
    return $title;
}, 10, 4);

/**
 * Fallback-Preview direkt unter "HEX 100%" rendern (falls Message-Feld nicht greift)
 */
add_action('acf/render_field/name=hex_100', function ($field) {
    // Wir rendern nur einmal je Repeater-Zeile
    $hex100 = isset($field['value']) ? $field['value'] : '';
    if (!$hex100 || !function_exists('abf_palette_compute_shades')) {
        echo '<div class="abf-color-preview" style="margin-top:6px;color:#666">Vorschau erscheint nach Eingabe von HEX 100%.</div>';
        return;
    }

    // Zugriff auf weitere Zeilenwerte
    $row = array(
        'hex_100' => $hex100,
        'hex_80' => function_exists('get_sub_field') ? get_sub_field('hex_80') : '',
        'hex_60' => function_exists('get_sub_field') ? get_sub_field('hex_60') : '',
        'hex_40' => function_exists('get_sub_field') ? get_sub_field('hex_40') : '',
        'hex_25' => function_exists('get_sub_field') ? get_sub_field('hex_25') : '',
        'cmyk_100' => function_exists('get_sub_field') ? get_sub_field('cmyk_100') : '',
        'text_color_100' => function_exists('get_sub_field') ? (get_sub_field('text_color_100') ?: 'auto') : 'auto',
        'text_color_80' => function_exists('get_sub_field') ? (get_sub_field('text_color_80') ?: 'auto') : 'auto',
        'text_color_60' => function_exists('get_sub_field') ? (get_sub_field('text_color_60') ?: 'auto') : 'auto',
        'text_color_40' => function_exists('get_sub_field') ? (get_sub_field('text_color_40') ?: 'auto') : 'auto',
        'text_color_25' => function_exists('get_sub_field') ? (get_sub_field('text_color_25') ?: 'auto') : 'auto',
    );

    $sh = abf_palette_compute_shades($row);
    $steps = array('100','80','60','40','25');
    echo '<div class="abf-color-preview" style="display:flex;gap:4px;align-items:center;margin:6px 0">';
    foreach ($steps as $s) {
        $hex = isset($sh[$s]['hex']) ? $sh[$s]['hex'] : '';
        echo '<span class="sw" style="width:18px;height:18px;border:1px solid #ddd;background:' . esc_attr($hex) . '"></span>';
    }
    echo '<span class="tx" style="font-size:12px;color:#666;margin-left:6px">' . esc_html($hex100) . '</span>';
    echo '</div>';
}, 20);


