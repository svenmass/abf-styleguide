<?php
if (!defined('ABSPATH')) { exit; }

$palette_id = get_field('palette_ref');
if (is_array($palette_id)) {
	$palette_id = count($palette_id) ? $palette_id[0] : 0;
}
if (!$palette_id) { return; }
$colors = get_field('colors', (int)$palette_id);
if (!$colors || !is_array($colors)) { return; }

$classes = array('block-styleguide-farben-details');
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
	<?php foreach ($colors as $row):
		$label = isset($row['label']) ? $row['label'] : '';
		$slug = isset($row['slug']) && $row['slug'] ? $row['slug'] : sanitize_title($label);
		$shades = function_exists('abf_palette_compute_shades') ? abf_palette_compute_shades($row) : array();
		$active = '100';
		$active_hex = isset($shades[$active]['hex']) ? $shades[$active]['hex'] : '';
		$active_rgb = isset($shades[$active]['rgb']) ? $shades[$active]['rgb'] : null;
		$active_cmyk = isset($shades[$active]['cmyk']) ? $shades[$active]['cmyk'] : null;
		$active_text = isset($shades[$active]['text_color']) ? $shades[$active]['text_color'] : 'auto';
		$active_contrast = 0;
		if ($active_text === 'white') {
			$active_contrast = isset($shades[$active]['contrast_white']) ? $shades[$active]['contrast_white'] : 0;
		} else {
			$active_contrast = isset($shades[$active]['contrast_black']) ? $shades[$active]['contrast_black'] : 0;
		}
		$preview_text_class = ($active_text === 'white') ? 'has-text-white' : 'has-text-black';
	?>
	<section id="<?php echo esc_attr($slug); ?>" class="sg-color-section" aria-labelledby="heading-<?php echo esc_attr($slug); ?>">
		<div class="sg-color-grid">
			<!-- Spalte 1: große Fläche (20%) -->
			<div class="sg-col sg-col-preview <?php echo esc_attr($preview_text_class); ?>" style="background-color: <?php echo esc_attr($active_hex); ?>;">
				<button class="sg-copy-hex" data-hex="<?php echo esc_attr($active_hex); ?>" aria-label="HEX kopieren">
					<span class="sg-copy-icon">
						<svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
							<path d="M8 64H36C40.418 63.9961 43.9961 60.418 44 56V28C43.9961 23.582 40.418 20.0039 36 20H8C3.582 20.0039 0.0039 23.582 0 28V56C0.003906 60.418 3.582 63.9961 8 64ZM4 28C4.00391 25.793 5.793 24.0039 8 24H36C38.207 24.0039 39.9961 25.793 40 28V56C39.9961 58.207 38.207 59.9961 36 60H8C5.793 59.9961 4.0039 58.207 4 56V28Z" fill="currentColor"/>
							<path d="M22 14C22.5312 14 23.0391 13.7891 23.4141 13.4141C23.7891 13.0391 24 12.5313 24 12V8C24.0039 5.793 25.793 4.0039 28 4H56C58.207 4.00391 59.9961 5.793 60 8V36C59.9961 38.207 58.207 39.9961 56 40H52C50.8945 40 50 40.8945 50 42C50 43.1055 50.8946 44 52 44H56C60.418 43.9961 63.9961 40.418 64 36V8C63.9961 3.582 60.418 0.0039 56 0H28C23.582 0.003906 20.0039 3.582 20 8V12C20 12.5312 20.211 13.0391 20.586 13.4141C20.961 13.7891 21.4687 14 22 14Z" fill="currentColor"/>
						</svg>
					</span>
					<span class="sg-copy-text">Hex kopieren</span>
				</button>
			</div>

			<!-- Spalte 2: Stufen-Selector (20%) -->
			<div class="sg-col sg-col-steps" role="tablist" aria-label="Farbstufen wählen">
				<?php foreach (array('100','80','60','40','25') as $step):
					$hex = isset($shades[$step]['hex']) ? $shades[$step]['hex'] : '';
					$rgb = isset($shades[$step]['rgb']) ? $shades[$step]['rgb'] : null;
					$cmyk = isset($shades[$step]['cmyk']) ? $shades[$step]['cmyk'] : null;
					$text = isset($shades[$step]['text_color']) ? $shades[$step]['text_color'] : 'auto';
					$ratio = ($text === 'white') ? ($shades[$step]['contrast_white'] ?? 0) : ($shades[$step]['contrast_black'] ?? 0);
					$txt_css = ($text === 'white') ? '#ffffff' : '#000000';
				?>
				<button class="sg-step" role="tab" data-step="<?php echo esc_attr($step); ?>" style="--step-color: <?php echo esc_attr($hex); ?>; color: <?php echo esc_attr($txt_css); ?>" aria-selected="<?php echo $step==='100' ? 'true' : 'false'; ?>"
					data-hex="<?php echo esc_attr($hex); ?>"
					data-r="<?php echo isset($rgb['r']) ? (int)$rgb['r'] : 0; ?>"
					data-g="<?php echo isset($rgb['g']) ? (int)$rgb['g'] : 0; ?>"
					data-b="<?php echo isset($rgb['b']) ? (int)$rgb['b'] : 0; ?>"
					data-cmyk="<?php echo esc_attr(function_exists('abf_format_cmyk') ? abf_format_cmyk($cmyk) : ''); ?>"
					data-text-color="<?php echo esc_attr($text); ?>"
					data-contrast="<?php echo esc_attr(number_format((float)$ratio, 1)); ?>">
					<span class="sg-step-label"><?php echo esc_html($step . '%'); ?></span>
					<span class="sg-step-icon" aria-hidden="true">
						<svg width="90" height="64" viewBox="0 0 90 64" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M59.973 0.855044C61.0628 -0.0785459 62.7074 0.0464541 63.6449 1.13629L88.6449 30.3043C89.4809 31.2809 89.4809 32.7184 88.6449 33.6949L63.6449 62.8589C62.7074 63.9527 61.0629 64.0777 59.973 63.1441C58.8792 62.2066 58.7542 60.5621 59.6918 59.4722L81.0038 34.6052H3.33176C1.89426 34.6052 0.730164 33.4372 0.730164 31.9997C0.730164 30.5622 1.89426 29.3942 3.33176 29.3942H81.0038L59.6918 4.52715C58.7543 3.43735 58.8793 1.79254 59.973 0.855044Z" fill="currentColor"/>
						</svg>
					</span>
				</button>
				<?php endforeach; ?>
			</div>

			<!-- Spalte 3: Rechte Infofläche (60%) als Flex/Rows) -->
			<div class="sg-col sg-right">
				<!-- Reihe 1: Bezeichnung -->
				<h3 class="sg-color-heading" id="heading-<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></h3>
				<div class="sg-gap16"></div>
				<!-- Reihe 2: Abstufung -->
				<div class="sg-row1">
					<div class="label">Abstufung</div>
					<div class="value js-step-label"><?php echo esc_html($active . '%'); ?></div>
				</div>
				<div class="sg-section-sep" aria-hidden="true"></div>
				<div class="sg-gap16"></div>
				<!-- Reihe 3: HEX | Schriftfarbe -->
				<div class="sg-row2">
					<div class="cell">
						<div class="label">HEX</div>
						<div class="value js-hex"><?php echo esc_html($active_hex); ?></div>
					</div>
					<div class="cell">
						<div class="label">Schriftfarbe</div>
						<div class="value js-text-color"><?php echo esc_html($active_text === 'white' ? 'Weiß' : 'Schwarz'); ?></div>
					</div>
				</div>
				<div class="sg-gap16"></div>
				<!-- Reihe 4: RGB | Kontrast -->
				<div class="sg-row2">
					<div class="cell">
						<div class="label">RGB</div>
						<div class="value js-rgb"><?php echo $active_rgb ? esc_html($active_rgb['r'] . ' | ' . $active_rgb['g'] . ' | ' . $active_rgb['b']) : '—'; ?></div>
					</div>
					<div class="cell">
						<div class="label">Kontrastverhältnis</div>
						<div class="value js-contrast"><?php echo esc_html(number_format((float)$active_contrast, 1) . ' : 1'); ?></div>
					</div>
				</div>
				<div class="sg-gap16"></div>
				<div class="sg-section-sep" aria-hidden="true"></div>
				<div class="sg-gap16"></div>
				<!-- Reihe 5: CMYK | Pantone -->
				<div class="sg-row2">
					<div class="cell">
						<div class="label">CMYK</div>
						<div class="value js-cmyk"><?php echo $active_cmyk ? esc_html(abf_format_cmyk($active_cmyk)) : '—'; ?></div>
					</div>
					<div class="cell">
						<div class="label">Pantone</div>
						<div class="value js-pantone"><?php echo isset($row['pantone']) && $row['pantone'] ? esc_html($row['pantone']) : '—'; ?></div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php endforeach; ?>
</div>


