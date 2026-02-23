<?php
if (!defined('ABSPATH')) exit;

// 2. Elementor Widget (EXACT param_name mapping)
add_action('elementor/widgets/register', 'register_diag_resp_view_elementor');
function register_diag_resp_view_elementor($widgets_manager) {
    class DiagRespViewElementorWidget extends \Elementor\Widget_Base {
        public function get_name() { return 'diag_resp_view'; }
        public function get_title() { return 'Diagonal Responsive View'; }
        public function get_icon() { return 'eicon-image-rollover'; }
        public function get_categories() { return ['general']; }

        protected function register_controls() {
            $this->start_controls_section('content_section', ['label' => 'Content', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
            $this->add_control('content', [
                'label' => 'Content', 'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '<h2>Diagonal Responsive View</h2><p>I am test text block. Click edit button to change this text.</p>'
            ]);
            $this->add_control('show_button', [
                'label' => 'Show Button?', 'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Yes', 'label_off' => 'No', 'return_value' => 'yes'
            ]);
            $this->end_controls_section();

            $this->start_controls_section('button_section', ['label' => 'Button Settings', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
            $this->add_control('button_text', [
                'label' => 'Button Text', 'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Click Here', 'condition' => ['show_button' => 'yes']
            ]);
            $this->add_control('button_link', [  // ← EXACT
                'label' => 'Button Link', 'type' => \Elementor\Controls_Manager::URL,
                'condition' => ['show_button' => 'yes']
            ]);
            $this->add_control('button_align', [
                'label' => 'Button Alignment', 'type' => \Elementor\Controls_Manager::SELECT,
                'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                'condition' => ['show_button' => 'yes']
            ]);
            $this->add_control('button_bg_color', [
                'label' => 'Background Color', 'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0041C2', 'condition' => ['show_button' => 'yes']
            ]);
            $this->add_control('button_text_color', [
                'label' => 'Text Color', 'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF', 'condition' => ['show_button' => 'yes']
            ]);
            $this->add_control('button_border_radius', [
                'label' => 'Border Radius', 'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '5px', 'condition' => ['show_button' => 'yes']
            ]);
            $this->add_control('button_css_classes', [
                'label' => 'CSS classes', 'type' => \Elementor\Controls_Manager::TEXT,
                'description' => 'Add custom CSS classes...', 'condition' => ['show_button' => 'yes']
            ]);
            $this->end_controls_section();

            $this->start_controls_section('media_section', ['label' => 'Media', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
            $this->add_control('flip_media', [
                'label' => 'Flip media?', 'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Yes', 'label_off' => 'No', 'return_value' => 'yes', 'description' => 'Swap order on desktop.'
            ]);
            $this->add_control('mask_tilt', [
                'label' => 'Mask Tilt', 'type' => \Elementor\Controls_Manager::SELECT,
                'options' => ['20' => '20%', '30' => '30%', '40' => '40%'], 'default' => '20'
            ]);
            $this->add_control('is_video', [
                'label' => 'Is video?', 'type' => \Elementor\Controls_Manager::SWITCHER, 'label_on' => 'Yes', 'label_off' => 'No', 'return_value' => 'yes'
            ]);
            $this->add_control('media_url', [
                'label' => 'Video URL', 'type' => \Elementor\Controls_Manager::TEXT,
                'condition' => ['is_video' => 'yes']
            ]);
            $this->add_control('image_id', [
                'label' => 'Image', 'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => 'image', 'condition' => ['is_video!' => 'yes']
            ]);
            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();

            // Build vc_link-like string for button_link if URL provided
            $button_link_attr = '';
            if (!empty($settings['button_link']) && is_array($settings['button_link']) && !empty($settings['button_link']['url'])) {
                $button_link_attr = 'url:' . esc_url($settings['button_link']['url']);
                if (!empty($settings['button_link']['is_external'])) {
                    $button_link_attr .= '|target:_blank';
                }
            }

            $atts = [
                'show_button' => $settings['show_button'] ?? 'no',
                'button_text' => $settings['button_text'] ?? '',
                'button_link' => $button_link_attr,
                'button_align' => $settings['button_align'] ?? 'left',
                'button_bg_color' => $settings['button_bg_color'] ?? '',
                'button_text_color' => $settings['button_text_color'] ?? '',
                'button_border_radius' => $settings['button_border_radius'] ?? '',
                'button_css_classes' => $settings['button_css_classes'] ?? '',
                'flip_media' => $settings['flip_media'] ?? 'no',
                'mask_tilt' => $settings['mask_tilt'] ?? '20',
                'is_video' => $settings['is_video'] ?? 'no',
                'media_url' => $settings['media_url'] ?? '',
                'image_id' => $settings['image_id']['id'] ?? ''
            ];

            $shortcode_atts = [];
            foreach ($atts as $k => $v) {
                if ($v !== '' && $v !== 'no' && $v !== null) {
                    $shortcode_atts[] = sprintf('%s="%s"', $k, esc_attr($v));
                }
            }

            $content = $settings['content'] ?? '';
            printf('<div class="elementor-widget-container">%s</div>', do_shortcode('[diag_resp_view ' . implode(' ', $shortcode_atts) . ']' . $content . '[/diag_resp_view]'));
        }
    }
    $widgets_manager->register(new DiagRespViewElementorWidget());
}
?>
