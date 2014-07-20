<?php
/*
Plugin Name: Sticky Editor Sidebar
Plugin URI: http://reaktivstudios.com/custom-plugins/
Description: Makes the editor sidebar (publish and other meta) sticky when scrolling
Author: Andrew Norcross
Version: 0.0.1
Requires at least: 3.8
Author URI: http://andrewnorcross.com
*/
/*  Copyright 2014 Andrew Norcross

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License (GPL v2) only.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if( ! defined( 'STICKY_SIDE_BASE ' ) ) {
    define( 'STICKY_SIDE_BASE', plugin_basename(__FILE__) );
}

if( ! defined( 'STICKY_SIDE_DIR' ) ) {
    define( 'STICKY_SIDE_DIR', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'STICKY_SIDE_VER' ) ) {
    define( 'STICKY_SIDE_VER', '0.0.1' );
}

class Sticky_Editor_Sidebar
{

    /**
     * Static property to hold our singleton instance
     * @var Code_Docs_Core
     */
    static $instance = false;

    /**
     * [__construct description]
     */
    private function __construct() {
        add_action          (   'plugins_loaded',                       array(  $this,  'textdomain'             )           );
        add_filter          (   'admin_body_class',                     array(  $this,  'sticky_body_class'      )           );
        add_action          (   'admin_enqueue_scripts',                array(  $this,  'scripts_styles'         ),  10      );
    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * retuns it.
     *
     * @return
     */

    public static function getInstance() {
        if ( !self::$instance )
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * [textdomain description]
     * @return [type] [description]
     */
    public function textdomain() {

        load_plugin_textdomain( 'sticky-editor-sidebar', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * adds our body class on the admin side
     * to the types we want the sticky applied
     * to
     *
     * @param  [type] $classes [description]
     * @return [type]          [description]
     */
    public function sticky_body_class( $classes ) {

        // bail on non-admin
        if ( ! is_admin() ) {
            return $classes;
        }

        // get our post types
        $types = $this->types();

        // bail without types
        if ( ! $types ) {
            return $classes;
        }

        // get our global post object
        global $post;

        // bail if we have no post object
        if ( ! $post || ! is_object( $post ) ) {
            return $classes;
        }

        // our two conditionals
        if ( in_array( $post->post_type, $types ) ) {
            $classes .= 'sticky-editor-side';
        }
        // send it back
        return $classes;

    }

    /**
     * Load CSS and JS files
     *
     * @return
     */

    public function scripts_styles( $hook ) {

        // get our post types
        $types = $this->types();

        // bail without types
        if ( ! $types ) {
            return;
        }

        // fetch current screen object
        $screen = get_current_screen();

        // check for our screen object and our post type
        if ( ! is_object( $screen ) || ! empty( $screen->post_type ) && ! in_array( $screen->post_type, $types ) ) {
            return;
        }

        // JS
        wp_enqueue_script( 'ses-admin', plugins_url( '/lib/js/ses-admin.js', __FILE__ ), array( 'jquery' ), STICKY_SIDE_VER, true );
        wp_localize_script( 'ses-admin', 'sesOptions', array(
            'opacity'   => apply_filters( 'sticky_side_opacity', 0.35 ),
        ));
    }

    /**
     * set the post types we want to apply
     * the sticky sidebar to, with optional
     * filter
     *
     * @return [type] [description]
     */
    public function types() {
        return apply_filters( 'sticky_side_allowed_types', array( 'post', 'page' ) );
    }

/// end class
}

// Instantiate our class
$Sticky_Editor_Sidebar = Sticky_Editor_Sidebar::getInstance();