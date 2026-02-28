=== Diagonal Responsive View ===
Contributors: giorgiobianchi
Donate link: https://ko-fi.com/giorgiobianchi
Tags: diagonal, responsive, elementor, wpbakery, media block
Requires at least: 5.8
Tested up to: 6.9
Stable tag: 1.0.0
Requires PHP: 7.4
License: MIT
License URI: https://mit-license.org/

Renders a diagonal, responsive content + media block (image or video) with optional button. Works via shortcode, Elementor, or WPBakery.

== Description ==

**Diagonal Responsive View** renders a diagonal, responsive split-layout block combining a content area and a media panel (image or looping video), with an optional call-to-action button.

It integrates natively with **Elementor** (dedicated widget) and **WPBakery Page Builder** (Visual Composer element), and can also be used anywhere via shortcode.

**Key features:**

* Diagonal/masked split layout — content on one side, image or looping video on the other
* Flip option to swap media and content order
* Optional styled button with full color, radius, alignment, and CSS class controls
* Adjustable mask tilt (20°, 30°, 40°)
* Native Elementor widget (drag-and-drop, live preview)
* Native WPBakery element (backend and frontend editor)
* Plain shortcode support for any editor or theme

**Optimized for desktop and tablet devices.**

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/` or install it via the WordPress admin Plugins screen → Add New → Upload Plugin.
2. Activate the plugin from the **Plugins** screen in your WordPress admin.
3. The plugin automatically enqueues `assets/css/diag-resp-style.css`. No additional setup is required.

== Usage — Shortcode ==

Shortcode name: `diag_resp_view`

Basic example:

`[diag_resp_view]Your <strong>HTML</strong> content here[/diag_resp_view]`

Advanced example:

`[diag_resp_view flip_media="yes" is_video="no" show_button="yes" button_text="Learn more" button_link="url:https://example.com|target:_blank" button_bg_color="#ff0000" button_border_radius="8px" button_text_color="#ffffff" button_align="center" image_id="123" mask_tilt="30"]<h2>Title</h2><p>Content here.</p>[/diag_resp_view]`

**Supported attributes** (defaults in parentheses):

* `flip_media` (`no`) — `yes` to swap media and content order on desktop
* `is_video` (`no`) — `yes` to use `media_url` as a looping background video
* `show_button` (`no`) — `yes` to render a CTA button
* `button_text` (`Click Here`) — button label
* `button_link` (`''`) — plain URL or WPBakery `vc_link` format (`url:...|target:_blank`)
* `button_bg_color` (`#0041C2`) — button background color
* `button_border_radius` (`5px`) — button border radius
* `button_text_color` (`#FFFFFF`) — button text color
* `button_css_classes` (`''`) — extra CSS classes for the button
* `button_align` (`left`) — `left`, `center`, or `right`
* `media_url` (`''`) — URL to a video file (used when `is_video="yes"`)
* `image_id` (`''`) — WordPress attachment ID for the image
* `mask_tilt` (`20`) — tilt angle: `20`, `30`, or `40`

The shortcode wrapper uses the class `diag-responsive-view` for custom CSS targeting.

== Usage — Elementor ==

1. Open a page in Elementor.
2. Search for **Diagonal Responsive View** in the widget panel (category: General).
3. Drag it into your layout and configure content, media, and button settings via the panel controls.

Alternatively, use the Elementor **Shortcode** widget and paste the `diag_resp_view` shortcode directly.

== Usage — WPBakery (Visual Composer) ==

1. Open a page in the WPBakery editor (backend or frontend).
2. Click **Add Element** → search for **Diagonal Responsive View**.
3. Configure content, media, and button settings via the element popup.

You can also insert the shortcode directly into WPBakery text blocks or the classic editor.

== For Developers ==

* Shortcode handler: `diag_resp_view` — see `diag-resp-view.php`
* Elementor widget class: `DiagRespViewElementorWidget` — registered in `includes/elementor-config.php`
* WPBakery mapping base: `diag_resp_view` — registered in `includes/vc-config.php`
* CSS handle: `diag-resp-style` — file: `assets/css/diag-resp-style.css`

== Frequently Asked Questions ==

= Does this plugin work without Elementor or WPBakery? =

Yes. The shortcode `[diag_resp_view]` works in any editor, including the WordPress Block Editor (Gutenberg), classic editor, or any theme that supports shortcodes.

= Can I use a video instead of an image? =

Yes. Set `is_video="yes"` and provide a `media_url` pointing to a self-hosted video file (e.g. `.mp4`). The video will loop silently as a background panel.

= What tilt angles are supported? =

The UI controls support tilt values of `20`, `30`, and `40` degrees via the `mask_tilt` attribute.

= Is it compatible with the latest version of WordPress? =

Yes, it has been tested up to WordPress 6.9.1.

== Screenshots ==

1. Diagonal block with image panel and CTA button — desktop view.
2. Elementor widget controls panel.
3. WPBakery element configuration popup.

== Changelog ==

= 1.0.0 =
* Initial release
* Shortcode `diag_resp_view` with full attribute support
* Native Elementor widget
* Native WPBakery element
* Adjustable mask tilt (20, 30, 40 degrees)
* Optional CTA button with full style controls

== Upgrade Notice ==

= 1.0.0 =
Initial release. No upgrade needed.
