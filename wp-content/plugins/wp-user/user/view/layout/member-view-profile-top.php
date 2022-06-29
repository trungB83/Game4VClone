<?php
$user_id = ((isset($_GET['user_id'])) ? $_GET['user_id'] : '');
$data=wpuserAjax::getUserDetails($user_id);
$atts['user_id']= $info['user_id']=$user_id;

$wp_view_user_profile['wpuser_post'] = array(
    'class' => 'WPUserViewProfile',
    'function' => 'posts',
    'tab' => 'Posts',
    'icon' => 'fa fa-th-large',
    'order' => '4',
    'parent' => '0',
    'active' => 'active'
);

$wp_user_disable_group_myprofile = get_option('wp_user_disable_group_myprofile');
  if ($wp_user_disable_group_myprofile!=1) {
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
$header_block_info = array();
$user_info_box=array();

$header_block_info = apply_filters('wp_user_member_filter_header_block', $header_block_info, $user_id);
$user_info_box = apply_filters('wp_user_member_info', $user_info_box, $user_id);
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

<input type="hidden" name="wpuser_filter_by_user" id="wpuser_filter_by_user" value="<?php echo $user_id?>">
<div id="group_view" class="group_view" style="display:none;">

</div>


<div class="row" id="profile_view">
  <?php if(isset($_GET['redirect']) && !empty($_GET['redirect'])){
    $url_title=(isset($_GET['url_title']) && !empty($_GET['url_title'])) ? $_GET['url_title'] : 'Back';
    ?>
      <div class="col-md-12">
        <a type="button" id="member_list_button" href="<?php echo $_GET['redirect']?>" title="<?php _e($url_title,'wpuser')?>" class="pull-right btn btn-default btn-flat">
          <i class="fa fa-fw fa-arrow-left"></i><?php _e($url_title,'wpuser')?></a>
      </div>
  <?php }?>
  <div class="col-md-12 wpuser-center margin-top-15">
          <div class="box box-primary">
            <div class="box-body box-profile">
        <div  class="wpuser-post col-sm-3 col-md-3 col-xl-2 mb-2">
        <div class="wpuser-custom-box" style="border-top:none">
          <img id="wpuser_profile_image" class="profile-user-img img-circle" src="<?php echo $data['wp_user_profile_img']?>" alt="User profile picture">

          <input type="hidden" class="wpuser_mail_to_userid" value="" id="wpuser_profile_id" name="user_id">

<br>
               <center>
                   <div class="input-group margin-top-15">
                     <?php if (get_option('wp_user_disable_send_mail_view_profile') != 1) { ?>
                       <button type="button"
                               class="wpuser_button btn <?php echo $wp_user_appearance_button_type ?> btn-default pull-left wpuser_sendmail"
                               id="sendmail">
                           <span>
                             <i class="fa fa-envelope"></i> <?php _e('Send Mail', 'wpuser') ?>
                           </span>

                       </button> &nbsp;&nbsp;
                     <?php } ?>
                       <?php do_action('wp_user_hook_member_list_button_view_profile', $info); ?>
                       <?php do_action('wpuser_member_profile_view', $atts); ?>
                   </div>


               </center>

          <?php
          echo '<div style="margin-top:10px">';
          if (get_option('wp_user_disable_member_profile_progress') != 1) {
            do_action('wpuser_action_profile_progressbar', $atts);
          }
            do_action('wpuser_action_view_profile_sidebar_header',$user_id);
          echo '</div>';
          ?>

      </div>
      </div>
        <div  class="wpuser-post col-sm-9 col-md-5 col-xl-6 mb-6 text-left">
          <h3 class="profile-username" id="wpuser_profile_name"> <?php _e($data['name'],'wpuser')?></h3>
          <p class="profile-label" id="wpuser_profile_label"> <?php _e($data['label'],'wpuser')?></p>

                <p>
                        <?php foreach ($header_block_info as $header_block) {
                            $link_attr=(($header_block['url'])=='#') ? 'onclick="getFollowerList(\'' . $user_id . '\',\'\',\''. $header_block['type'] .'\',\'1\')"' : ' ';
                            $link_attr .=(($header_block['url'])=='#') ? ' ' : " href='" . $header_block['url'] ."' target='_blank' ";
                            echo '<span class="wpuser_follow margin-right-15"><i class="' . $header_block['icon'] . '"></i>&nbsp;&nbsp;' . $header_block['name'] . ' : <a '.$link_attr.' class="wpuser_' . strtolower($header_block['name']) . '_count">' . $header_block['count'] . '</a></span>';

                        } ?>
                </p>

          <?php if(!empty($data['user_info'])) { ?>
           <p class="profile-label text-left" id="wpuser_profile_label"> <a href="<?php echo $data['user_info']['Website']?>" target="_blank"><?php _e($data['user_info']['Website'],'wpuser')?></a></p>
        <?php } ?>
        </div>

        <div  class="wpuser-post col-sm-12 col-md-4 col-xl-4 mb-4">
          <p>
            <?php do_action('wp_user_badge_list_icon_profile', $user_id); ?>
            <?php do_action('wp_user_hook_view_member_list_icon', $user_id,__($data['name'],'wpuser')); ?>
          </p>
        </div>
      </div>
  </div>
    <div class="wpuser-custom-box" style="border-top:none">

      <?php do_action('wpuser_member_profile_view', $atts); ?>


      <?php
      do_action('wpuser_action_view_profile_sidebar',$user_id);
    $wp_user_tab_position_is_vertical = get_option('wp_user_tab_position_is_vertical');
    if (isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) {
      ?>

      <!-- About Me Box -->
      <div class="box box-default wpuser-custom-box">
        <ul class="nav">
          <?php foreach ($wp_view_user_profile as $tab => $user_profile) {
            if( false == empty($user_profile['tab'])){
              $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
              $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
              $href=($user_profile['function']=='tab_link_function') ? $user_profile['value'] :'#'. $tab ;
              $data_toggale=($user_profile['function']=='tab_link_function') ? '' : 'data-toggle="tab"';
              echo ' <li class="' .$active . '" id="tab_'.$tab.'">><a id="tab_link_'.$tab.'" class="'. $tab.'" href="'.$href .'" '.$data_toggale.'>' . $user_profile['tab'] . '</a></li>';
            }
          } ?>
        </ul>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    <?php } ?>
  </div>
  </div>
  <div class="col-md-12">
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
      <div class="tab-content">
        <?php
        foreach ($wp_view_user_profile as $tab => $user_profile) {
          $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
          $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
          echo '<div class="tab-pane ' . $active . '" id="' . $tab . '">                                    ';
          $WPclass = $user_profile['class'];
          $WPfunction = $user_profile['function'];
          $atts['wpuser_update_setting_nonce']= wp_create_nonce('wpuser-update-setting');
          $atts['data']=$data;
          $atts['user_id']=$user_id;
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
<style>

.margin-right-15 {
    margin-right: 15px;
}
.margin-top-15 {
    margin-top: 15px;
}
</style>
