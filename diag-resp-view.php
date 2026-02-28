<?php
/**
 * Plugin Name:       Diagonal Responsive View
 * Plugin URI:        https://github.com/GiorgioBianchiVR/diagonal-resposive-view
 * Description:       Adds a responsive diagonal layout block with dynamic content, optional styled button, image or video media, and configurable mask tilt. Fully compatible with Elementor (WYSIWYG widget) and WPBakery (vc_map element). Output is rendered via an external HTML/CSS template using the [diag_resp_view] shortcode.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:            Giorgio Bianchi
 * Author URI:        https://giorgiobianchivr.github.io/
 * Plugin URI:        https://github.com/GiorgioBianchiVR/diagonal-resposive-view
 * Text Domain:       diag-resp-view
 * Domain Path:       /languages
 * License:           MIT
 * License URI:       https://mit-license.org/
 * Tags:              responsive, diagonal, layout, elementor, wpbakery, shortcode, media, button
 */


if ( ! defined( 'ABSPATH' ) ) exit;

// WPBakery
require_once plugin_dir_path(__FILE__) . 'includes/vc-config.php';

//Elementor configuration 
require_once plugin_dir_path(__FILE__) . 'includes/elementor-config.php';

function render($atts, $content = null) {
    $plugin_url = plugin_dir_url(__FILE__);
    $data = shortcode_atts([
        'flip_media' => 'no',
        'is_video' => 'no',
        'show_button' => 'no',
        'button_text' => 'Click Here',
        'button_link' => '',
        'button_bg_color' => '#0041C2',
        'button_border_radius' => '5px',
        'button_text_color' => '#FFFFFF',
        'button_css_classes' => '',
        'button_align' => 'left',
        'media_url' => '',
        'image_id' => '',
        'mask_tilt' => '20',
    ], $atts);

    // Process $content (from textarea_html)
    $content = wpb_js_remove_wpautop( $content, true );  // WPBakery helper: fixes p tags

    // FIXED vc_link - declare $button_url first, handle string output
    $button_url = '#';  // Default
    $button_target = '_self';
    $button_rel = '';
    $link_data = $data['button_link'];
    if (!empty($link_data)) {
        $link_parsed = vc_build_link($link_data);  // Returns STRING like "url:title|target=..."
        if ($link_parsed) {
            $button_url = esc_url($link_parsed['url']);
            $button_target = esc_attr($link_parsed['target']);
            $button_rel = !empty($link_parsed['rel']) ? ' rel="' . esc_attr($link_parsed['rel']) . '"' : '';
        }
    }

    // Default plugin assets
    $default_image = $plugin_url . 'assets/images/default-img.webp';
    $default_video = $plugin_url . 'assets/videos/sample.mp4';

    // Media URLs with fallbacks
    $media_url = $default_video;
    if (!empty($data['media_url'])) {
        $media_url = $data['media_url'];
    }

    $image_url = $default_image;
    if (!empty($data['image_id'])) {
        $custom_image = wp_get_attachment_url((int)$data['image_id']);
        $image_url = $custom_image ?: $default_image;
    }

    // Button renders if toggled + text set (link optional)
    $button_html = '';
    if ($data['show_button'] === 'yes' && !empty($data['button_text'])) {
        $button_html = '<div class="button-container ' . esc_attr($data['button_align']) . '">
            <a href="' . esc_url($button_url) . '" 
            class="custom-button ' . esc_attr($data['button_css_classes']) . '" 
            style="
                background-color: ' . esc_attr($data['button_bg_color']) . ';
                border-radius: ' . esc_attr($data['button_border_radius']) . ';
                color: ' . esc_attr($data['button_text_color']) . ';
            "
            target="' . esc_attr($button_target) . '"' . $button_rel . '>
                ' . esc_html($data['button_text']) . '
            </a>
        </div>';
    }

    if ($data['is_video'] === 'yes' && $media_url) {
        $media_html = '
            <div class="media-mask diag-mask-' . esc_attr($data['mask_tilt']) . '">
                <div class="embed-wrap">
                    <video autoplay muted loop playsinline class="embed">
                        <source src="' . esc_url($media_url) . '" type="video/mp4">
                    </video>
                </div>
            </div>';
    } else {
        $media_html = '
            <div class="media-mask diag-mask-' . esc_attr($data['mask_tilt']) . '">
                <img src="' . esc_url($image_url) . '" alt="Hero image" class="masked-image">
            </div>';
    }

    $text_content = '<div>
        ' . wp_kses_post($content) . '
        ' . $button_html . '
    </div>';

    $html = '
    <div class="diag-responsive-view">
        <div class="content-desktop ' . ( $data['flip_media'] === 'yes' ? 'flipped' : '' ) . '">
            ' . ( $data['flip_media'] === 'yes' ? $media_html : $text_content ) . '
            ' . ( $data['flip_media'] === 'yes' ? $text_content : $media_html ) . '
        </div>
        <div class="content-tablet ' . ( $data['flip_media'] === 'yes' ? 'flipped' : '' ) . '">
            ' . ( $data['flip_media'] === 'yes' ? $media_html : $text_content ) . '
            ' . ( $data['flip_media'] === 'yes' ? $text_content : $media_html ) . '
        </div>
        <div class="content-mobile ' . ( $data['flip_media'] === 'yes' ? 'flipped' : '' ) . '">
            ' . ( $data['flip_media'] === 'yes' ? $media_html : $text_content ) . '
            ' . ( $data['flip_media'] === 'yes' ? $text_content : $media_html ) . '
        </div>
    </div>';

    return $html;
}

add_shortcode('diag_resp_view', 'render');

function diag_resp_view_enqueue_assets() {
    wp_enqueue_style(
        'diag-resp-style',
        plugin_dir_url(__FILE__) . 'assets/css/diag-resp-style.css', 
        [],
        '1.0.0',
        'all'
    );
}

// Frontend + Frontend Editor
add_action('wp_enqueue_scripts', 'diag_resp_view_enqueue_assets');

// Backend Editor + Classic Editor
add_action('admin_enqueue_scripts', 'diag_resp_view_enqueue_assets');

