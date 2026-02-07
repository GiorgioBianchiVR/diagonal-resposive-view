<?php
/**
 * Plugin Name: Diagonal Responsive View
 * Plugin URI: https://github.com/GiorgioBianchiVR/diagonal-resposive-view
 * Description: Custom responsive HTML/CSS block using external template. Dynamic title/description + button.
 * Version: 1.0.2
 * Author: Giorgio Bianchi
 * Author URI: https://giorgiobianchivr.github.io/gb-site/
 * Text Domain: diag-resp-view
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * License: MIT
 * License URI: https://mit-license.org/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// WPBakery
require_once plugin_dir_path(__FILE__) . 'includes/vc-config.php';
//Elementor configuration
require_once plugin_dir_path(__FILE__) . 'includes/elementor-config.php';

function diag_resp_view_shortcode($atts) {
    $data = shortcode_atts([
        'title' => 'Diagonal View',
        'description' => 'Responsive content.',
        'is_video' => 'no',
        'show_button' => 'no',
        'button_text' => 'Click Here',
        'button_link' => '',
        'media_id' => '',
        'image_id' => ''
    ], $atts);

    // Handle vc_link
    $button_link = '';
    if (is_array($data['button_link']) && isset($data['button_link']['url'])) {
        $button_link = $data['button_link']['url'];
    }

    // Handle media pickers
    $media_url = '';
    if (!empty($data['media_id'])) {
        $media_file = get_attached_file((int)$data['media_id']);
        $media_url = wp_get_attachment_url((int)$data['media_id']);
    }
    
    $image_url = '';
    if (!empty($data['image_id'])) {
        $image_url = wp_get_attachment_url((int)$data['image_id']);
    }

    $button_html = '';
    if ($data['show_button'] === 'yes' && !empty($data['button_text'])) {
        $button_html = '<a href="' . esc_url($button_link) . '" class="my-button">' . esc_html($data['button_text']) . '</a>';
    }

    if ($data['is_video'] === 'yes' && $media_url) {
        $media_class = 'media-video';
        $media_html = '
            <div class="embed-wrap">
                <video autoplay muted loop playsinline class="embed">
                    <source src="' . esc_url($media_url) . '" type="video/mp4">
                </video>
            </div>
            <div class="media-mask diag-mask"></div>';
    } else {
        $media_class = 'media-image';
        $media_html = '
            <div class="media-mask diag-mask">
                <img src="' . esc_url($image_url) . '" alt="Hero image" class="masked-image">
            </div>';
    }

    $html = '
    <div class="diag-responsive-view">
        <div class="content-desktop">
            <div>
                <h1>' . esc_html($data['title']) . '</h1>
                <p>' . esc_html($data['description']) . '</p>
                ' . $button_html . '
            </div>
            <div class="media ' . esc_attr($media_class) . '">
                ' . $media_html . '
            </div>
        </div>
        <div class="content-tablet">
            <h3>' . esc_html($data['title']) . '</h3>
            <p>' . esc_html($data['description']) . '</p>
            ' . $button_html . '
        </div>
        <div class="content-mobile">
            <h4>' . esc_html($data['title']) . '</h4>
            <p>' . esc_html($data['description']) . '</p>
        </div>
    </div>';

    return do_shortcode(wp_kses_post($html));
}
add_shortcode('diag_resp_view', 'diag_resp_view_shortcode');


function diag_resp_view_enqueue_assets() {
    wp_enqueue_style(
        'diag-resp-style', 
        plugin_dir_url(__FILE__) . 'assets/css/diag-resp-style.css', 
        [], 
        '1.0.2', 
        'all' 
    );
}

// Frontend + Frontend Editor
add_action('wp_enqueue_scripts', 'diag_resp_view_enqueue_assets');

// Backend Editor + Classic Editor
add_action('admin_enqueue_scripts', 'diag_resp_view_enqueue_assets');

