<?php
if (!defined('ABSPATH')) { exit; }

$palette_id = get_field('palette_ref');
if (is_array($palette_id)) {
	$palette_id = count($palette_id) ? $palette_id[0] : 0;
}
if (!$palette_id) { return; }

// Hole Reihen
$colors = get_field('colors', (int)$palette_id);
if (!$colors || !is_array($colors)) { return; }

// Klassen
$classes = array('block-styleguide-farben');
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
	<div class="sg-farben-grid">
		<?php foreach ($colors as $row):
			$label = isset($row['label']) ? $row['label'] : '';
			$slug = isset($row['slug']) && $row['slug'] ? $row['slug'] : sanitize_title($label);
			$hex100 = isset($row['hex_100']) ? $row['hex_100'] : '';
			$shades = function_exists('abf_palette_compute_shades') ? abf_palette_compute_shades($row) : array();
			$txt = isset($shades['100']['text_color']) ? $shades['100']['text_color'] : 'auto';
			$txt_css = ($txt === 'white') ? '#ffffff' : '#000000';
			$hex80 = isset($shades['80']['hex']) ? $shades['80']['hex'] : '';
			$hex60 = isset($shades['60']['hex']) ? $shades['60']['hex'] : '';
			$hex40 = isset($shades['40']['hex']) ? $shades['40']['hex'] : '';
			$hex25 = isset($shades['25']['hex']) ? $shades['25']['hex'] : '';
		?>
		<a class="sg-farben-card" href="#<?php echo esc_attr($slug); ?>" aria-label="<?php echo esc_attr($label); ?>">
			<div class="sg-card-top" style="background-color: <?php echo esc_attr($hex100); ?>; color: <?php echo esc_attr($txt_css); ?>;">
				<strong class="sg-card-label">&nbsp;<?php echo esc_html($label); ?></strong>
			</div>
			<div class="sg-card-bottom">
				<span style="background-color: <?php echo esc_attr($hex80); ?>;"></span>
				<span style="background-color: <?php echo esc_attr($hex60); ?>;"></span>
				<span style="background-color: <?php echo esc_attr($hex40); ?>;"></span>
				<span style="background-color: <?php echo esc_attr($hex25); ?>;"></span>
			</div>
		</a>
		<?php endforeach; ?>
	</div>
</div>


