<?php
$context = Timber::get_context();
$context['post'] = Timber::query_post();
Timber::render('page-comments.twig', $context);