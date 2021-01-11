<?php
/**
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
/* Template Name: Tabs */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

Timber::render(  'page-tabs.twig' , $context );