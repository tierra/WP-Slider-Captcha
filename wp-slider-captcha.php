<?php

    /*
    Plugin Name: WP Slider Captcha
    Plugin URI: http://ditoforge.com/
    Description: jQuery UI Slider Captcha
    Version: 1.0
    Author: Brian J. Rogers
    Author URI: http://ditoforge.com
    License: GPL
    */
    
    add_action('init','captcha');
    
    function captcha() {
        wp_register_script(
            'wp_slider_captcha',
            plugins_url('/wp-slider-captcha/wp-slider-captcha.js'),
            array('jquery', 'jquery-ui'),
            '1.0'
        );
        
        wp_register_style(
            'wp-slider-captcha',
            plugins_url('/wp-slider-captcha/wp-slider-captcha.css')
        );
        
        
        wp_enqueue_style('wp-slider-captcha');
        
        add_action('wp_enqueue_scripts', 'required_scripts');
        wp_enqueue_script('wp_slider_captcha');
    }
    
    function required_scripts() {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
        wp_enqueue_script('jquery');
        
        wp_deregister_script('jquery-ui');
        wp_register_script('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js');
        wp_enqueue_script('jquery-ui');
    }    
 

?>