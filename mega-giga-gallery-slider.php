<?php

/**
 * Mega giga gallery slider
 *
 * @author            Sashko
 *
 * @wordpress-plugin
 * Plugin Name:       Mega-giga-gallery-slider
 * Description:       Making your gallery mega-giga!!!
 * Version:           2.0
 * Requires PHP:      5.6
 * Author:            Sashko
 */

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script('jquery');
    wp_enqueue_style('gs_style', plugins_url('mega-giga-gallery-slider.css', __FILE__));
    wp_enqueue_script('gs_js', plugins_url('mega-giga-gallery-slider.js', __FILE__));
    if (isset(get_option('slide_data')['MGGS_check_slick']) && get_option('slide_data')['MGGS_check_slick'] != 1) {
        wp_enqueue_script('slick_js', plugins_url('slick.min.js', __FILE__));
        wp_enqueue_style('slick_css', plugins_url('slick.css', __FILE__));
    }
});

add_action('admin_menu', 'MGGS_menu_page', 25);

function MGGS_menu_page()
{
    add_menu_page('Gallery slider options', 'Gallery slider', 'manage_options', 'mega-giga-gallery-slider', 'MGGS_true_slider_page_callback', 'dashicons-images-alt2', 35);
}

function MGGS_true_slider_page_callback()
{
?>
    <div class="MSSG_wrap">
        <h1><?= get_admin_page_title(); ?></h1>
        <form action="options.php" method="POST">
            <?php
            settings_fields('slide_group');
            do_settings_sections('slide_page');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

add_action('admin_init', 'MGGS_init_edit_global_content_example');
function MGGS_init_edit_global_content_example()
{
    register_setting('slide_group', 'slide_data', 'sanitize_callback');
    add_settings_section('slide_section', '', '', 'slide_page');
    $fields = [
        'MGGS_check_slick' => 'Disable plugin\'s Slick',
        'MGGS_check_autoscroll' => 'Enable autoscroll',
        'MGGS_nmb_autospeed' => 'Autoscroll speed (ms)',
        'MGGS_check_gutenberg' => 'Check it if you use Gutenberg',
        'MGGS_check_thumbnails' => 'Thumbnails',
        'MGGS_nmb_thumbnails' => 'How many thumbnails',
        'MGGS_nmb_t_thumbnails' => 'How many tablet thumbnails',
        'MGGS_nmb_m_thumbnails' => 'How many mobile thumbnails',
        'MGGS_select_columns' => 'How many gallery colunms wanna be slidered?',
    ];
    $vals = get_option('slide_data');
    foreach ($fields as $field => $name) {
        add_settings_field($field, $name, 'MGGS_get_fields_example', 'slide_page', 'slide_section', $args = ['field' => $field, 'vals' => $vals, 'name' => 'slide_data']);
    }
}
function MGGS_get_fields_example($args)
{
    $field_name = $args['field'];
    $vals = $args['vals'];
    $name_option = $args['name'];
    if (strpos($field_name, 'check') !== false) {
        if (isset($vals[$field_name]) && $vals[$field_name] == 1) $cheked = ' checked';
        else $cheked = '';
        echo '<input name="' . esc_attr($name_option) . '[' . esc_attr($field_name) . ']" type="checkbox" id="' . esc_attr($field_name) . '" value="1"' . esc_attr($cheked) . '/>';
    } elseif (strpos($field_name, 'select') !== false) {
        $options = '';
        for ($i = 1; $i < 10; $i++) {
            if (isset($vals[$field_name]) && $vals[$field_name] == $i && is_numeric($vals[$field_name])) {
                $options .= '<option value="' . $i . '" selected>' . $i . '</option>';
            } else {
                $options .= '<option value="' . $i . '">' . $i . '</option>';
            }
        }
        echo '<select name="' . esc_attr($name_option) . '[' . esc_attr($field_name) . ']" id="' . esc_attr($field_name) . '">
        ' . $options . '
        </select>';
    } elseif (strpos($field_name, 'nmb') !== false) {
        if (isset($vals[$field_name]))
            $value = $vals[$field_name];
        else $value = '';
        echo '<input name="' . esc_attr($name_option) . '[' . esc_attr($field_name) . ']" type="number" id="' . esc_attr($field_name) . '" value="' . esc_attr($value) . '" class="regular-text" />';
    }
}
$opts = get_option('slide_data');
if (isset($opts['MGGS_check_gutenberg']) && $opts['MGGS_check_gutenberg'] == 1) {
    add_filter('render_block', function ($block_content, $block) {
        if (isset(get_option('slide_data')['MGGS_check_thumbnails']) && get_option('slide_data')['MGGS_check_thumbnails'] == 1)
            $thumb = ' thumb';
        else
            $thumb = '';
        if (isset(get_option('slide_data')['MGGS_nmb_thumbnails']) && get_option('slide_data')['MGGS_nmb_thumbnails'])
            $thumbs = get_option('slide_data')['MGGS_nmb_thumbnails'];
        else
            $thumbs = 5;
            if (isset(get_option('slide_data')['MGGS_nmb_t_thumbnails']) && get_option('slide_data')['MGGS_nmb_t_thumbnails'])
            $t_thumbs = get_option('slide_data')['MGGS_nmb_t_thumbnails'];
        else
            $t_thumbs = 4;
            if (isset(get_option('slide_data')['MGGS_nmb_n_thumbnails']) && get_option('slide_data')['MGGS_nmb_m_thumbnails'])
            $m_thumbs = get_option('slide_data')['MGGS_nmb_m_thumbnails'];
        else
            $m_thumbs = 3;
        $mar = '';
        if ($thumb == ' thumb')
            $mar = ' t-mar';
            if (isset(get_option('slide_data')['MGGS_select_columns']) && get_option('slide_data')['MGGS_select_columns']) {
                $clmn = get_option('slide_data')['MGGS_select_columns'];
            } else {
                $clmn = 1;
            }
            if (isset(get_option('slide_data')['MGGS_nmb_autospeed']) && get_option('slide_data')['MGGS_nmb_autospeed']) {
                $atps = get_option('slide_data')['MGGS_nmb_autospeed'];
            } else {
                $atps = 3000;
            }
            if ('core/gallery' == $block['blockName'] && isset($block['attrs']['columns']) && $block['attrs']['columns'] == $clmn) {
                $ids = $block['attrs']['ids'] ?? array_column(array_column($block['innerBlocks'], 'attrs'), 'id');
                $size = $block['attrs']['sizeSlug'];
                $clmn_clss = '';
                if (isset(get_option('slide_data')['MGGS_check_autoscroll']) && get_option('slide_data')['MGGS_check_autoscroll'] == 1) {
                    $clmn_clss = ' auto';
                }
                $output = '';
                $gallery_div = "<div class='MGGS'><div class='MGGS_gallery-slider" . $clmn_clss . $thumb . "' data='" . $atps . "' data-2='" . $thumbs . "'' data-3='" . $t_thumbs . "'' data-4='" . $m_thumbs . "''>";
                $output = apply_filters('gallery_style', $gallery_div);
                foreach ($ids as $id => $attachment) {
                    $output .= "<div class='gallery-item'>";
                    if (wp_get_attachment_caption($attachment->post_excerptt)) {
                        $output .= "
                            <div class='wp-caption-text gallery-caption " . $mar . "'>
                            " . wptexturize(wp_get_attachment_caption($attachment->post_excerpt)) . "
                            </div>";
                    }
                    $output .= "<div class='MGGS_gallery-icon landscape' style='background-image:url(" . wp_get_attachment_image_url($attachment, $size) . ");'></div></div>";
                }
                $output .= '</div><div class="MGGS_panel-control">
                        <div class="slider-dots"></div>
                        <div class="prev" style="background: "></div>
                        <div class="next"></div></div>';
                        if ($thumb == ' thumb') {
                            $output .= "<div class='MGGS_gallery-thumbs'>";
                            foreach ($ids as $id => $attachment) {
                                $output .= "<div class='gallery-item'>";
                                $output .= "<div class='MGGS_gallery-thumb landscape' style='background-image:url(" . wp_get_attachment_image_url($attachment, $size) . ");'></div>";
                                $output .= '</div>';
                            }
                            
                        }
                        $output .= '</div>';
                return $output;
            } else {
                return $block_content;
            }
    }, 10, 2);
} else {
    add_filter('post_gallery', 'MGGS_my_gallery_output', 10, 2);
    function MGGS_my_gallery_output($output, $attr)
    {
        $post = get_post();

        static $instance = 0;
        $instance++;

        if (!empty($attr['ids'])) {
            if (empty($attr['orderby'])) {
                $attr['orderby'] = 'post__in';
            }
            $attr['include'] = $attr['ids'];
        }

        if (!empty($output)) {
            return $output;
        }

        $html5 = current_theme_supports('html5', 'gallery');
        $atts  = shortcode_atts(
            array(
                'order'      => 'ASC',
                'orderby'    => 'menu_order ID',
                'id'         => $post ? $post->ID : 0,
                'itemtag'    => $html5 ? 'figure' : 'dl',
                'icontag'    => $html5 ? 'div' : 'dt',
                'captiontag' => $html5 ? 'figcaption' : 'dd',
                'columns'    => 3,
                'size'       => 'thumbnail',
                'include'    => '',
                'exclude'    => '',
                'link'       => '',
            ),
            $attr,
            'gallery'
        );

        $id = (int) $atts['id'];

        if (!empty($atts['include'])) {
            $_attachments = get_posts(
                array(
                    'include'        => $atts['include'],
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );

            $attachments = array();
            foreach ($_attachments as $key => $val) {
                $attachments[$val->ID] = $_attachments[$key];
            }
        } elseif (!empty($atts['exclude'])) {
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'exclude'        => $atts['exclude'],
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
        } else {
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
        }

        if (empty($attachments)) {
            return '';
        }

        if (is_feed()) {
            $output = "\n";
            foreach ($attachments as $att_id => $attachment) {
                if (!empty($atts['link'])) {
                    if ('none' === $atts['link']) {
                        $output .= wp_get_attachment_image($att_id, $atts['size'], false, $attr);
                    } else {
                        $output .= wp_get_attachment_link($att_id, $atts['size'], false);
                    }
                } else {
                    $output .= wp_get_attachment_link($att_id, $atts['size'], true);
                }
                $output .= "\n";
            }
            return $output;
        }

        $itemtag    = tag_escape($atts['itemtag']);
        $captiontag = tag_escape($atts['captiontag']);
        $icontag    = tag_escape($atts['icontag']);
        $valid_tags = wp_kses_allowed_html('post');
        if (!isset($valid_tags[$itemtag])) {
            $itemtag = 'dl';
        }
        if (!isset($valid_tags[$captiontag])) {
            $captiontag = 'dd';
        }
        if (!isset($valid_tags[$icontag])) {
            $icontag = 'dt';
        }

        $columns   = (int) $atts['columns'];
        $itemwidth = $columns > 0 ? floor(100 / $columns) : 100;
        $float     = is_rtl() ? 'right' : 'left';

        $selector = "gallery-{$instance}";

        $gallery_style = '';
        if (isset(get_option('slide_data')['MGGS_select_columns']) && get_option('slide_data')['MGGS_select_columns']) {
            $clmn = get_option('slide_data')['MGGS_select_columns'];
        } else {
            $clmn = 1;
        }
        if (isset(get_option('slide_data')['MGGS_nmb_autospeed']) && get_option('slide_data')['MGGS_nmb_autospeed'])
            $atps = get_option('slide_data')['MGGS_nmb_autospeed'];
        else
            $atps = 3000;
        if ($atts['columns'] == $clmn) {
            $clmn_clss = '';
            if (isset(get_option('slide_data')['MGGS_check_autoscroll']) && get_option('slide_data')['MGGS_check_autoscroll'] == 1) {
                $clmn_clss = ' auto';
            }
            if (isset(get_option('slide_data')['MGGS_check_thumbnails']) && get_option('slide_data')['MGGS_check_thumbnails'] == 1)
                $thumb = ' thumb';
            else
                $thumb = '';
            if (isset(get_option('slide_data')['MGGS_nmb_thumbnails']) && get_option('slide_data')['MGGS_nmb_thumbnails'])
                $thumbs = get_option('slide_data')['MGGS_nmb_thumbnails'];
            else
                $thumbs = 5;
            $mar = '';
            if ($thumb == ' thumb')
                $mar = ' t-mar';
                if (isset(get_option('slide_data')['MGGS_nmb_t_thumbnails']) && get_option('slide_data')['MGGS_nmb_t_thumbnails'])
                $t_thumbs = get_option('slide_data')['MGGS_nmb_t_thumbnails'];
            else
                $t_thumbs = 4;
                if (isset(get_option('slide_data')['MGGS_nmb_n_thumbnails']) && get_option('slide_data')['MGGS_nmb_m_thumbnails'])
                $m_thumbs = get_option('slide_data')['MGGS_nmb_m_thumbnails'];
            else
                $m_thumbs = 3;
            $size_class  = sanitize_html_class(is_array($atts['size']) ? implode('x', $atts['size']) : $atts['size']);
            $gallery_div = "<div class='MGGS'><div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class} MGGS_gallery-slider" . $clmn_clss . $thumb . "' data = '" . $atps . "' data-2='" . $thumbs . "'data-3='" . $t_thumbs . "'' data-4='" . $m_thumbs . "''>";
            $output = apply_filters('gallery_style', $gallery_div);
            $i = 0;

            foreach ($attachments as $id => $attachment) {
                $attr = (trim($attachment->post_excerpt)) ? array('aria-describedby' => "$selector-$id") : '';
                if (!empty($atts['link']) && 'file' === $atts['link']) {
                    $image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
                } elseif (!empty($atts['link']) && 'none' === $atts['link']) {
                    $image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
                } else {
                    $image_output = wp_get_attachment_link($id, $atts['size'], true, false, false, $attr);
                }

                $image_meta = wp_get_attachment_metadata($id);

                $orientation = '';

                if (isset($image_meta['height'], $image_meta['width'])) {
                    $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
                }
                $output .= "<div class='gallery-item'>";
                if ($captiontag && trim($attachment->post_excerpt)) {
                    $output .= "
                    <div class='wp-caption-text gallery-caption" . $mar . "' id='$selector-$id'>
                    " . wptexturize($attachment->post_excerpt) . "
                    </div>";
                }

                $output .= "<div class='MGGS_gallery-icon landscape' style='background-image:url(" . wp_get_attachment_image_url($id, $atts['size']) . ");'></div>";

                $output .= "</div>";
            }



            $output .= '</div><div class="MGGS_panel-control">
        <div class="slider-dots"></div>
        <div class="prev"></div>
        <div class="next"></div></div>';
            if ($thumb == ' thumb') {

                $output .= "<div class='MGGS_gallery-thumbs'>";
                foreach ($attachments as $id => $attachment) {
                    $attr = (trim($attachment->post_excerpt)) ? array('aria-describedby' => "$selector-$id") : '';
                    if (!empty($atts['link']) && 'file' === $atts['link']) {
                        $image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
                    } elseif (!empty($atts['link']) && 'none' === $atts['link']) {
                        $image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
                    } else {
                        $image_output = wp_get_attachment_link($id, $atts['size'], true, false, false, $attr);
                    }

                    $image_meta = wp_get_attachment_metadata($id);

                    $orientation = '';

                    if (isset($image_meta['height'], $image_meta['width'])) {
                        $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
                    }
                    $output .= "<div class='gallery-item'>";
                    $output .= "<div class='MGGS_gallery-thumb landscape' style='background-image:url(" . wp_get_attachment_image_url($id, $atts['size']) . ");'></div>";

                    $output .= "</div>";
                }
                $output .= "</div>";
            }
            $output .= "</div>";
            return $output;
        } else {
            if (apply_filters('use_default_gallery_style', !$html5)) {
                $type_attr = current_theme_supports('html5', 'style') ? '' : ' type="text/css"';

                $gallery_style = "
		<style{$type_attr}>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
			/* see gallery_shortcode() in wp-includes/media.php */
		</style>\n\t\t";
            }

            $size_class  = sanitize_html_class(is_array($atts['size']) ? implode('x', $atts['size']) : $atts['size']);
            $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

            $output = apply_filters('gallery_style', $gallery_style . $gallery_div);

            $i = 0;

            foreach ($attachments as $id => $attachment) {
                $attr = (trim($attachment->post_excerpt)) ? array('aria-describedby' => "$selector-$id") : '';

                if (!empty($atts['link']) && 'file' === $atts['link']) {
                    $image_output = wp_get_attachment_link($id, $atts['size'], false, false, false, $attr);
                } elseif (!empty($atts['link']) && 'none' === $atts['link']) {
                    $image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
                } else {
                    $image_output = wp_get_attachment_link($id, $atts['size'], true, false, false, $attr);
                }

                $image_meta = wp_get_attachment_metadata($id);

                $orientation = '';

                if (isset($image_meta['height'], $image_meta['width'])) {
                    $orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
                }

                $output .= "<{$itemtag} class='gallery-item'>";
                $output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";

                if ($captiontag && trim($attachment->post_excerpt)) {
                    $output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
                }

                $output .= "</{$itemtag}>";

                if (!$html5 && $columns > 0 && 0 === ++$i % $columns) {
                    $output .= '<br style="clear: both" />';
                }
            }

            if (!$html5 && $columns > 0 && 0 !== $i % $columns) {
                $output .= "
			<br style='clear: both' />";
            }

            $output .= "
		</div>\n";

            return $output;
        }
    }
}
