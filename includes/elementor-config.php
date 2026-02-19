<?php
// Elementor Widget - Updated to match WPBakery config exactly
add_action( 'elementor/widgets/register', 'register_diag_resp_view_elementor_widget' );

function register_diag_resp_view_elementor_widget( $widgets_manager ) {
    class Diag_Resp_View_Elementor_Widget extends \Elementor\Widget_Base {
        public function get_name() { return 'diag_resp_view_elementor'; }
        public function get_title() { return 'Diagonal Responsive View'; }
        public function get_icon() { return 'eicon-image-rollover'; } // Better icon
        public function get_categories() { return [ 'general' ]; }
        public function get_keywords() { return [ 'diagonal', 'responsive', 'view' ]; }

        protected function register_controls() {
            // Content Section (matches title + textarea_html)
            $this->start_controls_section(
                'content_section',
                [ 'label' => 'Content', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
            );
            $this->add_control(
                'title',
                [
                    'label' => 'Title',
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Diagonal View',
                ]
            );
            $this->add_control(
                'content',
                [
                    'label' => 'Content',
                    'type' => \Elementor\Controls_Manager::WYSIWYG,
                    'default' => '<p>I am test text block. Click edit button to change this text.</p>',
                    'description' => 'Enter your content.',
                ]
            );
            $this->end_controls_section();

            // Button Section (matches show_button, button_text, vc_link)
            $this->start_controls_section(
                'button_section',
                [ 'label' => 'Button', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
            );
            $this->add_control(
                'show_button',
                [
                    'label' => 'Show Button?',
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => 'Yes',
                    'label_off' => 'No',
                    'return_value' => 'yes',
                ]
            );
            $this->add_control(
                'button_text',
                [
                    'label' => 'Button Text',
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Click Here',
                    'condition' => [ 'show_button' => 'yes' ],
                    'group' => 'Button Settings',
                ]
            );
            $this->add_control(
                'button_link',
                [
                    'label' => 'Button Link',
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => 'https://your-link.com',
                    'condition' => [ 'show_button' => 'yes' ],
                    'group' => 'Button Settings',
                ]
            );
            $this->end_controls_section();

            // Media Section (matches flip_media, is_video, attach_media, attach_image)
            $this->start_controls_section(
                'media_section',
                [ 'label' => 'Media', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ]
            );
            $this->add_control(
                'flip_media',
                [
                    'label' => 'Flip media orientation?',
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => 'Yes',
                    'label_off' => 'No',
                    'return_value' => 'yes',
                    'description' => 'Swap content and media order on desktop.',
                ]
            );
            $this->add_control(
                'is_video',
                [
                    'label' => 'Is media a video?',
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => 'Yes',
                    'label_off' => 'No',
                    'return_value' => 'yes',
                ]
            );
            $this->add_control(
                'media_url',
                [
                    'label' => 'Video/Media File',
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'media_types' => [ 'video' ],
                    'condition' => [ 'is_video' => 'yes' ],
                ]
            );
            $this->add_control(
                'image_id',
                [
                    'label' => 'Image',
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'media_types' => [ 'image' ],
                    'condition' => [ 'is_video!' => 'yes' ],
                ]
            );
            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $atts = [
                'title' => esc_attr( $settings['title'] ?? '' ),
                'content' => $settings['content'] ?? '',
                'show_button' => $settings['show_button'] ?? 'no',
                'button_text' => esc_attr( $settings['button_text'] ?? '' ),
                'button_link' => esc_url( $settings['button_link']['url'] ?? '' ),
                'flip_media' => $settings['flip_media'] ?? 'no',
                'is_video' => $settings['is_video'] ?? 'no',
                'media_url' => $settings['media_url']['id'] ?? '',
                'image_id' => $settings['image_id']['id'] ?? '',
            ];
            // Build shortcode atts string safely
            $shortcode_atts = '';
            foreach ( $atts as $key => $value ) {
                if ( $value !== '' && $value !== 'no' && $value !== [] ) {
                    $shortcode_atts .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
                }
            }
            echo do_shortcode( '[diag_resp_view' . $shortcode_atts . ']' );
        }
    }
    $widgets_manager->register( new Diag_Resp_View_Elementor_Widget() );
}