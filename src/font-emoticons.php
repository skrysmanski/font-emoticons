<?php
/*
Plugin Name: Font Emoticons
Plugin URI: https://github.com/skrysmanski/font-emoticons
Description: Replace the standard WP Smileys with font icons.
Version: 1.5.0
Author: Sebastian Krysmanski
Author URI: https://manski.net
*/
# ^-- https://developer.wordpress.org/plugins/the-basics/header-requirements/

require_once(dirname(__FILE__).'/FontEmoticonsPlugin.php');

FontEmoticonsPlugin::init();

register_deactivation_hook(__FILE__, 'fe_plugin_deactivated');
function fe_plugin_deactivated()
{
    # Re-enable Wordpress smileys
    #
    # NOTE: Even though we now (since 1.5.0) we use a filter to disable Wordpress'
    #   smileys, we still have to reset this option back to "1" or Wordpress' smileys
    #   wont work after disabling font-emoticons. Unfortunately, Wordpress has removed
    #   the option in the UI to change this setting and I don't want the user to dive
    #   into the database to re-enable this manually if they disable font-emoticons.
    #   I know there may be cases where re-enabling this is not the right thing but I
    #   guess in the majority of all cases this is what the user wants.
    update_option('use_smilies', 1);
}
