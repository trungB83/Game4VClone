<?php
/*
  Plugin Name: WP User
  Plugin URI: http://wpuserplus.com/
  Description: Create elegant Login, Register, and Forgot Password form on Page, widget or Popups on your website, in just minutes with AJAX.
  Author: Prashant Walke
  Version: 6.4.2
  Author URI: http://wpuserplus.com/
  Text Domain: wpuser
  Domain Path: /lang
  License: GPLv2
 */

/*
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('WPUser')) :

    final class WPUser
    {

        public $version = '6.4';
        public $WPUSERprefix = "wpuser";
        protected static $_instance = null;
        public $query = null;

        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct()
        {
            // Define constants
            $this->define_constants();
            register_activation_hook(__FILE__, array($this, 'installation'));
            register_activation_hook(__FILE__, array($this, 'my_plugin_install_function'));
            add_action('plugins_loaded', array($this, 'load_textdomain'));
            $this->installation();
            // Include required files
            $this->includes();
        }

        function my_plugin_install_function()
        {
            //post status and options
            $wp_user_page=get_option('wp_user_page');
            if(empty($wp_user_page)) {
                $post = array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => 1,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_name' => 'User',
                    'post_status' => 'publish',
                    'post_title' => 'User',
                    'post_content' => '[wp_user]',
                    'post_type' => 'page',
                );
                //insert page and save the id
                $newvalue = wp_insert_post($post, false);
                //save the id in the database
                update_option('wp_user_page', $newvalue);
            }
        }

        private function define_constants()
        {
            define('WPUSER_PLUGIN_FILE', __FILE__);
            define('WPUSER_PLUGIN_URL', plugin_dir_url(__FILE__));
            define('WPUSER_PLUGIN_DIR', plugin_dir_path(__FILE__));
            define('WPUSER_PLUGIN_ASSETS_URL', plugin_dir_url(__FILE__). 'assets');
            define('WPUSER_TEMPLETE_URL', plugin_dir_url(__FILE__) . 'includes/admin/view/');
            define('WPUSER_USER_TEMPLETE_URL', plugin_dir_url(__FILE__) . 'includes/user/view/');
            define('WPUSER_USER_i18n', plugin_dir_url(__FILE__) . 'i18n');
            define('WPUSER_VERSION', $this->version);
            define('WPUSER_PREFIX', $this->WPUSERprefix);
            define('WPUSER_TYPE', 'FREE'); //FREE OR PRO
            define('WPUSER_ENV', 'LIVE'); //LIVE OR DEV
            define('WPUSER_DOC_URL', 'http://wpuserplus.com/documentation');
            define('WPUSER_SUPPORT_URL', 'http://wpuserplus.com/support');
            define('WPUSER_PRO_URL', 'http://wpuserplus.com/pricing');
            define('WPUSER_COUPON', "Use Coupon code 'UPDATEPRO' and Get Flat 30% off");

        }

        function includes()
        {
            if (is_admin()) {
                foreach (glob(WPUSER_PLUGIN_DIR . 'admin/*.php') as $filename) {
                    include $filename;
                }
                include_once('includes/class-tab-action.php');
                include_once('includes/class-tab-my-profile.php');

            }
            include_once('includes/class-group-action.php');
            foreach (glob(WPUSER_PLUGIN_DIR . 'user/*.php') as $filename) {
                include $filename;
            }

            foreach (glob(WPUSER_PLUGIN_DIR . 'includes/lib/php-jwt/src/*.php') as $filename) {
                include $filename;
            }

	    if (!class_exists('WPUserLayout')) {
                include_once('includes/appearance_filter-action.php');
            }
        }

        function installation()
        {
            include('includes/installation.php');
        }

        function load_textdomain()
        {

		load_plugin_textdomain('wpuser', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

    }

endif;

function WPUserFunction()
{
    return WPUser::instance();
}

$GLOBALS['WPUser'] = WPUserFunction();
