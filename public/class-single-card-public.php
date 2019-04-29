<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/baberparweez
 * @since      1.0.0
 *
 * @package    Single_Card
 * @subpackage Single_Card/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Single_Card
 * @subpackage Single_Card/public
 * @author     Baber Parweez <baberparweez@gmail.com>
 */
class Single_Card_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Single_Card_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Single_Card_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/single-card-public.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/reset.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Single_Card_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Single_Card_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        //wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/single-card-public.js', array('jquery'), $this->version, true);
    }


    /*
    * Shortcode for supporting related posts in VC
    *
    */
    public function single_card($atts = array())
    {

        // echo "TEST";
        // die();

        $output = '';

        /** Set shortcode attributes */
        $a = shortcode_atts(array(
            'card_type' => '',
            'article_id' => '',
            'custom_image' => '',
            'custom_title' => '',
            'custom_link' => '',
            'btn_text' => '',
            'custom_excerpt' => '',
            'post' => '',
        ), $atts);
        $posts = get_post($a['article_id']);

        /** If custo, card selected... */
        if ($a['card_type'] == 'custom_card') {
            if ($a['custom_image'] != "") {
                $image_src = wp_get_attachment_image_src($a['custom_image'], 'single-post-thumbnail');
                $image = $image_src[0];
            }
            if ($a['custom_title'] != "") {
                $title = $a['custom_title'];
            }
            if ($a['custom_link'] != "") {
                $link = $a['custom_link'];
            }
            if ($a['btn_text'] != "") {
                $btnText = $a['btn_text'];
            } else {
                $btnText = 'More';
            }
            if ($a['custom_excerpt'] != "") {
                $excerpt = wp_trim_words($a['custom_excerpt'], 30);
            }
        }
        /** If article selected from dropdown... */
        else if ($a['article_id'] != "") {
            $title = get_the_title($posts->ID);
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($posts->ID), 'single-post-thumbnail');
            $post_content = get_the_content($posts->ID);
            $image = $image[0];
            $btnText = 'More';
            $link = get_the_permalink($posts->ID);

            if ($posts->post_type == "page") {
                if (isset($posts->post_excerpt)) {
                    $excerpt = wp_kses_post(wp_trim_words($posts->post_excerpt, 30));
                }
            } else {
                if (empty($posts->post_excerpt)) {
                    $excerpt = wp_kses_post(wp_trim_words($posts->post_content, 30));
                } else {
                    $excerpt = wp_kses_post(wp_trim_words($posts->post_excerpt, 30));
                }
            }
        }
        /** If nothing selected from article dropdown... */
        else {
            $postType = 'post';
            if ($a['post'] != "") {
                $postType = $a['post'];
            }

            $cat = get_the_category($posts);
            $args = array(
                'numberposts' => 1,
                'orderby' => 'post_date',
                'order' => 'DESC',
                'post_type' => $postType,
                'post_status' => 'publish',
            );

            $recentID = wp_get_recent_posts($args);

            if ($recentID[0]["ID"] == $posts->ID) {
                $args = array(
                    'numberposts' => 1,
                    'orderby' => 'post_date',
                    'order' => 'DESC',
                    'post_type' => $postType,
                    'post_status' => 'publish',
                    'offset' => 1,
                );
            }
            $recent = wp_get_recent_posts($args);

            foreach ($recent as $post) {
                $title = $post["post_title"];
                $post_content = $post["post_content"];
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post["ID"]), 'single-post-thumbnail');
                $image = $image[0];
                $btnText = 'More';
                $link = get_the_permalink($post["ID"]);

                if (empty($post["post_excerpt"])) {
                    $excerpt = wp_kses_post(wp_trim_words($post["post_content"], 30));
                } else {
                    $excerpt = wp_kses_post(wp_trim_words($post["post_excerpt"], 30));
                }
            }
            wp_reset_query();
        }

        /** Image variable, if set */
        if (!isset($uploadDir)) {
            $uploadDir = plugin_dir_url(__FILE__) . 'images/placeholder.png';
        }
        if (!isset($image)) {
            $image = $uploadDir;
        }

        $button = '<a href="' . $link . '" class="masonry-button">' . $btnText . '</a>';
        $output .= '<div class="masonry-card single">
                    <div class="masonry-card_image_outer">
                        <a href="' . $link . '"><div class="masonry-card_image" style="background: url(' . $image . ') no-repeat center; background-size: cover;"></div>
                        </a>
                    </div>
                <div class="masonry-card-inner">';

        /** Title variable, if set */
        if (isset($title) && $title != '') {
            $output .= '<a class="masonry-card_header" href="' . $link . '" target=""><h3>' . $title . '</h3></a>';
        }

        /** Excerpt variable, if set */
        if (isset($excerpt) && $excerpt != '') {
            $output .= "<p>{$excerpt}</p>";
        }

        $output .= '</div>';

        /** Add button, if link set */
        if (isset($link) && $link != '' || $a['custom_link'] != "") {
            $output .= $button;
        }

        $output .= '</div>';

        ob_start();

        WPBMap::addAllMappedShortcodes();
        echo $output;

        return ob_get_clean();
    }
}
