<?php
/**
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */
 /* Template Name: Container Template */

$context = Timber::get_context();
$post = new TimberPost();


if(!empty($post->custom['add_page_by_id'])){

    $pages = explode(',', $post->custom['add_page_by_id']);
    foreach ($pages as $one_page){
        $thispage = new Timber\Post($one_page);
        if(!empty($thispage->custom['_wp_page_template'])){
            $filename = explode('.', $thispage->custom['_wp_page_template']);
            if(!empty($filename[0]) && file_exists(get_template_directory().'/views/'.$filename[0].'-template.twig')){
                $thispage->custom_template = $filename[0].'-template.twig';
                $context['page_by_id'][] = $thispage;
            }
        }
    }
}

$context['post'] = $post;
Timber::render(  'page-container.twig' , $context );