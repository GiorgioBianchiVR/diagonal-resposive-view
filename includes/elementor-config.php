<?php
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