<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php 
    // Output favicon and touch icon meta tags
    abf_output_favicon_meta_tags();
    
    wp_head(); 
    ?>
</head>

<body <?php body_class('fullscreen-template'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site fullscreen-site">
    <div id="content" class="site-content fullscreen-content"> 