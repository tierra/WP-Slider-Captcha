<?php

    /*
    Plugin Name: WP Slider Captcha
    Plugin URI: http://ditoforge.com/
    Description: jQuery UI Slider Captcha
    Version: 1.2.0
    Author: Brian J. Rogers
    Author URI: http://ditoforge.com
    License: GPLv3
    */

    /*  
    Copyright 2012 Brian J. Rogers (email : captbrogers@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    If you want a written copy of the GNU General Public License,
    write to the Free Software Foundation, Inc., 
    51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    */
    


    // kick it all off
    add_action(
        'init',             // action hook, e.g. when to execute the attached function
        'wpsc_scripts'      // the attached function to run
    );



    // settings given to JS from PHP
    add_action( 'init', 'wpsc_passon' );



    // for the options page in the admin menu
    add_action( 'admin_menu', 'wpsc_admin_menu' );



    // menu is created, now register the functions
    add_action( 'admin_init', 'wpsc_settings' );



    function wpsc_scripts() {

        // register our javascript with wordpress
        wp_register_script(
            'wpsc-scripts',                                     // slug or handle to reference this
            plugins_url( '/wp-slider-captcha.js', __FILE__ ),   // which file, location (__FILE__ references this directory)
            array(
                'jquery',                                       // jquery is required
                'jquery-ui-slider'                              // jquery ui slider is as well
            )
        );

        // now enque the scripts for wordpress to call on later
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'wpsc-scripts' );

        // registering a stylesheet
        wp_register_style(
            'wpsc-styles',                                        // again, the slug or handle
            plugins_url( '/wp-slider-captcha.css', __FILE__ )     // same as above
        );

        // and now give it to wordpress to load
        wp_enqueue_style( 'wpsc-styles' );

    }



    // add a sub-menu item for this page
    function wpsc_admin_menu() {

        add_options_page(
             'WP Slider Captcha Options',   // title of the page
             'WP Slider Captcha',           // text to be used in options menu
             'manage_options',              // permission/role that is the minimum for this
             'wpsc',                        // menu slug
             'wpsc_slider_options'          // function callback
        );

    }



    // register your settings with Wordpress to be accessible later
    function wpsc_settings() {

        add_settings_section(
            'wpsc_settings_group',          // id of the group
            'WP Slider Captcha Settings',   // title of the section
            'plugin_section_text',          // obligatory callback function for rendering
            'wpsc'                          // what page (slug) to add it to
        );

        register_setting(
            'wpsc_settings_group',      // what group it belongs to
            'wpsc_threshold',           // data object name/id
            'wpsc_threshold_sanitize'   // callback function for sanitizing
        );

        register_setting(
            'wpsc_settings_group',
            'wpsc_form_id',
            'form_sanitize'
        );

        add_settings_field(
            'wpsc_threshold',           // id for this setting
            'Threshold:',               // label
            'wpsc_threshold_callback',  // callback function for rendering
            'wpsc',                     // page to add this setting to
            'wpsc_settings_group'       // settings group to attach to 
        );

        add_settings_field(
            'wpsc_form_id',
            'Form ID',
            'wpsc_form_id_callback',
            'wpsc',
            'wpsc_settings_group'
        );

    }



    // description area for your options page
    function plugin_section_text() {
        echo "<p>You can control when it counts as accepted, and what form to put it in!</p>";
    }



    // render the following for the threshold
    function wpsc_threshold_callback() { ?>
        <input id="threshold" type="input" value="<?php get_option( 'wpsc_threshold', 60 ); ?>" name="wpsc_threshold">
        <p>
            <em>What percent point to slide past</em>
            <br>
            <em>Default is 60, i.e. 60% of slider width</em>
        </p>
    <?php }



    // render the following for the form id field
    function wpsc_form_id_callback() { ?>
        <input id="form-id" type="input" value="<?php get_option( 'wpsc_form_id', 'commentform' ); ?>" name="wpsc_form_id">
        <p>
            <em>Default is "commentform" (without quotes)</em>
        </p>
    <?php }



    // make sure they only submit an integer between 1 and 100
    function wpsc_threshold_sanitize($input) {
        $trimmed = (int) trim($input);

        if( !is_numeric($trimmed) || $trimmed > 101 || $trimmed < 0 ) {
            $trimmed = 60;
        }

        return $trimmed;
    }



    // regex to keep the id of the form within recommendataion
    // can be letters and have hyphens in it
    function wpsc_form_sanitize($str) {
        $string = trim($str);

        $pattern = '/^[\w]+[-]?+[\w]+$/gim';

        if( !preg_match( $pattern, $string ) ) {
            $string = 'commentform';
        }

        return $string;
    }



    // function to pass the settings to JavaScript
    function wpsc_passon() {

        // nested array of data to pass to our JavaScript
        $data = array(
            'wpsc_threshold' => get_option( 'wpsc_threshold' ),
            'wpsc_form_id'   => get_option( 'wpsc_form_id' )
        );

        // pass the variables to javascript as JSON data
        wp_localize_script(
            'wpsc-scripts',     // script to pass variables to
            'wpsc_settings',    // object name to give to javascript
            $data               // data to pass in
        );

    }



    // function for creating the options page
    function wpsc_slider_options() {

        echo '<div class="wrap">
            <form action="options.php" method="post">';
                settings_fields( 'wpsc_settings_group' );
                do_settings_sections( 'wpsc' );
                submit_button();
            echo '</form>
        </div>';

    }
?>