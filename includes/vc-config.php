<?php
if (!defined('ABSPATH')) exit;

add_action('vc_before_init', 'diag_resp_view_wpbakery_element');
function diag_resp_view_wpbakery_element() {
    vc_map(array(
        'name' => 'Diagonal Responsive View',
        'base' => 'diag_resp_view',
        'description' => 'Responsive template with dynamic title/description/button/media',
        'category' => 'Custom Elements',
        'icon' => 'vc_icon-wpbakery-logo',
        'params' => array(
            array('type' => 'textfield', 
                'heading' => 'Title', 
                'param_name' => 'title', 
                'value' => 'Diagonal View'),
            array('type' => 'textarea_html', 
                'heading' => 'Description', 
                'param_name' => 'description', 
                'value' => 'Responsive content.'),
            array('type' => 'checkbox', 
                'heading' => 'Show Button?', 
                'param_name' => 'show_button', 
                'value' => array('Yes' => 'yes')),
            array('type' => 'textfield', 
                'heading' => 'Button Text', 
                'param_name' => 'button_text', 
                'value' => 'Click Here', 
                'dependency' => array('element' => 'show_button', 'value' => 'yes')),
                'group' => 'Button Settings',
            array('type' => 'vc_link', 
                'heading' => 'Button Link', 
                'param_name' => 'button_link', 
                'dependency' => array('element' => 'show_button', 'value' => 'yes')),
                'group' => 'Button Settings',
            array('type' => 'checkbox', 
                'heading' => 'Is media a video?', 
                'param_name' => 'is_video', 
                'value' => array('Yes' => 'yes')),
            array('type' => 'attach_media', 
                'heading' => 'Video/Media File', 
                'param_name' => 'media_id', 
                'dependency' => array('element' => 'is_video', 'value' => array('yes'))),
            array('type' => 'attach_image', 
                'heading' => 'Image', 
                'param_name' => 'image_id', 
                'dependency' => array('element' => 'is_video', 'value_not_equal_to' => 'yes'))
        )
    ));
}
