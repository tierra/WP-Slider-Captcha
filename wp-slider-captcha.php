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

    // for the options page in the admin menu
    add_action(
        'admin_menu',
        'wpsc_admin_menu'
    );

    function wpsc_scripts() {

        // register our javascript with wordpress
        wp_register_script(
            'wpsc-scripts',                                     // slug or handle to reference this
            plugins_url( '/wp-slider-captcha.js', __FILE__ ),     // which file, location (__FILE__ references this directory)
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
            'wpsc-styles',                                      // again, the slug or handle
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
        register_setting( 'sc_settings_group', 'threshhold' );
        register_setting( 'sc_settings_group', 'form-id' );
        register_setting( 'sc_settings_group', 'button-id' );
        register_setting( 'sc_settings_group', 'no-id' );
        register_setting( 'element', '' );
    }

    // function to pass the settings to JavaScript
    function wpsc_passon() {
        // nested array of data to pass to our JavaScript
        $data = array(
            array( 'threshhold', get_option( 'threshhold' ) ),
            array( 'form-id', get_option( 'form-id' ) ),
            array( 'button-id', get_option( 'button-id' ) ),
            array( 'no-id', get_option( 'no-id' ) ),
            array( 'element', get_option( 'element' ) )
        );

        // pass the variables to javascript as JSON data
        wp_localize_script(
            'wpsc-scripts',                 // script to pass variables to
            'wpscscripts',                 // object name to give to javascript
            $data                           // data to pass in
        );
    }

    // function for creating the options page
    function wp_slider_options() {

        if( !get_option( 'threshhold' ) || !get_option( 'form-id' ) || !get_option( 'button-id' ) ) {
            update_option(
                'threshhold',               // variable name
                '60'                        // variable value
            );

            update_option(
                'form-id',
                'commentform'
            );
            
            update_option(
                'button-id',
                ''
            );

            update_option(
                'no-id',
                false
            );

            update_option(
                'element',
                ''
            );
        }

        // hook in the settings
        add_action( 'init', 'wpsc_passon' );

        //else {
            echo '<div class="wrap">
                 '.screen_icon().'
                <h2>WP Slider Captcha Options</h2>
                <p>Options to customize your install of WP Slider Captcha</p>
            </div>
            <div class="wrap">
                <form action="options.php" method="post">
                    '. settings_fields( 'sc_settings_group' ) .'
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row">Threshold</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span>Threshold</span>
                                        </legend>
                                        <label for="threshhold">
                                            <input id="threshold" type="input" value="'.get_option( 'threshhold' ).'" name="threshhold">
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
                                            <input id="form-id" type="input" value="'.get_option( 'form-id' ).'" name="form-id">
                                            <p>
                                                <em>Default is "commentform" (without quotes)</em>
                                            </p>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scrope="row">Submit Button ID</th>
                                <td>
                                    <fieldset>
                                        <legend class="screen-reader-text">
                                            <span>Submit Button ID</span>
                                        </legend>
                                        <label for="submit-button-id">
                                            <input id="submit-button-id" type="input" value="'.get_option( 'button-id' ).'" name="submit-button-id">
                                            <p>
                                                <em>Default is blank</em>
                                            </p>
                                        </label>
                                        <legend class="screen-reader-text">
                                            <span>No ID for submit button</span>
                                        </legend>
                                        <label for="no-id">
                                            <input id="no-id" type="checkbox" value="0" name="no-id">
                                            I don\'t have an ID on my submit button.
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
                                        <label>
                                            <button type="submit" class="button-primary">Save These Settings</button>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>';
            echo '</div>';
        //}
    }
?>