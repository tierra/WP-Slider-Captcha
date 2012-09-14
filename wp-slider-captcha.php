<?php

    /*
    Plugin Name: WP Slider Captcha
    Plugin URI: http://ditoforge.com/
    Description: jQuery UI Slider Captcha
    Version: 1.1.0
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
    

    // minimum required version check
    function minimum_version_check() {

        // get the current version of wordpress
        global $wp_version;

        // get the name of this plugin and an array of it's related data
        $plugin      = plugin_basename( __FILE__ );
        $plugin_data = get_plugin_data( __FILE__, false );

        // is it less than the quoted version?
        if ( version_compare( $wp_version, "3.3", "<" ) ) {

            // it is, eh? is it activated already?
            if ( is_plugin_active ( $plugin ) ) {
                
                // IT IS?! KILL IT!! KILL IT WITH FIRE!!1
                deactivate_plugins( $plugin );
                
                // now inform Dave that you are sorry you can't do that...
                wp_die( "'".$plugin_data['Name']
                       ."' requires Wordpress version 3.0 or higher, so it cannot be activated. Please upgrade your instance of Wordpress to use it.");
            }
        }
    }

    // kick it off by checking the version
    add_action( 'admin_init', 'captcha' );


    // register your settings with Wordpress to be accessible later
    function sc_register_settings() {
        register_setting( 'sc_settings_group', 'threshhold' );
        register_setting( 'sc_settings_group', 'form-id' );
        register_setting( 'sc_settings_group', 'button-id' );
    }

    
    // main function, called on initialization
    function captcha() {

        // register the settings in the database
        add_action( 'admin_init', 'sc_register_settings' );

        // register your script, where to find the JavaScript file, and dependencies
        wp_register_script(
            'wp_slider_captcha',
            plugins_url(
                '/wp-slider-captcha/wp-slider-captcha.js'
            ),
            array(
              'jquery',
              'jquery-ui'
            ),
            '1.1'
        );

        // register your CSS
        wp_register_style(
            'wp-slider-captcha',
            plugins_url(
                '/wp-slider-captcha/wp-slider-captcha.css'
            )
        );
        
        
        wp_enqueue_style('wp-slider-captcha');
        wp_enqueue_script('wp_slider_captcha');

        add_action(
           'admin_menu',        // a hook for adding to the admin menu
           'admin_menu_init'    // what function to use for that hook
        );

    }


    //
    function admin_menu_init() {

        add_options_page(
             'WP Slider Captcha Options',   // title of the page
             'WP Slider Captcha',           // foo
             'manage_options',              // permission/role that is the minimum for this
             'wp_slider_captcha',           // foo
             'wp_slider_options'            // function callback
        );

    }


    //
    function wp_slider_options() {

        echo '<div class="wrap">
             '.screen_icon().'
            <h2>WP Slider Captcha Options</h2>
            <p>Options to customize your install of WP Slider Captcha</p>
        </div>
        <div class="wrap">';

        if ( !current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $foo = get_option( 'threshhold' );

        echo "<pre>";
        print_r($foo);
        echo "</pre>";

        
        echo '<form action="options.php" method="post">
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
                                        <input id="form-id" type="input" value="commentform" name="form-id">
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
                                        <input id="submit-button-id" type="input" value="" name="submit-button-id">
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
    }
?>