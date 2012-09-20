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



        // menu is created, now register the functions
        add_action( 'admin_init', 'wpsc_settings' );
    }

    // register your settings with Wordpress to be accessible later
    function wpsc_settings() {
        add_settings_field(
            'threshold',    // id to attach to the input field
            'Threshold',    // label for the input field
            'thresh',       // callback function
            'wpsc'          // what page to add them to, must match menu slug
        );

        add_settings_field( 'form-id', 'Form ID', '', 'wpsc' );

        register_setting(
            'sc_settings_group',    // id for the group, just a slug
            'threshold'             // data object name/id
        );

        register_setting( 'sc_settings_group', 'form_id' );
    }

    function thresh() {
        echo '<input type="text">';
    }

    // function to pass the settings to JavaScript
    function wpsc_passon() {
        // nested array of data to pass to our JavaScript
        $data = array(
            array( 'threshold', get_option( 'threshold' ) ),
            array( 'form_id', get_option( 'form-id' ) )
        );

        // pass the variables to javascript as JSON data
        wp_localize_script(
            'wpsc-scripts',                 // script to pass variables to
            'wpsc_settings',                // object name to give to javascript
            $data                           // data to pass in
        );
    }

    // function for creating the options page
    function wp_slider_options() {

        /*if( !get_option( 'threshold' ) || !get_option( 'form-id' ) || !get_option( 'button-id' ) ) {
            update_option(
                'threshold',               // variable name
                '60'                       // variable value
            );

            update_option( 'form-id', 'commentform' );
        }*/

        add_settings_section(
            'foo',
            'Foo',
            'foo',
            'wpsc'
        );

        
        add_settings_field(
            'threshold',
            'Threshold:',
            'threshold_setting',
            __FILE__,
            'sc_settings_group',
            array( 'label_for' => 'threshold')
        );
    }

    function threshold_setting() {
        echo '<p>Foo!!!!</p>';
    }
?>