<?php
/**
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
/* Template Name: Page Review */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render('page-review.twig', $context);