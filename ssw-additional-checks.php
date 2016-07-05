<?php

/**
 *
 * Plugin Name:       Site Setup Wizard Additional Checks
 * Plugin URI:        https://github.com/neelakansha85/ssw-additional-checks
 * Description:       This plugin provides additional checks a super admin can customize before displaying Site Setup Wizard's steps to a user.
 * Version:           1.0.0
 * Author:            Neel Shah <neel@nsdesigners.com>
 * Author URI:        http://neelshah.info
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if(!class_exists('SSW_Additional_Checks')) {

  class SSW_Additional_Checks {
    public function __construct() {
      // Add filter to perform additional checks before loading all steps 
      add_filter('ssw_additional_checks', array($this, 'ssw_limit_no_of_sites'));

    }

    /**
     * Custom function to limit number of sites a user can create using Site Setup Wizard.
     * 
     * @return bool true if user is NOT allowed to create another site
     */
    function ssw_limit_no_of_sites() {
      global $wpdb;
      global $current_user;

      // Number of sites allowed to be created by a user
      $allowed_sites = 1;

      $current_user_id = $current_user->ID;

      $results = $wpdb->get_results($wpdb->prepare("SELECT COUNT(*) AS total FROM ".$wpdb->prefix."nsd_site_setup_wizard WHERE `user_id` = %d AND `site_created` = 1 AND `wizard_completed` = 1", $current_user_id));
      
      foreach ($results as $obj) {
        $count = $obj->total;
      }

      if(isset($count) && $count >= $allowed_sites) {
        echo '<p> You are not allowed to create more than 1 site. Please contact your site administrator for further questions.</p>';
        return true;
      }
    }
  }
}

if(class_exists('SSW_Additional_Checks')) {
  $ssw_additional_checks = new SSW_Additional_Checks();
}
?>