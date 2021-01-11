<?php
/**
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
/* Template Name: BIG Content */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render(  'page-big-content.twig' , $context );