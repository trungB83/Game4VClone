<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
if (!class_exists('WPUserAdminAssets')) :

    class WPUserAdminAssets
    {

        public function __construct()
        {
            add_action('init', array($this, 'admin_scripts'));
        }

        // Enqueue scripts
        public function admin_scripts()
        {
            if (isset($_GET['page'])) {

                if (in_array($_GET['page'], array("wp-user-setting", "wp-user-list", 'wp-user-subscription','wp-user-addons','wp_user-woocommerce','wp-socil-login'))) {

                    wp_enqueue_script('jquery');
                    $wp_user_cdn_enable = get_option('wp_user_cdn_enable');
                    if ($wp_user_cdn_enable == '1') {
                        //JS
                        wp_enqueue_script('wpdb', "https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js");
                        wp_enqueue_script('wpdbbootstrap', "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js");
                        wp_enqueue_script('wpdbapp', WPUSER_PLUGIN_URL . "assets/dist/js/app.min.js");
                        wp_enqueue_script('wpdbjquery', "https://code.jquery.com/ui/1.11.4/jquery-ui.min.js");

                        //CSS
                        wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");//Custom CSS
                        wp_enqueue_style('wpdbbootstrapcdncss', "https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css");
                        wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.min.css");//Custom CSS
                        //wp_enqueue_style('wpdbadminltecss', "https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/AdminLTE.css");
                        wp_enqueue_style('wpdbbskinscss', "https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/skins/_all-skins.min.css");
                        wp_enqueue_style('wpdbiCheckcss', "https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.0/css/skins/skin-blue.css");

                    } else {
                        //JS
                        wp_enqueue_script('wpdb', WPUSER_PLUGIN_URL . "assets/plugins/jQuery/jQuery-2.1.4.min.js");

                        if (in_array($_GET['page'], array('wp-user-setting', 'wp-user-subscription','wp-user-addons','wp_user-woocommerce','wp-socil-login'))) {
                            wp_enqueue_script('wpdbapp', WPUSER_PLUGIN_URL . "assets/dist/js/app.min.js");
                            //wp_enqueue_style('wpsp_bootstrapcolor', WPUSER_PLUGIN_URL . 'assets/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css');
                           // wp_enqueue_script('wpdbbootstrapcolor', WPUSER_PLUGIN_URL . "assets/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js");

                        }
                        wp_enqueue_script('wpdbjquery', WPUSER_PLUGIN_URL . "assets/js/jquery-ui.min.js");

                        wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");
                        if (in_array($_GET['page'], array("wp-user-list"))) {

                            //jPList lib
                            wp_enqueue_script('wpuserjplist', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.core.min.js");
                            wp_enqueue_script('wpuserjplistbootstrap', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-filter-dropdown.min.js");
                            wp_enqueue_script('wpuserapppagination', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-pagination-bundle.min.js");
                            wp_enqueue_script('wpusersortdropdown', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-sort-dropdown.min.js");
                            wp_enqueue_script('wpusersortfilter', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.textbox-filter.min.js");

                        }


                        //CSS
                        wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");
                        wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
                        wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.min.css");
                        wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
                        wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");

                    }
                    wp_enqueue_media();
                    wp_enqueue_script('wpcolorpickerjs', "https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js");
                    wp_enqueue_style('wpcolorpickercss', "https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css");


                    wp_enqueue_script('wpuserajax', WPUSER_PLUGIN_URL . "assets/js/ajax.min.js");
                   // wp_enqueue_script('wpdbbootstrapconfirmbox', WPUSER_PLUGIN_URL . "assets/js/bootbox.js");
                    wp_enqueue_script('wpdbbootstrapconfirmbox', WPUSER_PLUGIN_URL . "assets/js/jquery.sortable.min.js");
                    
                    $localize_script = array(
                        'wpuser_ajax_url' => admin_url('admin-ajax.php'),
                        'wpuser_update_setting' => wp_create_nonce('wpuser-update-setting'),
                        'wpuser_site_url' => site_url(),
                        'plugin_url' => WPUSER_PLUGIN_URL,
                        'wpuser_templateUrl' => WPUSER_TEMPLETE_URL,
                        'plugin_dir' => WPUSER_PLUGIN_DIR,
                        'wpuser_user_i18n' => WPUSER_USER_i18n,
                        'wpuser_lang' => get_option('wp_user_language')
                    );
                    wp_localize_script('wpuserajax', 'wpuser_link', $localize_script);


                }
            }
        }

    }

endif;

$obj = new WPUserAdminAssets();
