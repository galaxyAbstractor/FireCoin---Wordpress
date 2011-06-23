<?php
/*******************************************************************
* FireCoin - WordPress
*
* Copyright (c) 2011, galaxyAbstractor (http://pixomania.net)
* All rights reserved.

* Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

*    Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
*    Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT 
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
* HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
* LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON 
* ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE 
* USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* @copyright Copyright 2011, pixomania, http://pixomania.net
* @license BSD license (http://www.opensource.org/licenses/bsd-license.php)
********************************************************************/

/*
Plugin Name: FireCoin
Plugin URI: http://pixomania.net
Description: Allows you to add your BitCoin address to the header for the firefox addon FireCoin to detect
Version: 1.0
Author: galaxyAbstractor
Author URI: http://pixomania.net
License: BSD
*/


class FireCoin {
	/**
	 * This adds the needed meta tags to the header
	 */
	function addMeta() {
		$firecoinVars = get_option('FireCoin_Vars');
		if (!empty($firecoinVars['FireCoin_BitCoinAddress']))
			echo "<!-- Get FireCoin from https://addons.mozilla.org/en-US/firefox/addon/firecoin/ -->\n";
			echo "<meta name=\"bitcoin\" content=\"".$firecoinVars['FireCoin_BitCoinAddress']."\" />\n";
		if (!empty($firecoinVars['FireCoin_BitCoinMessage']))
			echo "<meta name=\"bitcoinmsg\" content=\"".$firecoinVars['FireCoin_BitCoinMessage']."\" />\n";
		if (!empty($firecoinVars['FireCoin_BitCoinUrl']))
			echo "<meta name=\"bitcointhanks\" content=\"".$firecoinVars['FireCoin_BitCoinUrl']."\" />\n";
	}
	
	/**
	 * Initialize the settings
	 */
	function init(){
		// Register our settings 
        register_setting(
            'FireCoin_Vars_Group', 
            'FireCoin_Vars', 
            array('FireCoin', 'Validate'));
 		
		// Add a new section
        add_settings_section(
            'FireCoin_Vars_ID', 
            'FireCoin Settings', 
            array('FireCoin', 'Overview'), 
            'FireCoin_Page_Title');
 		
		// Add the settings field for the BitCoin address
        add_settings_field(
            'FireCoin_BitCoinAddress', 
            'BitCoin Address:', 
            array('FireCoin', 'Address_Control'), 
            'FireCoin_Page_Title', 
            'FireCoin_Vars_ID');
		
		// Add the settings field for the message	
		add_settings_field(
            'FireCoin_BitCoinMessage', 
            'Donation Message:', 
            array('FireCoin', 'Message_Control'), 
            'FireCoin_Page_Title', 
            'FireCoin_Vars_ID');
		
		// Add the settings field for the thank you page	
		add_settings_field(
            'FireCoin_BitCoinUrl', 
            'Donation Thanks Page URL:', 
            array('FireCoin', 'URL_Control'), 
            'FireCoin_Page_Title', 
            'FireCoin_Vars_ID');
    }
 
 	/**
	 * Add the menu
	 */
    function adminMenus(){
    	// Check if the user can manage the blog options
        if (!function_exists('current_user_can')
            ||
            !current_user_can('manage_options'))
                return;
 
 		// Add the options page
        if (function_exists('add_options_page'))
            add_options_page(
                'FireCoin',
                'FireCoin', 
                'manage_options', 
                'FireCoin', 
                array('FireCoin', 'FireCoinForm'));
    }
	
	/**
	 * Create the form on the options page
	 */
	function FireCoinForm(){
		// Get the current options
	    $firecoinVars = get_option('FireCoin_Vars');
	 
	    ?>
	        <div class="wrap">
	            <?php screen_icon("options-general"); ?>
	                <h2>FireCoin <?php echo $firecoinVars['Version']; ?></h2>
	            <form action="options.php" method="post">
	                <?php settings_fields('FireCoin_Vars_Group'); ?>
	                <?php do_settings_sections('FireCoin_Page_Title'); ?>
	                <p class="submit">
	                    <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
	                </p>
	            </form>
	        </div> 
	    <?php 
	}

	/**
	 * The description of the page
	 */
	function Overview()
	{
	    ?>
	        <p>Here you will have to set your bitcoin address and some optional fields. Bitcoin address is the only required field. If you like this plugin, please donate to <br />
	        	<strong>1KHTs795SKBd2yBfdfpf4BxArEq5RGrNZo</strong>
	        </p>
	    <?php
	}
	
	/**
	 * Generate the Address input field
	 */
	function Address_Control(){
	    $firecoinVars = get_option('FireCoin_Vars');
	 
	    ?>
	        <input id="FireCoin_BitCoinAddress" 
	            name="FireCoin_Vars[FireCoin_BitCoinAddress]" 
	            class="regular-text" 
	            value="<?php echo $firecoinVars['FireCoin_BitCoinAddress']; ?>" />
	    <?php
	}
	
	/**
	 * Generate the Message input field
	 */
	function Message_Control(){
	    $firecoinVars = get_option('FireCoin_Vars');
	 
	    ?>
	        <input id="FireCoin_BitCoinMessage" 
	            name="FireCoin_Vars[FireCoin_BitCoinMessage]" 
	            class="regular-text" 
	            value="<?php echo $firecoinVars['FireCoin_BitCoinMessage']; ?>" />
	    <?php
	}
	
	/**
	 * Generate the URL input field
	 */
	function URL_Control(){
	    $firecoinVars = get_option('FireCoin_Vars');
	 
	    ?>
	        <input id="FireCoin_BitCoinUrl" 
	            name="FireCoin_Vars[FireCoin_BitCoinUrl]" 
	            class="regular-text" 
	            value="<?php echo $firecoinVars['FireCoin_BitCoinUrl']; ?>" />
	    <?php
	}
	
	/**
	 * Validate the input
	 * @param $input the form data
	 * @return the updated settings
	 */
	function Validate($input){
	    $firecoinVars = get_option('FireCoin_Vars');
	 
	    // Only the address is required
	    if (!empty($input['FireCoin_BitCoinAddress']))
	        $firecoinVars['FireCoin_BitCoinAddress'] = $input['FireCoin_BitCoinAddress'];
	    else
	        add_settings_error('FireCoin_Vars', 
	            'settings_updated', 
	            __('Address is required.'));
				
		$firecoinVars['FireCoin_BitCoinMessage'] = $input['FireCoin_BitCoinMessage'];
		$firecoinVars['FireCoin_BitCoinUrl'] = $input['FireCoin_BitCoinUrl'];
	 
	    return $firecoinVars;
	}
}

// Add the action to insert the meta into the header
add_action('wp_head', array('FireCoin','addMeta'));

// Register the settings
add_action('admin_init', 
    array('FireCoin', 'init'));
	
// Add the admin menu
add_action('admin_menu', 
    array('FireCoin', 'adminMenus'));
 
?>