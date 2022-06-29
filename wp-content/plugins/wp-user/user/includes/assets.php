<?php
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-datepicker');
wp_register_style('jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
wp_enqueue_style('jquery-ui');

wp_register_style('wpusertimestyle' , WPUSER_PLUGIN_URL . 'assets/css/jquery.timepicker.css');
wp_enqueue_style('wpusertimestyle');
wp_enqueue_style('jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');

wp_enqueue_script('jquery-time-picker' , WPUSER_PLUGIN_URL . 'assets/js/jquery.timepicker.js',  array('jquery' ));

$isUserLogged = (is_user_logged_in()) ? 1 : 0;
$wp_user_security_reCaptcha_enable = (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) ? true : false;

//if($isUserLogged) {
    $localize_script_data = array(
        'wpuser_ajax_url' => admin_url('admin-ajax.php'),
        'wpuser_update_setting' => wp_create_nonce('wpuser-update-setting'),
        'wpuser_site_url' => site_url(),
        'plugin_url' => WPUSER_PLUGIN_URL,
        'wpuser_templateUrl' => WPUSER_TEMPLETE_URL,
        'plugin_dir' => WPUSER_PLUGIN_DIR,
        'isUserLogged' => $isUserLogged,
        'form_id' => $form_id,
        'login_redirect' => isset($atts['login_redirect']) ? $atts['login_redirect'] : '',
        'register_redirect' => isset($atts['register_redirect']) ? $atts['register_redirect'] : '',
        'wp_user_security_reCaptcha_enable' => $wp_user_security_reCaptcha_enable,
    );

    wp_enqueue_script('wpuserprofileajax', WPUSER_PLUGIN_URL . "assets/js/user_profile.js");
    wp_localize_script('wpuserprofileajax', 'wpuser', $localize_script_data);

  //  wp_enqueue_script('wpusermember', WPUSER_PLUGIN_URL . "assets/js/user_member.min.js");
   // wp_localize_script('wpusermember', 'wpuser_member', $localize_script_data);
//}

wp_enqueue_style('wpsp_bootstrap', WPUSER_PLUGIN_URL . 'assets/css/bootstrap.min.css');
wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.min.css");
wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/red.css");
wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");

if($wp_user_security_reCaptcha_enable)
    wp_enqueue_script('wpdbbootstraprecaptcha', "https://www.google.com/recaptcha/api.js");

wp_enqueue_style('wpuser_style', WPUSER_PLUGIN_URL . 'assets/css/wpuser_style.min.css');

wp_deregister_style('wpce_bootstrap');
wp_enqueue_media();


wp_deregister_style('wpce_bootstrap');

$wp_user_appearance_skin = get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default';

wp_enqueue_script('wpdbapp', WPUSER_PLUGIN_URL . "assets/dist/js/app.js");
wp_enqueue_script('wpdbbootstrapconfirmbox', WPUSER_PLUGIN_URL . "assets/js/bootbox.min.js");
wp_enqueue_script('wpdbvalidate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js');
wp_enqueue_script('wpdbvalidateadditional', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js');

wp_enqueue_script('wpdbrangeslider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.3/bootstrap-slider.min.js');
wp_enqueue_style('wpdbrangeslidercss', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.3/css/bootstrap-slider.min.css");
