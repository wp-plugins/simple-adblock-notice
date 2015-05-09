<?php

/*
  Plugin Name: Simple Adblock Notice
  Plugin URI: http://techsini.com/our-wordpress-plugins/simple-adblock-notice
  Description: Simple Adblock Notice plugin shows a popup to whitelist your website in Adblock plus browser extension. 
  Version: 1.0.0
  Author: Shrinivas Naik
  Author URI: http://techsini.com
  License: GPL V3
 */


/*
Copyright (C) 2015 Shrinivas Naik

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.

*/


if(!class_exists('simple_adblock_notice') && !class_exists('simple_adblock_notice')){

    class simple_adblock_notice{

        private $options;

        public function __construct(){

            //Activate the plugin for first time
            register_activation_hook(__FILE__, array($this, "activate"));
            
            //Initialize settings page
            require_once(plugin_dir_path(__file__) . "settings.php");
            $simple_adblock_notice_settings = new simple_adblock_notice_settings();
            
            //Load scipts and styles
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'register_styles'));
            
            //Run the plugin in footer
            add_action('wp_footer', array($this, 'run_plugin'));

            //Store options in a variable
            $this->options = get_option( 'simple_adblock_notice_settings' );

            //Set cookie
            add_action( 'init', array($this,'simple_adblock_notice_cookie'));

            
        }

        
        public function activate(){

            //Set default options for the plugin
            $initial_settings = array(
                'noticeinterval'    =>  '20'
                );
            add_option("simple_adblock_notice_settings", $initial_settings);

        }
        
        public function deactivate(){

        }
        
        public function register_scripts(){
            wp_enqueue_script('jquery');
            wp_enqueue_script('SweetAlertJs', plugins_url( 'js/sweet-alert.min.js' , __FILE__ ),array( 'jquery' ));
            wp_enqueue_script('AdvertisementTester', plugins_url( 'js/advertisement.js' , __FILE__ ),array( 'jquery' ));
        }
        
        public function register_styles(){
            wp_enqueue_style( 'SweetAlertCSS', plugins_url('css/sweet-alert.css', __FILE__) );
            
        }
        
        public function run_plugin() {

            
            if($this->can_show_popup() == TRUE) {

                ?>

                <style type="text/css">
                    #advertisementtester {
                        display:none;
                    }
                </style>

                <script>
                jQuery(document).ready(function($) {

                    if( $('#advertisementtester').length ){
                        
                        //Do not show notice
                    
                    } else {

                        //show notice

                        swal({
                        title: "Adblock Detected",   
                        text: "We have detected that you are using adblocking plugin in your browser. \n \n The revenue we earn by the advertisements is used to manage this website, we request you to whitelist our website in your adblocking plugin.",   
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#01A9DB",   
                        confirmButtonText: "Yes, I'll Whitelist",   
                        cancelButtonText: "Cancel",   
                        closeOnConfirm: false,   closeOnCancel: false 
                    }, 
                    function(isConfirm){
                        if (isConfirm) {
                         swal("Thank you", "Thank you for whitelisting our website. :)", "success");   
                        } else {
                        swal("Oh..", "Oh.. Hope you whitelist our website in your adblocking plugin soon!", "error");   
                        } 
                    });
                    }

                    
                });   
                </script>
                
                
                <?php
                
                }
            }

            public function can_show_popup(){

                if(!isset($_COOKIE['simple_adblock_notice'])){
                //Show the popup
                    return true;
                } else {
                //Do not show the popup
                    return false;
                }
            }

            public function simple_adblock_notice_cookie(){

                if(!isset($_COOKIE['simple_adblock_notice']) && !is_admin()){
                    $noticeinterval = $this->options['noticeinterval'];
                    setcookie("simple_adblock_notice", "shown", time()+ (60 * $noticeinterval) , COOKIEPATH, COOKIE_DOMAIN, false);
                }
            }

        }

    }

    $simple_adblock_notice = new simple_adblock_notice();

    ?>