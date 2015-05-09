<?php

class simple_adblock_notice_settings {
    
    //Holds the values to be used in the fields callbacks
    private $options;

    public function __construct(){
    
        add_action("admin_menu", array($this,"add_plugin_menu"));
        add_action("admin_init", array($this,"page_init"));
        
    }

    public function add_plugin_menu(){
        
        add_options_page( "Simple Adblock Notice", //page_title 
                         "Simple Adblock Notice", //menu_title
                         "administrator", //capability
                         "simple-adblock-notice-settings", //menu_slug
                         array($this, "create_admin_page")); //callback function
        
    }
    
    public function create_admin_page(){
         
        $this->options = get_option( 'simple_adblock_notice_settings' );
        
        ?>
            <div class="wrap">
                
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        
                        
                        <div id="post-body-content">
                            <div class="meta-box-sortables ui-sortable">
                                <div class="postbox">
                                    <h3><span class="dashicons dashicons-admin-generic"></span>Simple Adblock Notice Settings</h3>
                                    <div class="inside">
                                        <form method="post" action="options.php">
                                            <?php 
                                            // This prints out all hidden setting fields 
                                            settings_fields( 'simple_adblock_notice_settings_group' ); //option group
                                            do_settings_sections( 'simple-adblock-notice-settings' ); //settings page slug
                                            submit_button(); ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!--post-body-content-->
                        
                        
                        <!-- sidebar -->
                        <div id="postbox-container-1" class="postbox-container">
                            <div class="meta-box-sortables">
                                <div class="postbox">
                                    <h3><span>About</span></h3>
                                    <div class="inside">
                                        Author: Shrinivas<br>
                                        Website: <a href="http://techsini.com" target="_blank">TechSini.com</a> <br>
                                        Thank you for installing this plugin.
                                    </div> <!-- .inside -->
                                </div> <!-- .postbox -->
                            </div> <!-- .meta-box-sortables -->
                        </div> <!-- #postbox-container-1 .postbox-container -->
                        
                        
                    </div>
                </div>
            </div>
        <?php
    }
    
    public function page_init(){
            
        register_setting(
        'simple_adblock_notice_settings_group', // Option group
        'simple_adblock_notice_settings' // Option name
        );
        
        add_settings_section(
            'section_1', // ID
            '', // Title
            array( $this, 'section_1_callback' ), // Callback
            'simple-adblock-notice-settings' // Page
        );  

        add_settings_field(
            'noticeinterval', // ID
            'Noice Interval (in minutes)', // Title 
            array( $this, 'noticeinterval_callback' ), // Callback
            'simple-adblock-notice-settings', // Page
            'section_1' // Section           
        );

        

    }
    public function section_1_callback(){
        
    }

    public function noticeinterval_callback(){

        $noticeinterval = $this->options['noticeinterval'];
        echo ('<select id="noticeinterval" name="simple_adblock_notice_settings[noticeinterval]">' .
                '<option value="5" ' . selected($noticeinterval, "5", false) . '>5</option>' . 
                '<option value="10" ' . selected($noticeinterval, "10", false) . '>10</option>' . 
                '<option value="20" ' . selected($noticeinterval, "20", false) . '>20</option>' .
                '<option value="30" ' . selected($noticeinterval, "30", false) . '>30</option>' .
                '<option value="60" ' . selected($noticeinterval, "60", false) . '>60</option>' .
                '</select>'
            );
    }

    
    
}


?>