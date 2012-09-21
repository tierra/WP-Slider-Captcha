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



  
    function wpsc_admin_menu() {

        add_options_page(
             'WP Slider Captcha Options',   // title of the page
             'WP Slider Captcha',           // text to be used in options menu
             'manage_options',              // permission/role that is the minimum for this
             'wpsc',                        // menu slug
             'wp_slider_options'            // function callback
        );

    }




    // register your settings with Wordpress to be accessible later
    function wpsc_settings() {

        register_setting(
            'sc_settings_group',    // id for the group, just a slug
            'threshold',            // data object name/id
            'threshold_sanitize'    // callback function for sanitizing
        );

        register_setting( 'sc_settings_group', 'form_id', 'form_sanitize' );

        add_settings_section(
            'sc_settings_group',
            'Main Settings',
            'plugin_section_text',
            'wpsc'
        );

        add_settings_field(
            'threshold',
            'Threshold:',
            'threshold_callback',
            'wpsc',
            'sc_settings_group',
            array('foobarbaz')
        );

    }

    function plugin_section_text() {
        echo "tra la la";
    }

    function threshold_callback() {
        echo "trolololol";
    }






    function threshold_sanitize($input) {
        $trimmed = trim($input);

        if( !is_numeric($trimmed) || $trimmed > 100 || $trimmed < 0 ) {
            $trimmed = 60;
        }

        return $trimmed;
    }

    function form_sanitize($str) {
        $string = trim($str);

        $pattern = '/^[\w]+[-]?+[\w]+$/';

        if( !preg_match( $pattern, $string ) ) {
            $string = 'commentform';
        }

        return $string;
    }





    // function to pass the settings to JavaScript
    function wpsc_passon() {

        // nested array of data to pass to our JavaScript
        $data = array(
            'threshold' => get_option( 'threshold' ),
            'form_id'   => get_option( 'form-id' )
        );

        // pass the variables to javascript as JSON data
        wp_localize_script(
            'wpsc-scripts',     // script to pass variables to
            'wpsc_settings',    // object name to give to javascript
            $data               // data to pass in
        );

    }



    // function for creating the options page
    function wp_slider_options() {

        echo '<div class="wrap">' .
             screen_icon() .
            '<h2>WP Slider Captcha Options</h2>
            <p>Options to customize your install of WP Slider Captcha</p>
        </div>
        <div class="wrap">
            <form action="options.php" method="post">';
                settings_fields( 'sc_settings_group' );

                echo '<table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row">Threshold</th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span>Threshold</span>
                                    </legend>
                                    <label for="threshhold">
                                        <input id="threshold" type="input" value="'. get_option( 'threshhold' ) .'" name="threshhold">
                                        <p>
                                            <em>What percent point to slide past</em>
                                            <br>
                                            <em>Default is 60, i.e. 60% of slider width</em>
                                        </p>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scrope="row">Form ID</th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span>Form ID</span>
                                    </legend>
                                    <label for="form-id">
                                        <input id="form-id" type="input" value="'. get_option( 'form-id' ) .'" name="form-id">
                                        <p>
                                            <em>Default is "commentform" (without quotes)</em>
                                        </p>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">&nbsp;</th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span>Save The Settings</span>
                                    </legend>
                                    <label>';
                                        do_settings_sections('sc_settings_group');
                                        submit_button();
                                    echo '</label>
                                </fieldset>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>';

    }



?>