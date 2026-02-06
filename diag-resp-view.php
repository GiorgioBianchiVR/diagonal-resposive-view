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
add_action( 'vc_before_init', 'diag_resp_view_wpbakery_element' );
function diag_resp_view_wpbakery_element() {
    vc_map( array(
        'name' => 'Diagonal Responsive View',
        'base' => 'diag_resp_view',
        'description' => 'Responsive template with dynamic title/description/button',
        'category' => 'Custom Elements',
        'icon' => 'vc_icon-wpbakery-logo',
        'params' => array(
            array( 'type' => 'textfield', 'heading' => 'Title', 'param_name' => 'title', 'value' => 'Diagonal View' ),
            array( 'type' => 'textarea', 'heading' => 'Description', 'param_name' => 'description', 'value' => 'Responsive content.' ),
            array( 'type' => 'checkbox', 'heading' => 'Show Button?', 'param_name' => 'show_button', 'value' => array( 'Yes' => 'yes' ) ),
            array( 'type' => 'textfield', 'heading' => 'Button Text', 'param_name' => 'button_text', 'value' => 'Click Here', 'dependency' => array( 'element' => 'show_button', 'value' => 'yes' ) ),
            array( 'type' => 'vc_link', 'heading' => 'Button Link', 'param_name' => 'button_link', 'dependency' => array( 'element' => 'show_button', 'value' => 'yes' ) ),
            array( 'type' => 'checkbox', 'heading' => 'Is media a video?', 'param_name' => 'is_video', 'value' => array( 'Yes' => 'yes' ) ),
            array( 'type' => 'textfield', 'heading' => 'Video URL', 'param_name' => 'video_url', 'dependency' => array( 'element' => 'is_video', 'value' => 'yes' ) ),
            array( 'type' => 'attach_image', 'heading' => 'Image', 'param_name' => 'image_url', 'dependency' => array( 'element' => 'is_video', 'value' => 'no' ) )
        )
    ) );
}

// Elementor Widget
add_action( 'elementor/widgets/register', 'register_diag_resp_view_elementor_widget' );
function register_diag_resp_view_elementor_widget( $widgets_manager ) {
    class Diag_Resp_View_Elementor_Widget extends \Elementor\Widget_Base {
        public function get_name() { return 'diag_resp_view_elementor'; }
        public function get_title() { return 'Diagonal Responsive View'; }
        public function get_icon() { return 'eicon-code'; }
        public function get_categories() { return [ 'general' ]; }

        protected function register_controls() {
            $this->start_controls_section( 'content_section', [ 'label' => 'Content' ] );
            $this->add_control( 'title', [ 'label' => 'Title', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Diagonal View' ] );
            $this->add_control( 'description', [ 'label' => 'Description', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => 'Responsive content.' ] );
            $this->end_controls_section();

            $this->start_controls_section( 'button_section', [ 'label' => 'Button' ] );
            $this->add_control( 'show_button', [ 'label' => 'Show?', 'type' => \Elementor\Controls_Manager::SWITCHER, 'return_value' => 'yes' ] );
            $this->add_control( 'button_text', [ 'label' => 'Text', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'Click Here', 'condition' => [ 'show_button' => 'yes' ] ] );
            $this->add_control( 'button_link', [ 'label' => 'Link', 'type' => \Elementor\Controls_Manager::URL, 'condition' => [ 'show_button' => 'yes' ] ] );
            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $atts = [
                'title' => $settings['title'],
                'description' => $settings['description'],
                'is_video' => $settings['is_video'] ?? 'no',
                'show_button' => $settings['show_button'],
                'button_text' => $settings['button_text'],
                'button_link' => $settings['button_link']['url'] ?? '',
                'video_url' => $settings['video_url'] ?? '',
                'image_url' => $settings['image_url'] ?? ''
            ];
            $shortcode_atts = implode( ' ', array_map( fn($k,$v) => "$k=\"$v\"", array_keys($atts), $atts ) );
            echo do_shortcode( "[diag_resp_view $shortcode_atts]" );
        }
    }
    $widgets_manager->register( new Diag_Resp_View_Elementor_Widget() );
}

// Template Engine
function render_diag_template( $path, $data ) {
    if ( ! file_exists( $path ) ) return '<p>Missing template</p>';
    $html = file_get_contents( $path );
    $search = '%' . $k . '%';
    foreach ( $data as $k => $v ) $html = str_replace( $search, esc_html($v), $html );
    $html = preg_replace_callback( '/\{\% if\s+([a-z_]+)\s*==\s*\'yes\'\s*\%\}([^\%]+)\{\% endif\s*\%\}/is', 
        fn($m) => ( isset($data[trim($m[1])]) && $data[trim($m[1])]==='yes' ) ? $m[2] : '', $html );
    return do_shortcode( wp_kses_post( $html ) );
}

// Shortcode CORE
function diag_resp_view_shortcode( $atts ) {
    $path = plugin_dir_path( __FILE__ ) . 'assets/templates/responsive-content.html';
    $data = shortcode_atts( [ 'title'=>'Diagonal View', 'description'=>'Responsive content.', 'is_video'=>'no', 'show_button'=>'no', 'button_text'=>'Click Here', 'button_link'=>'', 'video_url'=>'', 'image_url'=>'' ], $atts );
    return '<div class="diag-responsive-view">' . render_diag_template( $path, $data ) . '</div>';
}
add_shortcode( 'diag_resp_view', 'diag_resp_view_shortcode' );

add_action( 'wp_enqueue_scripts', 'diag_resp_view_enqueue_assets' );
function diag_resp_view_enqueue_assets() {
    global $post;
    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'diag_resp_view' ) ) {
        wp_enqueue_style( 'diag-resp-style', plugin_dir_url( __FILE__ ) . 'assets/css/diag-resp-style.css', [], '1.0.2', 'all' );
    }
}
