<?php
$user_id = ((isset($_GET['user_id'])) ? $_GET['user_id'] : '');
$data=wpuserAjax::getUserDetails($user_id);
$atts['user_id']=$user_id;
$wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';


$wp_view_user_profile['wpuser_about'] = array(
    'class' => 'WPUserViewProfile',
    'function' => 'profile_information',
    'tab' => 'About',
    'icon' => 'fa fa-dashboard',
    'order' => '0',
    'parent' => '0',
    'active' => 'active'
);
if (get_option('wp_user_disable_posts') != 1) {
  $wp_view_user_profile['wpuser_post'] = array(
      'class' => 'WPUserViewProfile',
      'function' => 'posts',
      'tab' => 'Posts',
      'icon' => 'fa fa-th-large',
      'order' => '4',
      'parent' => '0',
      'active' => ''
  );
}
if (get_option('wp_user_disable_group_myprofile') != 1) {
  $wp_view_user_profile['wpuser_group'] = array(
      'class' => 'WPUserViewProfile',
      'function' => 'groups',
      'tab' => 'Groups',
      'icon' => 'fa fa-users',
      'order' => '5',
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

      if (isset($value['tab_visible_role_view']) && !empty($value['tab_visible_role_view'])) {
        @$show_tab = ((count(array_intersect($user->roles, ($value['tab_visible_role']))) >= 1)) ? 1 : 0;
      } else {
        $show_tab = 1;
      }

      if ((isset($value['tab_visible_role_view_level']) && !empty($value['tab_visible_role_view_level']))) {
        if (in_array('login', $value['tab_visible_role_view_level'])) {
          $show_tab = is_user_logged_in() ? 1 : 0;
        }

        $optionsAccessLevel = array(
            'level_1', 'level_2',
        );

        foreach ($optionsAccessLevel as $accessLevel){
          $level = get_option($user->ID, $accessLevel);
          if (in_array($level, $value['tab_visible_role_view_level'])) {
           // $show_tab = 1;
          }
        }
      }
      /* $optionsAccessLevel = array(
           'level_1', 'level_2',
       );
       foreach ($optionsAccessLevel as $accessLevel){
           $level_1 = get_option($user->ID, $accessLevel);
         if (in_array($level_1, $value['tab_visible_role_view_level'])) {
           $show_tab = 1;
           break;
         }
       }*/




      if ($show_tab) {
        $wp_view_user_profile[$key] = array(
            'class' => 'WPUserMyProfile',
            'function' => (isset($value['is_link']) && $value['is_link'] == 'on') ? 'tab_link_function' : 'tab_content_function',
            'tab' => $value['tab_title'],
            'icon' => (isset($value['tab_icon'])) ? $value['tab_icon'] : '',
            'order' => isset($value['tab_sort_order_index']) ? $value['tab_sort_order_index'] : 100,
            'parent' => '0',
            'active' => '',
            'value' => stripslashes($value['tab_content'])
        );
      }
    }
  }
}

if (class_exists('WPReviewsFunction')) {
  $wp_reviews_user_review_enable = get_option('wp_reviews_user_review_enable');
  if ($wp_reviews_user_review_enable==1) {
    $wp_view_user_profile['wp_review'] = array(
        'class' => 'WPReviewsFunction',
        'function' => 'wp_review_function',
        'tab' => 'reviews',
        'icon' => '',
        'order' => '5',
        'parent' => '0',
        'active' => ''
    );
  }
}
?>


<!-- Modal -->
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
          <input type="hidden" class="form-control" name="id" class="wpuser_mail_to_userid" value="<?php echo $user_id?>"
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
<!--END Model -->

