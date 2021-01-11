<?php
$context = Timber::get_context();
$context['get_search_query'] = get_search_query();
$site = new TimberSite();
Timber::render( 'searchform.twig', $context );