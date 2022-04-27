<?php
/**
* Plugin Name: Featured Posts Shortcode
* Plugin URI: https://github.com/LioneAdri/featured-shortcode
* Description: List featured posts in cards
* Version: 0.1
* Text Domain: visionspring-featured-posts-shortcode
* Author: Sallai Adrienn "Lione"
* Author URI: https://www.visionspring.eu
*/

/*add_action('wp_enqueue_scripts', 'enqueue_style'); // TODO

function enqueue_style(){
    $style = 'bootstrapstyle';
    if ((!wp_style_is( $style, 'queue' )) && (!wp_style_is( $style, 'done' ))) {
        wp_enqueue_style("bootstrapstyle", plugin_dir_path( __FILE__ )."/css/bootstrap.min.css", array(), "5.0.0", 'all');
        wp_enqueue_script('jqueryjs',plugin_dir_path( __FILE__ )."/js/jquery-3.5.1.min.js", array(), "3.5.1");
        wp_enqueue_script('bootstrapjs',plugin_dir_path( __FILE__ )."/js/bootstrap.min.js", array(), "5.0.0");
    }
}*/

function visionspring_create_featured($attributes) {
    $post = get_post();

    extract( shortcode_atts( array(
        'post_id' => $post ? $post->ID : 0, // this post
        'ids' => 0, // list of post ids
        'category' => 0, // tag id
        'maxtext' => -1, // maximum excerpt length - 0 means no excerpt
        'columns'=> 4, // max 6
        'direction'=>'v' // h: horizontal v: vertical
    ), $attributes ) );

    if ($ids != 0) { // if ids are listed
        $_attachments = get_posts(
            array(
                'include' => $ids
            )
        );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[ $val->ID ] = $_attachments[ $key ];
        }
    } else if ($category != 0) { // if category is added
        $_attachments = get_posts(
            array(
                'category' => $category,
                'numberposts' => $columns
            )
        );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[ $val->ID ] = $_attachments[ $key ];
        }
    } else { // or has childran
        $attachments = get_children(
            array(
                'post_parent' => $post_id
            )
        );
    }

    if ( empty( $attachments ) ) {
        return ''; // no data
    }

    $maxchar = ($maxtext == -1 ? 120 : $maxtext);
    $html = '<div class="featured-blogpost-list">
                <div class="container">
                    <div class="row">';
    $d = "v";
    $style = '';
    $excerpt = "";
    $hclass = "";
    switch ($columns) {
        case 1:
            $colDef = "col-xs-12";
            $d = $direction;
            $maxchar = ($maxtext == -1 ? ($direction == "h" ? 600 : $maxchar) : $maxtext);
            $style = 'style="max-width: 600px;margin: auto;"';
            $hclass = ($direction == "h" ? "featured-horizontal" : $maxchar);
            break;
        case 2:
            $colDef = "col-sm-6 col-xs-12";
            $d = $direction;
            $maxchar = ($maxtext == -1 ? ($direction == "h" ? 400 : $maxchar) : $maxtext);
            $hclass = ($direction == "h" ? "featured-horizontal" : $maxchar);
            break;
        case 3:
            $colDef = "col-lg-4 col-md-4 col-sm-6 col-xs-12";
            $d = $direction;
            $hclass = ($direction == "h" ? "featured-horizontal" : $maxchar);
            break;
        case 6:
            $colDef = "col-lg-2 col-md-4 col-sm-6 col-xs-12";
            $maxchar = ($maxtext == -1 ? 60 : $maxtext);
            break;
        default:
            $colDef = "col-lg-3 col-md-3 col-sm-6 col-xs-12";
            break;
    }
    foreach ($attachments as $id => $attachment) {
        if ($maxchar > 0) {
            $excerpt = strip_tags(get_the_excerpt($id));
            if (strlen($excerpt) > $maxchar) {
                $excerpt = substr($excerpt, 0, $maxchar);
                $excerpt .= '...';
            }
        }
        if ($d == "v") {
            $html .= '<div class="' . $colDef . '">
                <div class="card h-100">
                    <div class="article-meta-featured">
                        <div class="image">
                            <a class="attachment-post-thumbnail" href="' . esc_url(get_permalink($id)) . '">' .
                            get_the_post_thumbnail($id, "medium")
                        . '</a>
                        </div>
                        <h2 class="entry-title fontsize20" style="margin-bottom:0px;">
                            <a href="' . esc_url(get_permalink($id)) . '" >' .
                            get_the_title($id)
                        . '</a>
                        </h2>
                        <p class="featured-description" data-href="' . esc_url(get_permalink($id)) . '">' . $excerpt . '</p>                    
                    </div>
                </div>
            </div>';
        } else {
            $html .= '<div class="' . $colDef . '" '.$style.'>
                <div class="card h-100">
                    <div class="article-meta-featured">
                        <div class="container">
                            <div class="row">
                                <div class="image col-sm-6 col-xs-12 '.$hclass.'">
                                    <a class="attachment-post-thumbnail" href="' . esc_url(get_permalink($id)) . '">' .
                                    get_the_post_thumbnail($id, "medium")
                                . '</a>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <h2 class="entry-title fontsize20" style="margin-bottom:0px;">
                                        <a href="' . esc_url(get_permalink($id)) . '" >' .
                                        get_the_title($id)
                                    . '</a>
                                    </h2>
                                    <p class="featured-description" data-href="' . esc_url(get_permalink($id)) . '">' . $excerpt . '</p>     
                                </div>               
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
    $html .= '</div></div></div>';

    return $html;
}

add_shortcode('featured-posts', 'visionspring_create_featured');
