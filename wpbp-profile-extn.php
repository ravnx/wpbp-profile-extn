<?php 
/*
Plugin Name: WP BP Profile Extension
Plugin URI:  http://www.askmp.net/wpbp
Description: Allows a user to get shortcodes for buddy press profile data, and some regular profile data too
Version:     1.0.2
Author:      Michael Palmer
Author URI:  http://www.askmp.net
License:     GPL2 
License URI: http://link to your plugin license

Copyright 2019 Michael Palmer <michaelp@gmail.com>
WP BP Profile Extension is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP BP Profile Extension is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP BP Profile Extension. If not, see (http://link to your plugin license).
*/

// Added by michael palmer
function shortcode_userpic ($atts, $content=null) {
  extract(shortcode_atts(array(
    'user_id' => '',
    'bg' => '',
    'text' => '',
   ), $atts));
  global $wpdb;
  $prefix = $wpdb->prefix;
  // Make sure user_id is numeric
  if (!is_numeric($user_id)){ return("not found"); }
   $userPic = $wpdb->get_var("SELECT pm.meta_value FROM Tu8tC5SU_postmeta as pm 
            LEFT JOIN Tu8tC5SU_usermeta as um on (um.meta_value = pm.post_id)
                WHERE um.user_id = $user_id and pm.meta_key = '_wp_attached_file'" );
  // Default upload path is: ./wordpress/wp-content/uploads/
  return("/wordpress/wp-content/uploads/$userPic");
}
add_shortcode('userpic','shortcode_userpic');

function shortcode_profile ($atts, $content = null) {
  extract(shortcode_atts(array(
    'user_id' => '',
    'field' => '',), $atts));
  global $wpdb;
  $prefix = $wpdb->prefix;
  // Fields: agent_instagram, agent_linkedin, agent_twitter, agent_facebook, agent_phone, agent_your-option (organization name)
  $validfields = [
      'instagram' => 'agent_instagram',
      'linkedin' => 'agent_linkedin',
      'twitter' => 'agent_twitter',
      'facebook' => 'agent_facebook',
      'phone' => 'agent_phone',
      'organization' => 'agent_your-option',
      'description' => 'description',
      'shortdesc' => 'description'
    ];
    $pinfo = "Not Found - $field ";
    if (!is_numeric($user_id)){ return($pinfo); }
    if ($field == 'shortdesc') {
      $pinfo = $wpdb->get_var("SELECT meta_value FROM Tu8tC5SU_usermeta WHERE user_id = $user_id and meta_key = '".$validfields[$field]."'");
      return(substr($pinfo,0,135));
    }
    if ($field == 'email') {
      $pinfo = $wpdb->get_var("SELECT user_email FROM Tu8tC5SU_users WHERE ID = $user_id");
    }
    elseif ($validfields[$field]) {
      $pinfo = $wpdb->get_var("SELECT meta_value FROM Tu8tC5SU_usermeta WHERE user_id = $user_id and meta_key = '".$validfields[$field]."'");
    }
  return($pinfo);
}
add_shortcode('profile', 'shortcode_profile');

?>
