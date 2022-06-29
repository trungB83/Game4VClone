<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

final class WPUserShortcode
{

    public function __construct()
    {
        add_shortcode('wp_user', array($this, 'wp_user'));
        add_shortcode('wp_user_list', array($this, 'wp_user_list'));
        add_shortcode('wp_user_member', array($this, 'wp_user_member'));
        add_shortcode('wp_user_restrict', array($this, 'wp_user_restrict'));
        add_shortcode('wp_user_form', array($this, 'wp_user_form'));
        add_shortcode('wp_user_search', array($this, 'wp_user_search'));
        add_shortcode('wp_user_counter', array($this, 'wp_user_counter'));
    }

    function wp_user_form($atts)
    {

        if (isset($atts['id']) && !empty($atts['id'])) {
            $view_fields ='';
            $wp_user_appearance_skin = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] :
                (get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default');

            $user_id = get_current_user_id();
            $wp_user_form_width = (isset($atts['width']) && !empty($atts['width']) && !is_user_logged_in()) ? $atts['width'] : '100%';
            $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
                (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');
            $atts['skin']=$wp_user_appearance_skin_color;
            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
            $form_fields = get_post_meta($atts['id'], 'fields', true);
            echo '<div class="box box-default">
                    <div class="box-header" role="tab" id="headingOne">
                        <h3 class="box-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#my_accout_'.$atts['id'].'" aria-expanded="true" aria-controls="collapseOne">
                           '.get_the_title($atts['id']).'
                            </a>
                        </h3>
                    </div>
                    <div id="my_accout_'.$atts['id'].'" class="box-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" aria-expanded="true">';
                        if(!(isset($_GET['user_id']) || (isset($atts['type']) && 'register' == $atts['type']))) {
                            echo '<div style="display: none;" id="wpuser_errordiv_form_'.$atts['id'].'"class="alert alert-dismissible" role="alert">
                                     <label id="wpuser_error_register_'.$atts['id'].'"></label>
                                    </div>
                            <form method="post" id="wpuser_form_' . $atts['id'] . '" onsubmit="return false">
                            <input name="wpuser_update_setting" type="hidden" value="' . wp_create_nonce('wpuser-update-setting') . '"/>
                            <input name="wpuser_form_id" type="hidden" value="' . $atts['id'] . '"/>
                            <input name="wpuser_form" type="hidden" value="1"/>
                        ';
                        }
                        echo '<div class="box-body">';
                        echo '<div class="row">';
                        $boolean_data_access_view = 0;
                            if ($userplus_field_order) {
                                $fields_count = count($userplus_field_order);
                                for ($i = 0; $i < $fields_count; $i++) {
                                    $key = $userplus_field_order[$i];
                                    $array = $form_fields[$key];
                                    if(!isset($_GET['user_id'])) {
                                        $view_fields .= profileController::edit_fields($key, $array, $wp_user_appearance_skin, $atts['id'], $user_id);
                                    }else{
                                        $viewFields = profileController::view_fields($key, $array, $wp_user_appearance_skin, $atts['id'], $_GET['user_id']);

                                        if( isset( $array['privacy'] ) && !empty( $array['privacy'] )){
                                            if ( '3' ==  $array['privacy'] && !is_user_logged_in() ) {
                                                $boolean_data_access_view = 1;
                                            }
                                        }

                                        $view_fields = $view_fields . $viewFields;
                                    }
                                }
                            }
                            if(!empty($view_fields)){
                                echo $view_fields;
                            }else{
                                echo ( $boolean_data_access_view == 0 ) ? '<p>'.__('No Data Found','wpuser').'</p>' : '<p>'.__('Only Member Can View Data.','wpuser').'</p>';
                            }
                        echo '</div>';
                        echo '</div>';

                     if(!(isset($_GET['user_id']) || (isset($atts['type']) && 'register' == $atts['type']))) {
                            echo '<div class="form-footer box-footer row">
                                                    <div class="col-xs-12">
                                                        <button type="submit" class="wpuser_button wpuser_update_user_meta btn  btn-primary"
                                                               id="wpuser_register'.$atts['id'].'" name="wpuser_register">Save</button>
                                                    </div>
                                                </div>';


                            echo '</form>';
                        }
                    echo'</div>
                </div>';
            ?>
            <script>
                var $ = jQuery.noConflict();
                $(".wpuser_update_user_meta").click(function () {
                    var wpuser_form_id = $(this).closest('form').find("input[name='wpuser_form_id']").val();
                    $.ajax({
                        url: wpuser.wpuser_ajax_url + '?action=wpuser_update_profile_action',
                        data: $(this).closest('form').serialize(),
                        error: function (data) {
                        },
                        success: function (data) {
                            var parsed = $.parseJSON(data);
                            $("#wpuser_errordiv_form_" + wpuser_form_id).html('<div class="wp-user-alert alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button>' + parsed.message + '</div>');
                            if (parsed.status == 'success') {
                                $('.wpuser_profile_name').html(parsed.user_info.name);
                                $('.wpuser_profile_first_name').html(parsed.user_info.first_name);
                                $('.wpuser_profile_last_name').html(parsed.user_info.last_name);
                                $('.wpuser_profile_description').html(parsed.user_info.description);
                                $('.wpuser_profile_email').html(parsed.user_info.email);
                                $('.wpuser_profile_user_url').html(parsed.user_info.user_url);
                                $('.wpuser_profile_img').attr('src', parsed.user_info.profile_img);
                                $('.profile_background_pic').attr('src', parsed.user_info.profile_background_pic);
                                $('.wpuser_profile_strength').attr('style', 'width:' + parsed.user_info.wpuser_profile_strength + '%');
                                $('.wpuser_profile_strength').html(parsed.user_info.wpuser_profile_strength + '%');
                                $.each(parsed.user_info.advanced, function (i, val) {
                                    $('.wpuser_profile_' + i).html(val);
                                    $('.wpuser_profile_url_' + i).attr('href', val);
                                });
                            }
                            $("#wpuser_errordiv_form_" + wpuser_form_id).show();
                        },
                        type: 'POST'
                    });
                });
            </script>
            <?php

        }
    }

    function wp_user_search($atts)
    {

        wp_enqueue_script('jquery');
        //jPList lib
        wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");

        $wp_user_security_reCaptcha_enable = (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) ? true : false;
        if($wp_user_security_reCaptcha_enable)
            wp_enqueue_script('wpdbbootstraprecaptcha', "https://www.google.com/recaptcha/api.js");

        wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");
        wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
        wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
        wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
        wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");
        wp_enqueue_style('wpuserstyle', WPUSER_PLUGIN_URL . "assets/css/wpuser_style.css");

        $isUserLogged = (is_user_logged_in()) ? 1 : 0;
        $wp_user_page = get_option('wp_user_page');
        $currentpage_url =  get_permalink();
        $wpuser_view_profile_url = !empty($wp_user_page) ? add_query_arg(array('redirect'=>$currentpage_url,'url_title'=>'Members List'), get_permalink($wp_user_page)) : '#';

        $localize_script_member = array(
            'wpuser_ajax_url' => admin_url('admin-ajax.php'),
            'wpuser_update_setting' => wp_create_nonce('wpuser-update-setting'),
            'wpuser_site_url' => site_url(),
            'plugin_url' => WPUSER_PLUGIN_URL,
            'wpuser_view_profile_url' => $wpuser_view_profile_url,
            'plugin_dir' => WPUSER_PLUGIN_DIR,
            'isUserLogged' => $isUserLogged,
            'atts' => $atts,
            'template' => (isset($atts['template']) && !empty($atts['template'])) ? $atts['template'] : 'deafult',
            'wp_user_security_reCaptcha_enable' => $wp_user_security_reCaptcha_enable,
        );

        wp_enqueue_script('wpusermember', WPUSER_PLUGIN_URL . "assets/js/user_member.js");
        wp_localize_script('wpusermember', 'wpuser_member', $localize_script_member);

        wp_enqueue_script('wpdbrangeslider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.3/bootstrap-slider.min.js');
        wp_enqueue_style('wpdbrangeslidercss', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.3/css/bootstrap-slider.min.css");

        global $wp_user_appearance_button_type;
        $wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';

        do_action('wpuser_appearance_skin');

        $wp_user_appearance_skin = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] :
            (get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default');

        $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
            (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');

        $title = (isset($atts['title']) && !empty($atts['title'])) ? $atts['title'] :'Search';

        echo '<style>';
        echo get_option('wp_user_appearance_custom_css');
        echo '</style>';
        $class = (isset($atts['type'])) ? $atts['type'] : 'normal';

        $html ='<div class="bootstrap-wrapper '.$class.' hold-transition skin-' . $wp_user_appearance_skin_color . ' sidebar-mini">';
        if (isset($atts['type']) && $atts['type'] == 'popup') {
            $html .='<a class="" href="javascript:void(0)" onclick="searchList()">
                        <span id="search_title" class="box-title search_title">
                            '. __($title, 'wpuser').'
                        </span>
                    </a>';
            $html .='<div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_searchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">'.
                            __($title, 'wpuser').'
                                <span id="wpuser_mail_to_name"></span>
                                </h4>
                            </div>
                            <div class="modal-body">';

            include('view/search-form.php');

            $html .='</div>';
            $html .='</div>';
            $html .='</div>';
            $html .='</div>';

            $html .="<script>
                            function searchList() {
                                $(' #wpuser_searchModal' ).modal();
                                var modal = $(' #wpuser_searchModal'),
                                dialog = modal.find('.modal-dialog');
                            modal.css('display', 'block');
                             }
                      </script>";
        }else{
            include('view/search-form.php');
        }
        $html .='</div>';

        echo $html;





    }

    function wp_user_counter($atts)
    {

        wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");
        wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
        wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
        wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
        wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");
        wp_enqueue_style('wpuserstyle', WPUSER_PLUGIN_URL . "assets/css/wpuser_style.css");


        global $wp_user_appearance_button_type;
        $wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';

        do_action('wpuser_appearance_skin');

        $wp_user_appearance_skin = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] :
            (get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default');

        $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
            (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');

        $title = (isset($atts['title']) && !empty($atts['title'])) ? $atts['title'] : 'Members';
        $icon = (isset($atts['icon']) && !empty($atts['icon'])) ? $atts['icon'] : '';

        $wp_user_page = get_option('wp_user_page');
        $wpuser_url = !empty($wp_user_page) ? add_query_arg(array('search_user' => 'null', 'title' => $title), get_permalink($wp_user_page)) : '#';

        echo '<style>';
        echo get_option('wp_user_appearance_custom_css');
        echo '</style>';

        $role__in = (isset($atts['role_in']) && !empty($atts['role_in'])) ? explode(',', $atts['role_in']) : array();
        $role__not_in = (isset($atts['role_not_in']) && !empty($atts['role_not_in'])) ? explode(',', $atts['role_not_in']) : array();
        $include = (isset($atts['include']) && !empty($atts['include'])) ? explode(',', $atts['include']) : array();
        $exclude = (isset($atts['exclude']) && !empty($atts['exclude'])) ? explode(',', $atts['exclude']) : array();
        $meta_key = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 'wp-approve-user' : '';
        $meta_value = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 1 : '';

        $args = array(
            'role' => '',
            'role__in' => $role__in,
            'role__not_in' => $role__not_in,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
            'meta_compare' => '',
            'date_query' => array(),
            'include' => $include,
            'exclude' => $exclude,
            'offset' => '',
            'search' => '',
            'number' => '',
            'count_total' => false,
            'fields' => 'all',
        );
        $meta_query = array();

        if(isset($atts['key']) && isset($atts['key']) && !empty($atts['value']) && !empty($atts['value'])){
            $wpuser_url = add_query_arg(array('key' => $atts['key'], 'value' => $atts['value']), $wpuser_url) ;
            $no_whitespaces_ids = preg_replace( '/\s*,\s*/', ',', filter_var( $atts['key'], FILTER_SANITIZE_STRING ) );
            $ids_array = explode( ',', $no_whitespaces_ids );

            $no_whitespaces_text = preg_replace( '/\s*,\s*/', ',', filter_var( $atts['value'], FILTER_SANITIZE_STRING ) );
            $text_array = explode( ',', $no_whitespaces_text );

            // We need to make sure that our two arrays are exactly the same lenght before we continue
            if ( count( $ids_array ) == count( $text_array ) ){
                $combined_array = array_combine( $ids_array, $text_array );
                foreach ( $combined_array as $k => $v ){
                    $meta_query[] = array(
                        'key' => $k,
                        'value' => $v,
                        'compare' => 'LIKE'
                    );
                }
            }
        }

        if(!empty($meta_query)){
            $args['meta_query']= $meta_query;
        }

        $total_count = count(get_users($args));


        $html = '<div class="bootstrap-wrapper col-lg-3 col-xs-6 hold-transition skin-' . $wp_user_appearance_skin_color . ' sidebar-mini">';

        $html .= '<div class="wpuser_counter">
          <!-- small box -->
          <div class="text-center small-box bg-' . $wp_user_appearance_skin_color . '">
            <div class="inner">
              <h3>'.$total_count.'</h3>

              <p>' . $title . '</p>
            </div>
            <div class="icon">
              <i class="' . $icon . '"></i>
            </div>';
              if (!empty($wpuser_url)){
                 $html .= '<a href="'.$wpuser_url.'" class="small-box-footer">View <i class="fa fa-arrow-circle-right"></i></a>';
             }

           $html .='</div>
        </div>';
        $html .='</div>';

        echo $html;





    }

    function wp_user($atts)
    {
        if ( ( isset($_GET['search_user']) && !empty($_GET['search_user']) || isset($_GET['form_id']) && !empty($_GET['form_id']))
          && ( false == ( isset($atts['popup'] ) ) ) ) {
            if (isset($_GET['title']) && !empty($_GET['title'])){
                echo '<h3>'.$_GET['title'].'</h3>';
            }
            echo do_shortcode('[wp_user_list]');
            return;
        }

    $form_id = time() . rand(2, 999);
    $login_redirect = "";
    $wp_user_form_width = (isset($atts['width']) && !empty($atts['width']) && !is_user_logged_in() && !isset($_GET['user_id'])) ? $atts['width'] : '100%';
    $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
        (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');
    @$atts['skin']=$wp_user_appearance_skin_color;

    include_once('includes/assets.php');
   // include_once('includes/script.php');
    include_once('view/appearance.php');
      if ( false == (isset($atts['popup']) ) ) {
          do_action('wp_user_member',$atts);
        }

    ob_start();
    echo '<style>';
    echo get_option('wp_user_appearance_custom_css');
    echo '</style>';
    echo "<script>
        function delete_img(img_delete, image = 'image') {
           jQuery('#upload'+img_delete).val('');
           jQuery('#upload_img'+img_delete).val('');
           jQuery('#img_view'+img_delete).attr('src','".WPUSER_PLUGIN_URL ."assets/images/'+image+'.png');
           jQuery('#img_delete_'+img_delete).hide();
           console.log(img_delete);
        }
        </script>";
    echo '<div style="margin: auto;max-width:' . $wp_user_form_width . '" class="bootstrap-wrapper wp_user support_bs">';
    if(isset($_GET['user_id']) && !empty($_GET['user_id']) && (false == (isset($atts['popup'])))){
    global $wpdb;
      $wp_user_view_profile_layout = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] : '';
          if(!empty($wp_user_view_profile_layout)){
                include('view/layout/member-view-profile-'.strtolower($wp_user_view_profile_layout).'.php');
      }else{
         include('view/view-profile.php');
      }
    }else {
    if (isset($atts['login_redirect'])) {
        $login_redirect = $atts['login_redirect'];
    } else {
        // $login_redirect=get_permalink(get_option('wp_user_page'));
    }

    $login_class = '';
    $register_class = '';
    $forgot_class = '';
    if (isset($atts['active']) && $atts['active'] == 'register') {
        $register_class = 'active';
    } else if (isset($atts['active']) && $atts['active'] == 'forgot') {
        $forgot_class = 'active';
    } else {
        $login_class = 'active';
    }

    if (isset($atts['popup']) && $atts['popup'] == 1) {
        $form_id = $form_id . 'p';
        if (is_user_logged_in()) {
          $wp_user_page = get_option('wp_user_page');
          if ( false == empty($wp_user_page) ){
            $genre_url = get_permalink($wp_user_page) ;
              echo '<a href="' .$genre_url . '" title=""><span class="fa fa-user"></span> ';
              _e('My Profile', 'wpuser');
              echo '</a> |';
          }
            echo ' <a href="' . wp_logout_url(get_permalink()) . '" title=""><span class="fa fa-sign-out"></span> ';
            _e('Logout', 'wpuser');
            echo '</a>';
        } else {
            ?>
            <div ng-app="listpp" ng-app lang="en">
                <!-- Button trigger modal -->
                <a id="wp_login_btn<?php echo $form_id ?>">
                    <?php if (isset($atts['active']) && $atts['active'] == 'register') {
                        _e('Sign Up', 'wpuser');
                    } else {
                        _e('Sign In', 'wpuser');
                    } ?>
                </a>
                <!-- Modal -->
                <div style="margin:auto;overflow: scroll" class="modal fade wpuser_login" role="dialog"
                     id="wp_login<?php echo $form_id ?>"
                     tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                    <div
                        style="z-index:1;margin:auto; max-width:<?php echo (isset($atts['width']) && !empty($atts['width']) && !is_user_logged_in()) ? $atts['width'] : '900px'; ?>;"
                        class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div style="padding: 0px;" class="modal-body">
                                <button type="button" class="close close_model" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <?php
                                include('view/view.php');
                                include('includes/script.php');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else if (is_user_logged_in()) {
        $wp_user_profile['my_account'] = array(
            'class' => 'WPUserMyProfile',
            'function' => 'my_account',
            'tab' => 'My Account',
            'icon' => 'glyphicon glyphicon-dashboard',
            'order' => '0',
            'parent' => '0',
            'active' => 'active'
        );
        $wp_user_profile['edit_profile'] = array(
            'class' => 'WPUserMyProfile',
            'function' => 'edit_profile',
            'tab' => 'Edit Profile',
            'icon' => 'glyphicon glyphicon-edit',
            'order' => '0',
            'parent' => '0',
            'active' => ''
        );

        $wp_user_profile = apply_filters('wpuser_multiple_address_list', $wp_user_profile);

        if (get_option('wp_user_disable_group_myprofile') != 1) {
            $wp_user_profile['groups'] = array(
                'class' => 'WPUserMyProfile',
                'function' => 'groups',
                'tab' => 'Groups',
                'icon' => 'fa fa-users',
                'order' => '0',
                'parent' => '0',
                'active' => ''
            );
        }
        include_once(ABSPATH.'wp-admin/includes/plugin.php');
        if (get_option('wp_user_disable_contact_form_myprofile') != 1) {
            $wp_user_profile['contact_us'] = array(
                'class' => 'WPUserMyProfile',
                'function' => 'contact_us',
                'tab' => 'Contact Us',
                'icon' => 'glyphicon glyphicon-envelope',
                'order' => '0',
                'parent' => '0',
                'active' => ''
            );
        }

        if (get_option('wp_user_disable_wishlist_myprofile') != 1 && is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {
                    $wp_user_profile['wishlist'] = array(
                        'class' => 'WPUserMyProfile',
                        'function' => 'wishlist',
                        'tab' => 'My Wishlist',
                        'icon' => 'fa fa-heart',
                        'order' => '0',
                        'parent' => '0',
                        'active' => ''
                    );
        }

                if (get_option('wp_user_disable_support_myprofile') != 1 && is_plugin_active( 'supportcandy/supportcandy.php' ) ) {
                    $wp_user_profile['support'] = array(
                        'class' => 'WPUserMyProfile',
                        'function' => 'support',
                        'tab' => 'Support',
                        'icon' => 'fa fa-support',
                        'order' => '0',
                        'parent' => '0',
                        'active' => ''
                    );
                }

        $user_tab = get_option('wpuser_tabs');
        if (!empty($user_tab)) {
            $wpuser_tab = $wpuser_tab_data = unserialize($user_tab);
            $wpuser_tab = WPUserMyProfile::array_sort($wpuser_tab, 'tab_sort_order_index', SORT_ASC);
        }

        if (!empty($wpuser_tab)) {
            $user = wp_get_current_user();
            foreach ($wpuser_tab as $key => $value) {
                if (!empty($key) && $value['tab_visibility'] != 'hide') {
                    if (isset($value['tab_visible_role']) && !empty($value['tab_visible_role'])) {
                        $show_tab = (count(array_intersect($user->roles, ($value['tab_visible_role']))) >= 1) ? 1 : 0;
                    } else {
                        $show_tab = 1;
                    }
                    if ($show_tab) {
                        $wp_user_profile[$key] = array(
                            'class' => 'WPUserMyProfile',
                            'function' => (isset($value['is_link']) && $value['is_link'] == 'on') ? 'tab_link_function' : 'tab_content_function',
                            'tab' => $value['tab_title'],
                            'icon' => '',
                            'order' => isset($value['tab_sort_order_index']) ? $value['tab_sort_order_index'] : 100,
                            'parent' => '0',
                            'active' => '',
                            'value' => stripslashes($value['tab_content'])
                        );
                    }
                }
            }
        }


        //echo '<a href="' . wp_logout_url(get_permalink()) . '" title="">';
        include('view/profile.php');
        //echo '</a>';
    } else {
        include('view/view.php');
        include('includes/script.php');
    }
    }
    echo '</div>';
    return ob_get_clean();
    }

    function wp_user_list($atts)
    {

        wp_enqueue_script('jquery');
        //jPList lib
       wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");

        $wp_user_security_reCaptcha_enable = (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) ? true : false;
        if($wp_user_security_reCaptcha_enable)
            wp_enqueue_script('wpdbbootstraprecaptcha', "https://www.google.com/recaptcha/api.js");

        wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css?4.3");
        wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
        wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
        wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
        wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");
        wp_enqueue_style('wpuserstyle', WPUSER_PLUGIN_URL . "assets/css/wpuser_style.css");

        $isUserLogged = (is_user_logged_in()) ? 1 : 0;
        $wp_user_page = get_option('wp_user_page');
        $currentpage_url =  get_permalink();
        $wpuser_view_profile_url = !empty($wp_user_page) ? add_query_arg(array('redirect'=>$currentpage_url,'url_title'=>'Members List'), get_permalink($wp_user_page)) : '#';

        $localize_script_member = array(
            'wpuser_ajax_url' => admin_url('admin-ajax.php'),
            'wpuser_update_setting' => wp_create_nonce('wpuser-update-setting'),
            'wpuser_site_url' => site_url(),
            'plugin_url' => WPUSER_PLUGIN_URL,
            'wpuser_view_profile_url' => $wpuser_view_profile_url,
            'plugin_dir' => WPUSER_PLUGIN_DIR,
            'isUserLogged' => $isUserLogged,
            'atts' => $atts,
            'view' => (isset($atts['view']) && !empty($atts['view'])) ? $atts['view'] : 'grid',
            'template' => (isset($atts['template']) && !empty($atts['template'])) ? $atts['template'] : 'deafult',
            'wp_user_security_reCaptcha_enable' => $wp_user_security_reCaptcha_enable,
        );

        $isStyleHeader = (isset($atts['view']) && 'list' == $atts['view'] ) ? '' : 'style="display:none"';

        wp_enqueue_script('wpusermember', WPUSER_PLUGIN_URL . "assets/js/user_member.js");
        wp_localize_script('wpusermember', 'wpuser_member', $localize_script_member);

        wp_enqueue_script('wpdbrangeslider', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.3/bootstrap-slider.min.js');
        wp_enqueue_style('wpdbrangeslidercss', "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/10.2.3/css/bootstrap-slider.min.css");

        global $wp_user_appearance_button_type;
        $wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';

        do_action('wpuser_appearance_skin');

        $wp_user_appearance_skin = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] :
            (get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default');

        $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
            (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');
        $form_id = time() . rand(2, 999);

        $html ='<div class="bootstrap-wrapper hold-transition skin-' . $wp_user_appearance_skin_color . ' sidebar-mini">';
        include('view/popup-model.php');
        $html .="<script>

        function filterSidenavShow(){
            $('#filterSidenav').css('width', '100%');
             $('#filterSidenav').show();
              $('.sidenav').css('transition', '0.5s');
        };

        function filterSidenavClose(){
            $('#filterSidenav').hide();
        };

        function filterList() {
            $(' #wpuser_view_filter' ).modal();
            var modal = $(' #wpuser_view_filter'),
                dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');
        }
        function viewUserList(grid) {
        console.log(grid);
        if(grid == 1){
            $( '.wpuser-view').removeClass(\"col-md-6\").removeClass(\"col-md-4\").addClass(\"col-md-12\");
            $( '.wpuser-view-col').removeClass(\"col-md-12\").addClass(\"col-md-2\");
            $( '.wpuser_label' ).hide();
            $( '.wpuser_profile_image').removeClass(\"wpuser_viewImage\").addClass('img-circle');
            $( '#wp_user_members_header' ).show();
            $( '.wpuser_user_list').removeClass( 'grid' ).addClass( 'list');
        } else if(grid == 2){
            $( '.wpuser-view').removeClass(\"col-md-12\").removeClass(\"col-md-4\").addClass(\"col-md-6\");
            $( '.wpuser-view-col').removeClass(\"col-md-2\").addClass(\"col-md-12\");
            $( '.wpuser_label' ).show();
            $( '.wpuser_profile_image').removeClass(\"img-circle\").addClass('wpuser_viewImage');
            $( '#wp_user_members_header' ).hide();
            $( '.wpuser_user_list').removeClass( 'list' ).addClass( 'grid');
        } else if(grid == 3){
            $( '.wpuser-view').removeClass(\"col-md-12\").removeClass(\"col-md-6\").addClass(\"col-md-4\");
            $( '.wpuser-view-col').removeClass(\"col-md-2\").addClass(\"col-md-12\");
            $( '.wpuser_label' ).show();
            $( '.wpuser_profile_image').removeClass(\"wpuser_viewImage\").addClass('img-circle');
            $( '#wp_user_members_header' ).hide();
            $( '.wpuser_user_list').removeClass( 'list' ).addClass( 'grid');
        }

        if (typeof(Storage) !== \"undefined\") {
         localStorage.setItem(\"grid\", grid);
        }


}

/*jQuery(document).ready(function(){
    jQuery('.dropdown-toggle').dropdown();
});*/
        </script>";


        $html .='

 <div class="panel panel-default">
    <div class="panel-body user-heading">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <ul class="list-unstyled list-inline">
                            <li>View</li>
                            <li>
                                <i onclick="viewUserList(\'3\')" class="cursor grid_3 wpdc-pointer fa fa-th"></i>
                            </li>
                            <li>
                                <i onclick="viewUserList(\'2\')"  class="cursor grid_2 wpdc-pointer fa fa-th-large"></i>
                            </li>
                            <li>
                                <i onclick="viewUserList(\'1\')" class="cursor grid_1 wpdc-pointer fa fa-bars"></i>
                            </li>
                    </ul>
                </div>
                <div class="col-sm-6 col-md-3">

                </div>
                <div class="col-sm-6 col-md-3">
                    <a class="pull-right" href="javascript:void(0)" onclick="filterList()">
                        <span id="filter_title" class="box-title filter_title"> <i class="fa fa-filter"></i>
                            Filter
                        </span>
                    </a>
                </div>
            </div>
    </div>
 </div>


    <!--  <a onclick="getOrderUserList(\'ID\',\'ASC\' )">ID</a>
       <a onclick="getOrderUserList(\'display_name\',\'ASC\' )">Name</a>
       <a onclick="getOrderUserList(\'weight\',\'ASC\' )">Weight</a>
       <a onclick="getOrderUserList(\'registered\',\'DESC\' )">Registered</a>
        <a onclick="getOrderUserList(\'ID\',\'DESC\' )">ID</a>
         <a onclick="getOrderUserList(\'display_name\',\'DESC\' )">Name</a>
           <a onclick="getOrderUserList(\'weight \',\'DESC\' )">Weight</a>
                              -->



                  <div id="filterSidenav" style="display: none;" class="sidenav">
                    <a href="javascript:void(0)" class="filterClose closebtn pull-right" onclick="filterSidenavClose()">&times;</a>
                     <label class="filter_title"><i class="fa fa-filter"></i> Filter</label>
                     <div id="wpuserFilterCollapse" class="panel wpuser-filter">

                          </div>
                </div>';
        $html .= '<div '.$isStyleHeader.' id="wp_user_members_header"></div>
                    <div class="wpuser_user_list list" id="wpuser_user_list">
                   <!-- Image loader -->
                  <div id="loader" style="display: none;position: fixed;top: 50%; left: 45%;">
                        <img src="'.WPUSER_PLUGIN_ASSETS_URL.'/images/icon_loading.gif">
                   </div>
                    <!-- Image loader -->
                 <div class="row">
                  <div class="response_message" id="wp_user_response_message"></div>

                    <div class="members" id="wp_user_members_list">

                    </div>
                 </div>
                 <div class="row wpuser-pagination">
                     <div class="col-md-6 col-sm-12">
                        <div id="members_pages" class="pages">
                        </div>
                      </div>
                     <div class="col-md-6 col-sm-12">
                        <ul id="members_pagination" class="pagination pagination-sm">
                        </ul>
                      </div>
                 </div>
                 </div>';

        return $html;
    }

    function wp_user_member($atts)
    {

    $template = (isset($atts['template']) && !empty($atts['template'])) ?  $atts['template'] : '';
    $wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';

    wp_enqueue_script('jquery');
    //jPList lib

    wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");

    $wp_user_security_reCaptcha_enable = (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) ? true : false;
    if($wp_user_security_reCaptcha_enable)
    wp_enqueue_script('wpdbbootstraprecaptcha', "https://www.google.com/recaptcha/api.js");

    wp_enqueue_script('wpuserjplist', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.core.min.js");
    wp_enqueue_script('wpuserjplistbootstrap', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-filter-dropdown.min.js");
    wp_enqueue_script('wpuserapppagination', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-pagination-bundle.min.js");
    wp_enqueue_script('wpusersortdropdown', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-sort-dropdown.min.js");
    wp_enqueue_script('wpusersortfilter', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.textbox-filter.min.js");

    wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");
    wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
    wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
    wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
    wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");
    wp_enqueue_style('wpuserstyle', WPUSER_PLUGIN_URL . "assets/css/wpuser_style.css");

        $isUserLogged = (is_user_logged_in()) ? 1 : 0;
            $localize_script_member = array(
                'wpuser_ajax_url' => admin_url('admin-ajax.php'),
                'wpuser_update_setting' => wp_create_nonce('wpuser-update-setting'),
                'wpuser_site_url' => site_url(),
                'plugin_url' => WPUSER_PLUGIN_URL,
                'wpuser_templateUrl' => WPUSER_TEMPLETE_URL,
                'plugin_dir' => WPUSER_PLUGIN_DIR,
                'template' => $template,
                'isUserLogged' => $isUserLogged,
                'wp_user_security_reCaptcha_enable' => $wp_user_security_reCaptcha_enable,
            );

            wp_enqueue_script('wpusermember', WPUSER_PLUGIN_URL . "assets/js/user_member.min.js");
            wp_localize_script('wpusermember', 'wpuser_member', $localize_script_member);



    $role__in = (isset($atts['role_in']) && !empty($atts['role_in'])) ? explode(',', $atts['role_in']) : array();
    $role__not_in = (isset($atts['role_not_in']) && !empty($atts['role_not_in'])) ? explode(',', $atts['role_not_in']) : array();
    $include = (isset($atts['include']) && !empty($atts['include'])) ? explode(',', $atts['include']) : array();
    $exclude = (isset($atts['exclude']) && !empty($atts['exclude'])) ? explode(',', $atts['exclude']) : array();
    $meta_key = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 'wp-approve-user' : '';
    $meta_value = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 1 : '';
    $orderby = (isset($atts['orderby'])) ? $atts['orderby'] : '';
    $order = (isset($atts['order'])) ? $atts['order'] : '';

    include_once('view/appearance.php');

    ob_start();
    $args = array(
        'role' => '',
        'role__in' => $role__in,
        'role__not_in' => $role__not_in,
        'meta_key' => $meta_key,
        'meta_value' => $meta_value,
        'meta_compare' => '',
        'meta_query' => array(),
        'date_query' => array(),
        'include' => $include,
        'exclude' => $exclude,
        'offset' => '',
        'search' => '',
        'number' => '',
        'count_total' => false,
        'fields' => 'all',
    );

    $blogusers = get_users($args);
    $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
        (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');

    echo '<div class="bootstrap-wrapper hold-transition skin-' . $wp_user_appearance_skin_color . ' sidebar-mini">';
    do_action('wp_user_member',$args);
    ?><!-- Modal -->
    <div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        <?php _e('Send Message to', 'wpuser'); ?>
                        <span id="wpuser_mail_to_name"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="google_form">
                        <div style="display: none;" id="wpuser_errordiv_send_mail"
                             class="alert alert-dismissible" role="alert"><label
                                id="wpuser_errordiv_send_mail"></label></div>
                        <input name="wpuser_update_setting" type="hidden"
                               value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>
                        <input type="hidden" class="form-control" name="id" class="wpuser_mail_to_userid" value=""
                               id="wpuser_mail_to_userid">
                        <div class="form-group">
                            <label><?php _e('From', 'wpuser'); ?></label>
                            <input type="text" class="form-control" name="from"
                                   placeholder="<?php _e('Email', 'wpuser'); ?>">
                        </div>
                        <div class="form-group">
                            <label><?php _e('Subject', 'wpuser'); ?></label>
                            <input type="text" class="form-control" name="subject"
                                   placeholder="<?php _e('Subject', 'wpuser'); ?>">
                        </div>
                        <div class="form-group">
                            <label><?php _e('Message', 'wpuser'); ?></label>
                            <textarea class="form-control" rows="3"
                                      name="message" placeholder="<?php _e('Message', 'wpuser'); ?>"></textarea>
                        </div>
                        <?php if (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) { ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div id="recaptcha" class="g-recaptcha"
                                         data-sitekey="<?php echo get_option('wp_user_security_reCaptcha_secretkey') ?>"></div>
                                    <input type="hidden" title="Please verify this" class="required" name="keycode"
                                           id="keycode">
                                </div>
                            </div>
                        <?php } ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn <?php echo $wp_user_appearance_button_type ?> btn-default"
                            data-dismiss="modal">
                        <?php _e('Close', 'wpuser'); ?>
                    </button>
                    <button type="button" id="wpuser_send_mail"
                            class="wpuser_button btn <?php echo $wp_user_appearance_button_type ?> btn-primary wpuser-custom-button">
                        <?php _e('Send', 'wpuser'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo '<div class="wpuser_member_profile" id="wpuser_member_profile">';
    echo '<div class="row">
              <div class="col-md-12">
            <button type="button" id="member_list_button" class="pull-right btn btn-default btn-flat">
            <i class="fa fa-fw fa-users"></i>';
    _e('Member List', 'wpuser');
    echo '</button>
                </div>
                </div>';
    ?>
    <div class="box box-primary wpuser-custom-box col-md-12">
        <div class="box-body box-profile" style="padding:0px">
            <div id="wpuser_member_header" class="wpuser-member-header">
                <img id="wpuser_profile_image" class="profile-user-img img-responsive img-circle" src=""
                     alt="User profile picture">

                <h3 class="profile-username text-center wpuser_profile_name" id="wpuser_profile_name"></h3>

                <p class="text-muted text-center" id="wpuser_profile_title"></p>
                <h3 class="text-center wpuser_profile_badge" id="wpuser_profile_badge">
                </h3>
                <input type="hidden" class="wpuser_mail_to_userid" value="" id="wpuser_profile_id" name="user_id">


                <center>
                    <div class="input-group">
                      <?php if (get_option('wp_user_disable_send_mail_view_profile') != 1) { ?>
                        <button type="button"
                                class="wpuser_button btn <?php echo $wp_user_appearance_button_type ?> btn-default pull-left wpuser_sendmail"
                                id="sendmail">
                    <span>
                      <i class="fa fa-envelope"></i> <?php _e('Send Mail', 'wpuser') ?>
                    </span>

                        </button> &nbsp;&nbsp;
                      <?php } ?>
                        <?php do_action('wpuser_member_profile_view', $atts); ?>
                    </div>

                </center>

            </div>
            <br>
            <?php
            if (get_option('wp_user_disable_member_profile_progress') != 1) {
                do_action('wpuser_member_profile_progress', $atts);
            }
            ?>
            <!-- <div class="progress">
                 <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="75"
                      aria-valuemin="0" aria-valuemax="100" style="width:75%">
                     75% Complete
                 </div>
             </div> -->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="wpuser_user_header">
                    </div>

                </div>
            </nav>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="wpuser_profile_name panel-title"></h3>
                </div>
                <div class="panel-body">
                    <div class="row">

                        <div class=" col-md-12 col-lg-12 ">
                            <table class="table table-user-information">
                                <tbody class="wpuser_user_info">

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.box-body -->
    </div>
   </div>
    <div class="wpuser_member_list" id="wpuser_member_list">
    <div class="row">
        <div class="col-md-12">
            <!-- main content -->
            <form action="" name="wpuser_bulk_action_form"
                  id="wpuser_bulk_action_form"
                  method="post">
                <input name="wpuser_update_setting" type="hidden"
                       value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>

                <div class="page" id="demo">
                    <!-- jplist top panel -->
                    <div class="jplist-panel">
                        <div class="center-block1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="default form-group has-feedback">
                                        <input
                                            class="form-control"
                                            data-path="*"
                                            type="text"
                                            value=""
                                            placeholder="<?php _e('Search', 'wpuser') ?>"
                                            data-control-type="textbox"
                                            data-control-name="title-filter"
                                            data-control-action="filter"
                                        />
                                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row list">
                        <?php
                        $count = 0;
                        $wp_user_page = get_option('wp_user_page');
                        $currentpage_url =  add_query_arg('page_no', 1 ,get_permalink());

                        echo '<div class="row">';
                        $chunk = (isset($atts['size']) && $atts['size'] == 'small') ? 3 : 2;
                          foreach (array_chunk($blogusers, $chunk) as $chunk_list) {
                              echo '<div class="col-md-12">';
                              foreach ($chunk_list as $value) {
                                  $info['atts'] = $atts;
                                  $info['value'] = $value;
                                  $genre_url = !empty($wp_user_page) ? add_query_arg(array('user_id'=>$value->ID,'redirect'=>$currentpage_url,'url_title'=>'Members List'), get_permalink($wp_user_page)) : '#';
                                  $title = (get_user_meta($value->ID, 'user_title', true));
                                  $user_status = (get_user_meta($value->ID, 'wp-approve-user', true));
                                  // retrieve the thumbnail size of our image
                                  $attachment_url = esc_url(get_the_author_meta('user_meta_image', $value->ID));
                                  $attachment_id = profileController::get_attachment_image_by_url($attachment_url);
                                  // retrieve the thumbnail size of our image
                                  $image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                                  // return the image thumbnail
                                  if (!empty($image_thumb[0])) {
                                      $wp_user_profile_img = $image_thumb[0];
                                  } else if (!empty($attachment_url)) {
                                      $wp_user_profile_img = $attachment_url;
                                  } else {
                                      $args = get_avatar_data($value->ID);
                                      if (!empty($args['url']))
                                          $wp_user_profile_img = $args['url'];
                                      else
                                          $wp_user_profile_img = WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
                                  }
                                  $name = get_the_author_meta('first_name', $value->ID) . " " . get_the_author_meta('last_name', $value->ID);
                                  $user_mobile = get_the_author_meta('user_mobile', $value->ID);
                                  $authors_posts = get_posts(array('author' => $value->ID, 'post_status' => 'publish'));
                                  $user_blog_url = (count($authors_posts)) ? get_author_posts_url($value->ID) : '';
                                  if (empty(str_replace(' ', '', $name))) {
                                      $user_info = get_userdata($value->ID);
                                      $name = $user_info->display_name;
                                      if (empty($name)) {
                                          $name = $user_info->user_nicename;
                                      }
                                      if (empty($name)) {
                                          $name = $user_info->user_login;
                                      }
                                  }
                                  $value->user_name = $name;
                                  $grid_class = (isset($atts['size']) && $atts['size'] == 'small') ? 4 : 6;
                                  $class = ($count & 1) ? 'list-odd' : 'list-even';
                                  echo '<div class="col-md-' . $grid_class . ' list-item ' . $class . '" id="user_' . $value->ID . '">';

                                  if (isset($atts['size']) && $atts['size'] == 'small') {
                                      echo '
                                                <div class="box box-primary wpuser-custom-box">
                                                    <div class="box-body box-profile" style="padding:0px !important">

                                                        <div style="margin: 10px;"  class="media-left pos-rel col-md-3">
                                                            <a> <img class="img-circle img-xs" src="' . $wp_user_profile_img . '" width="40px" alt="Profile Picture"></a>
                                                            <i class="badge badge-success badge-stat badge-icon pull-left"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="pull-left"><h5 class="member_list_display_name mar-no"><a href="'.$genre_url.'" >' . $name . '</a></h5>
                                                                <small class="text-muted">' . $title . '</small>
                                                            </div>
                                                            <div class="pull-right" style="margin-top: 10px; margin-right: 10px;">';

                                      do_action('wp_user_hook_member_list_button', $info);
                                      echo '</div>
                                                        </div>

                                                    </div>
                                                </div>';
                                  } else {

                                      echo '<div class="box box-primary wpuser-custom-box">
                        <div class="box-body box-profile">
                            <div class="media-left pos-rel col-md-3">
                                <a> <img class="img-circle img-xs" src="' . $wp_user_profile_img . '" width="70px" alt="Profile Picture"></a>
                                <i class="badge badge-success badge-stat badge-icon pull-left"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="member_list_display_name mar-no"><a href="'.$genre_url.'" >' . $name . '</a></h3>
                                <small class="text-muted">' . $title . '</small>
                                <br>
                                <h3>';
                                      if ($user_status == 0) {
                                         // echo '<a data-toggle="tooltip"  data-original-title="Deny"  title="Deny"><i class="fa fa-minus-circle"></i></a>&nbsp;&nbsp;';
                                      } else if ($user_status == 1) {
                                          echo '<a data-toggle="tooltip"  data-original-title="Approved" title="Approved"><i class="fa fa-check-circle"></i></a>&nbsp;&nbsp;';
                                      } else if ($user_status == 2) {
                                          echo '<a data-toggle="tooltip"  data-original-title="Pending" title="Pending"><i class="fa exclamation-circle"></i></a>&nbsp;&nbsp;';
                                      }
                                      if (!empty($user_mobile)) {
                                          echo '<a href="tel:' . $user_mobile . '" data-toggle="tooltip"  data-original-title="' . $user_mobile . '" title="' . $user_mobile . '"><i class="fa fa-phone"></i></a>&nbsp;&nbsp;';
                                      }
                                      echo '<a data-toggle="tooltip"  data-original-title="Send Mail" onclick="sendMail(\'' . $value->ID . '\',\'' . $name . '\')" ><i class="fa fa-envelope"></i></a>&nbsp;&nbsp;
                                    ';
                                      if (!empty($user_blog_url)) {
                                          echo '<a href="' . $user_blog_url . '" target="_blank" data-toggle="tooltip"  data-original-title="Blogs" title="Blogs"><i class="fa fa-th-large"></i></a>&nbsp;&nbsp;';
                                      }
                                      do_action('wp_user_hook_member_list_icon', $info);
                                      echo '<hr>
                                </h3>
                                <a type="button" class="btn ' . $wp_user_appearance_button_type . ' btn-default col-md-5" href="'.$genre_url.'" >View Profile</a>
                                <span class="col-md-1"></span>';
                                      do_action('wp_user_hook_member_list_button', $info);
                                      echo '</div>
                        </div>
                    </div>';
                                  }
                                  echo '</div>';
                                  $count++;
                              }
                              echo '</div>';
                          }

                        $perpage = (isset($atts['size']) && $atts['size'] == 'small') ? 12 : 10;
                        ?>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="row">
                    <div class="jplist-panel col-md-12">
                        <!-- items per page dropdown -->
                        <div
                            class="pagination dropdown jplist-items-per-page"
                            data-control-type="boot-items-per-page-dropdown"
                            data-control-name="paging"
                            data-control-action="paging">

                            <ul class="dropdown-menu"
                                role="menu"
                                aria-labelledby="dropdown-menu-1">

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#"
                                       data-number="<?php echo $perpage ?>"
                                       data-default="true"><?php _e($perpage . ' per page', 'wpuser'); ?>
                                    </a>
                                </li>

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#" data-number="20"
                                    ><?php _e('20 per page', 'wpuser'); ?>
                                    </a>
                                </li>

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#"
                                       data-number="50"><?php _e('50 per page', 'wpuser'); ?>
                                    </a>
                                </li>

                                <li role="presentation"
                                    class="divider"></li>

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#"
                                       data-number="all"><?php _e('ViewAll', 'wpuser'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- pagination info label -->
                        <div
                            class="pagination jplist-pagination-info"
                            data-type="<strong>Page {current} of {pages}</strong><br/> <small>{start} - {end} of {all}</small>"
                            data-control-type="pagination-info"
                            data-control-name="paging"
                            data-control-action="paging">

                        </div>

                        <!-- bootstrap pagination control -->
                        <ul
                            class="pagination pull-right jplist-pagination"
                            data-control-type="boot-pagination"
                            data-control-name="paging"
                            data-control-action="paging"
                            data-range="3"
                            data-mode="google-like">
                        </ul>

                    </div>
                </div>
        </div>
        </form>
    </div>
    <?php
    echo '</div>';
    echo '</div>';
    echo '<div class="clear"></div>';
    return ob_get_clean();
}

    function wp_user_restrict($atts, $content = null)
    {

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (isset($atts['role']) && !empty($atts['role']) && count(array_intersect($user->roles, explode(",", strtolower($atts['role'])))) >= 1) {
                return do_shortcode($content);
            }

            if (isset($atts['role']) && $atts['role'] == 'logged_in') {
                return do_shortcode($content);
            }

            return __("You do not have permission to access this content", 'wpuser');

        } else {
            $message = (isset($atts['message']) && !empty($atts['message'])) ? $atts['message'] : __('We’re sorry. You do not have permission to access this content. Please sign In to be granted access.', 'wpuser');
            return $message . " " . do_shortcode("[wp_user popup='1' width='700px']");
        }

    }
}

$GLOBALS['WPUserShortcode'] = new WPUserShortcode();