<!-- Image Modal -->
<div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_view_image" tabindex="-1" role="dialog"
     aria-labelledby="viewModalLabel">
  <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="text-red close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalImage">
          <span id="wpuser_image_name"><?php _e('Profile', 'wpuser'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <img style="width:100%" id='wpuser_image_url' class="profile-user-img img-responsive" src="">
      </div>
    </div>
  </div>
</div>
<!--END Model -->

<input type="hidden" name="wpuser_filter_by_user" id="wpuser_filter_by_user" value="<?php echo $user_id?>">
<div id="group_view" class="group_view" style="display:none;">

</div>


<div class="row margin-top-15" id="profile_view">
  <?php if(isset($_GET['redirect']) && !empty($_GET['redirect'])){
    $url_title=(isset($_GET['url_title']) && !empty($_GET['url_title'])) ? $_GET['url_title'] : 'Back';
    ?>

      <div class="col-md-12 col-sm-12">
        <a type="button" id="member_list_button" href="<?php echo $_GET['redirect']?>?search_user=null" title="<?php _e($url_title,'wpuser')?>" class="pull-right btn btn-default btn-flat">
          <i class="fa fa-fw fa-arrow-left"></i><?php _e($url_title,'wpuser')?></a>
      </div>
  <?php }
  $user_id = get_query_var('user_id');
  $arrViewBy['user_id'] = get_query_var('user_id');
  $arrViewBy['view_by'] = get_current_user_id();
  if( $arrViewBy['user_id'] !=  $arrViewBy['view_by'] ) {
      $mixViewCount = wpuserAjax::checkAccess($arrViewBy['user_id'],$arrViewBy,'view-profile');
      if( false == $mixViewCount ){
          echo '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Access Denie</h4>
                You have reach max view profile count. please contact support for more details.
              </div>';
          return;
      }

      if (get_user_meta($user_id, 'wp-approve-user', true) == 5 && false == current_user_can( 'manage_options' ) ) {
        echo '<div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h4><i class="icon fa fa-ban"></i> Invalid User</h4>
              This user is waiting for approve or denied. please contact support for more details.
            </div>';
        return;
      }
      $wpdb->insert($wpdb->prefix . 'wpuser_views', $arrViewBy);

  }
  ?>
    <div class="col-md-12 col-sm-12 profile_progressbar" style="height: 12px;">
        <?php  do_action('wpuser_action_profile_progressbar', $atts); ?>
    </div>
    <div class="col-md-12 margin-top-15 wpuser-profile-header">
        <div class="col-md-2 wpuser-center">
            <img id="wpuser_profile_image" class="wpuser_viewimage profile-user-img" src="<?php echo $data['wp_user_profile_img']?>"
                 alt="Profile Picture">

        </div>
        <div class="col-md-10 col-sm-12 margin-top-15">
            <div class="col-md-7">
                <?php
                $profile_prefix ='';
                $boolIshideUserName = false ;
                $profile_prefix = apply_filters('wpuser_filter_profile_prefix', $profile_prefix);
                $boolIshideUserName = apply_filters('wpuser_filter_hide_user_name', $boolIshideUserName);
                $profile_prefix_user = apply_filters('wpuser_filter_profile_user_id_prefix', $profile_prefix, $user_id);
                $name =  $profile_prefix_user.(( false == $boolIshideUserName ) ? ucwords($data['name']) : $user_id);
                ?>
            <label id="wpuser_profile_name"> <?php _e($name,'wpuser')?></label>
            <br><small><?php  do_action('wpuser_action_view_profile_sidebar_header',$user_id); ?></small>
                <?php do_action('wp_user_hook_view_member_list_icon', $user_id,__($data['name'],'wpuser')); ?>
                <p class="margin-bottom">
                <?php do_action('wpuser_action_view_profile_sidebar_header_info',$user_id); ?>
                </p>

            </div>
            <div class="col-md-5 col-sm-12">
                <div class="margin-top-15">
            <?php
            do_action('wp_user_profile_header', $user_id);
            ?>

                <div class="wpuser-custom-box" style="border-top:none">

                    <?php do_action('wpuser_member_profile_view', $atts); ?>

                </div>
            </div>
            </div>

        </div>

    </div>

    <div class="col-md-offset-2 col-md-10 col-sm-12 margin-top-10">
        <div class="col-md-7">
            <?php  do_action('wp_user_hook_view_member_list_icon_count', $user_id,__($data['name'],'wpuser')); ?>
        </div>

        <div class="col-md-5 col-sm-12">
            <div class="pull-right">
                <?php do_action('wp_user_hook_member_list_button_view_profile', $atts);?>
            </div>
        </div>

    </div>
    <div class="col-md-12 col-sm-12">
        <hr>
        <?php       $wp_user_tab_position_is_vertical = get_option('wp_user_tab_position_is_vertical'); ?>
  <div class="col-md-<?php echo (isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) ? '2' : '12'; ?> col-sm-12 wpuser-center">

      <?php
     // do_action('wpuser_action_view_profile_sidebar',$user_id);
      if (isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) {
          ?>

          <!-- About Me Box -->
          <div class="box box-default wpuser-custom-box">
              <ul class="row nav user_menu text-left">
                  <?php foreach ($wp_view_user_profile as $tab => $user_profile) {
                    if( false == empty($user_profile['tab'])){
                        $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
                        $icon = (isset($user_profile['icon']) && !empty($user_profile['icon'])) ? $user_profile['icon'] : 'fa fa-navicon' ;
                        $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
                        $href=($user_profile['function']=='tab_link_function') ? $user_profile['value'] :'#'. $tab ;
                        $data_toggale=($user_profile['function']=='tab_link_function') ? '' : 'data-toggle="tab"';
                        echo ' <li class="menu_item col-md-12 col-sm-4 ' .$active . '" id="tab_'.$tab.'"> <a id="tab_link_'.$tab.'" class="'. $tab.'" href="'.$href .'" '.$data_toggale.'><spam class="user_menu_icon '.$icon.'"></spam> <spam class="menu_list">' . $user_profile['tab'] . '</spam></a></li>';
                    }
                  } ?>
              </ul>
              <!-- /.box-body -->
          </div>
          <!-- /.box -->
      <?php } ?>


  </div>
  <div class="col-md-<?php echo (isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) ? '10' : '12'; ?>">
    <div class="nav-tabs-custom wpuser-custom-nav" id="viewProfileSection">
      <?php if (!(isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) ){ ?>
        <ul class="nav nav-tabs">
          <?php foreach ($wp_view_user_profile as $tab => $user_profile) {
            if( false == empty($user_profile['tab'])){
              $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
              $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
              $href=($user_profile['function']=='tab_link_function') ? $user_profile['value'] :'#'. $tab ;
              $data_toggale=($user_profile['function']=='tab_link_function') ? '' : 'data-toggle="tab"';
              echo ' <li class="' . $active . '" id="tab_'.$tab.'"><a id="tab_link_'.$tab.'" class="'.$tab.'" href="' . $href . '" '.$data_toggale.'>' . $user_profile['tab'] . '</a></li>';
            }
          } ?>
        </ul>
      <?php } ?>
      <div class="tab-content" style="margin-top: -10px;">
        <?php
        foreach ($wp_view_user_profile as $tab => $user_profile) {
          $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
          $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
          echo '<div class="tab-pane ' . $active . '" id="' . $tab . '">                                    ';
          $WPclass = $user_profile['class'];
          $WPfunction = $user_profile['function'];
          @$atts['wpuser_update_setting_nonce']= wp_create_nonce('wpuser-update-setting');
          @$atts['data']=$data;
          @$atts['user_id']=$user_id;
          if($WPfunction=='tab_content_function'){
            $atts=$user_profile['value'];
          }
          $WPclass::$WPfunction($atts);
          $action_filter='wp_user_view_profile_'.$tab;
          do_action($action_filter,$user_id);
          echo '</div>';
        } ?>
        <!-- /.tab-pane -->
      </div>
      <!-- /.tab-content -->
    </div>
  </div>
</div>
</div>
