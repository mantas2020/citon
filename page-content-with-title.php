<?php
/**
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
/* Template Name: Content with title */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render(  'page-content-title.twig' , $context );