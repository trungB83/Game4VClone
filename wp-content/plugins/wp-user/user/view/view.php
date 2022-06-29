<?php
$wp_user_appearance_icon = (isset($atts['icon'])) ? $atts['icon'] : get_option('wp_user_appearance_icon');
$wp_user_appearance_skin = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] :
    (get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default');
$wp_user_register_enable = get_option('wp_user_disable_signup');
$wp_user_register_enable = (isset($atts['form_type']) && $atts['form_type'] == 'login' ) ? true : $wp_user_register_enable;
$wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';

include('option.php');
?>

<div class="tab-content">
  <!-- Image loader -->
 <div id="loader_action" style="display: none;position: fixed;top: 50%; left: 45%;z-index: 99999;">
       <img src="<?php echo WPUSER_PLUGIN_ASSETS_URL ?>/images/icon_loading.gif">
  </div>
   <!-- Image loader -->
    <?php
    include('login.php');
    include('forgot.php');
    if ( false == $wp_user_register_enable ) {
        include('register.php');
    } ?>
</div>
