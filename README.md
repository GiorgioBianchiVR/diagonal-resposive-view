# diagonal-resposive-view

![WordPress](https://img.shields.io/badge/WordPress-Plugin-21759b?logo=wordpress&logoColor=white) ![Elementor](https://img.shields.io/badge/Elementor-Support-ef476f?logo=elementor&logoColor=white) ![WPBakery](https://img.shields.io/badge/WPBakery-Compatible-00a0d2)

A WordPress plugin that renders a diagonal, responsive content + media block (image or video) with optional button. It can be used via shortcode, with Elementor as a dedicated widget, or as a WPBakery (Visual Composer) element.
It's optimized for desktop and tablet devices.

## Quick install

- Upload the plugin folder to `wp-content/plugins/` or place the files into your theme/plugin folder.
- Activate the plugin from the WordPress admin Plugins screen.
- The plugin enqueues `assets/css/diag-resp-style.css` automatically.

## Shortcode usage

Shortcode name: `diag_resp_view`

Basic example (inline content):

```HTML
[diag_resp_view]Your <strong>HTML</strong> content here[/diag_resp_view]
```

Advanced example with attributes:

```HTML
[diag_resp_view flip_media="yes" is_video="no" show_button="yes" button_text="Learn more" button_link="url:https://example.com|target:_blank" button_bg_color="#ff0000" button_border_radius="8px" button_text_color="#ffffff" button_css_classes="my-btn" button_align="center" media_url="https://example.com/video.mp4" image_id="123" mask_tilt="30"]
    <h2>Title</h2>
    <p>Some descriptive content here.</p>
[/diag_resp_view]
```

Supported shortcode attributes (defaults in parentheses):

- `flip_media` (`no`) — `yes` to swap media and content order on desktop.
- `is_video` (`no`) — `yes` to use `media_url` as a looping video; otherwise an image is shown.
- `show_button` (`no`) — `yes` to render a button.
- `button_text` (`Click Here`) — button label text.
- `button_link` (`''`) — use WPBakery `vc_link` format or a plain URL; when used in the shortcode prefer `url:...|target:_blank` format.
- `button_bg_color` (`#0041C2`) — background color for the button.
- `button_border_radius` (`5px`) — border radius for the button.
- `button_text_color` (`#FFFFFF`) — button text color.
- `button_css_classes` (`''`) — extra CSS classes applied to the button.
- `button_align` (`left`) — `left`, `center`, or `right`.
- `media_url` (`''`) — URL to a video file when `is_video="yes"`.
- `image_id` (`''`) — WordPress attachment ID for the image to display.
- `mask_tilt` (`20`) — mask tilt value (20, 30, 40 supported by UI controls).

Notes:

- When using `button_link` in a shortcode, you can pass a `vc_link`-style value (e.g. `url:https://example.com|target:_blank`) or set `button_link` via builder controls.
- The shortcode wrapper class is `diag-responsive-view` if you want to target it with custom CSS.

## Use with Elementor

- This plugin registers an Elementor widget named **Diagonal Responsive View** (category: General).
- You can either drag the **Diagonal Responsive View** widget into your layout and configure its controls (content, media, button settings), or use Elementor's **Shortcode** widget and paste the `diag_resp_view` shortcode.
- The widget exposes the same options as the shortcode and will render the block in the Elementor preview.

## Use with WPBakery (Visual Composer)

- The plugin maps a WPBakery element called **Diagonal Responsive View** (base: `diag_resp_view`).
- In the WPBakery backend editor click **Add element** → **Diagonal Responsive View**, then configure content, media and button settings via the element panel.
- You can also use the shortcode directly in WPBakery text blocks or classic editor.

## Notes for developers

- Shortcode handler: `diag_resp_view` (see `diag-resp-view.php`).
- Elementor widget class: `DiagRespViewElementorWidget` (registered in `includes/elementor-config.php`).
- WPBakery mapping: base `diag_resp_view` (registered in `includes/vc-config.php`).
- CSS file: `assets/css/diag-resp-style.css` (enqueued as `diag-resp-style`).

## Examples

- Shortcode with image attachment ID:

```HTML
[diag_resp_view image_id="123" show_button="yes" button_text="Get ticket"]<p>Buy your ticket now.</p>[/diag_resp_view]
```

- Shortcode with hosted video:

```HTML
[diag_resp_view is_video="yes" media_url="https://example.com/video.mp4" mask_tilt="30"]<p>Looping background video</p>[/diag_resp_view]
```

## Support

Open an issue or contact the author via the plugin header author URL.
