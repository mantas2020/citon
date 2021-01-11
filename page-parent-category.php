<?php
/**
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
/* Template Name: Parent Category */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['tags'] = wp_get_post_tags($post->ID);
Timber::render(  'page-parent-category.twig' , $context );