<?php
if (!defined('ABSPATH')) exit;

add_action('elementor/widgets/register', 'register_diagrespview_elementor_widget');
function register_diagrespview_elementor_widget($widgets_manager) {
    class DiagRespViewElementorWidget extends \Elementor\Widget_Base {
        public function get_name() { return 'diagrespview'; }
        public function get_title() { return 'Diagonal Responsive View'; }
        public function get_icon() { return 'eicon-image-rollover'; }
        public function get_categories() { return ['general']; }
        public function get_keywords() { return ['diagonal', 'responsive', 'view']; }

        protected function register_controls() {
            // Content (textarea_html -> WYSIWYG)
            $this->start_controls_section('content_section', ['label' => 'Content', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
            $this->add_control('content', [
                'label' => 'Content',
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => '<h2>Diagonal Responsive View</h2><p>I am test text block. Click edit button to change this text.</p>',
                'description' => 'Enter your content.'
            ]);
            $this->end_controls_section();

            // Button Settings (checkbox + textfield + vclink + dropdown + colorpickers + textfields)
            $this->start_controls_section('button_section', ['label' => 'Button Settings', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
            $this->add_control('showbutton', [
                'label' => 'Show Button?',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Yes',
                'label_off' => 'No',
                'return_value' => 'yes',
                'default' => 'no'
            ]);
            $this->add_control('buttontext', [
                'label' => 'Button Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Click Here',
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->add_control('buttonlink', [
                'label' => 'Button Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://your-link.com',
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->add_control('buttonalign', [
                'label' => 'Button Alignment',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->add_control('buttonbgcolor', [
                'label' => 'Background Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#0041C2',
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->add_control('buttontextcolor', [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->add_control('buttonborderradius', [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '5px',
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->add_control('buttoncssclasses', [
                'label' => 'CSS classes',
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => 'Add custom CSS classes to the button, separated by spaces.',
                'condition' => ['showbutton' => 'yes']
            ]);
            $this->end_controls_section();

            // Media Settings (checkbox flip + dropdown masktilt + checkbox isvideo + textfield/media/image)
            $this->start_controls_section('media_section', ['label' => 'Media', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT]);
            $this->add_control('flipmedia', [
                'label' => 'Flip media orientation?',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Yes',
                'label_off' => 'No',
                'return_value' => 'yes',
                'description' => 'Swap content and media order on desktop.'
            ]);
            $this->add_control('masktilt', [
                'label' => 'Mask Tilt',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '20',
                'options' => ['20' => '20', '30' => '30', '40' => '40'],
            ]);
            $this->add_control('isvideo', [
                'label' => 'Is media a video?',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Yes',
                'label_off' => 'No',
                'return_value' => 'yes'
            ]);
            $this->add_control('mediaurl', [
                'label' => 'Video File url',
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => 'Write the URL of the uploaded video file.',
                'condition' => ['isvideo' => 'yes']
            ]);
            $this->add_control('imageid', [
                'label' => 'Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => 'image',
                'condition' => ['isvideo!' => 'yes']
            ]);
            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $atts = [
                'content' => $settings['content'] ?? '',
                'showbutton' => $settings['showbutton'] ?? 'no',
                'buttontext' => esc_attr($settings['buttontext'] ?? ''),
                'buttonlink' => esc_url($settings['buttonlink']['url'] ?? ''),
                'buttonalign' => $settings['buttonalign'] ?? 'left',
                'buttonbgcolor' => $settings['buttonbgcolor'] ?? '',
                'buttontextcolor' => $settings['buttontextcolor'] ?? '',
                'buttonborderradius' => $settings['buttonborderradius'] ?? '',
                'buttoncssclasses' => $settings['buttoncssclasses'] ?? '',
                'flipmedia' => $settings['flipmedia'] ?? 'no',
                'masktilt' => $settings['masktilt'] ?? '20',
                'isvideo' => $settings['isvideo'] ?? 'no',
                'mediaurl' => esc_url($settings['mediaurl'] ?? ''),
                'imageid' => $settings['imageid']['id'] ?? ''
            ];

            // Build shortcode atts string
            $shortcode_atts = '';
            foreach ($atts as $key => $value) {
                if ($value !== '' && $value !== 'no' && $value !== null) {
                    $shortcode_atts .= sprintf('%s="%s" ', $key, esc_attr($value));
                }
            }
            echo do_shortcode('[diagrespview ' . trim($shortcode_atts) . ']');
        }
    }
    $widgets_manager->register(new DiagRespViewElementorWidget());
}
