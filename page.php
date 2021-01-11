<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

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
Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );