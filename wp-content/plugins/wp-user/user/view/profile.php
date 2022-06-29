<?php
echo '<div class="wrapper">';
global $current_user, $wp_roles;

$wp_user_disable_user_sidebar = get_option('wp_user_disable_user_sidebar');

$args = array(
    'role' => '',
    'role__in' => array(),
    'role__not_in' => array(),
    'meta_key' => '',
    'meta_value' => '',
    'meta_compare' => '',
    'meta_query' => array(),
    'date_query' => array(),
    'include' => array(),
    'exclude' => array(),
    'offset' => '',
    'search' => '',
    'number' => '',
    'count_total' => false,
    'fields' => 'all',
);


$user_id = get_current_user_id();
$attachment_url = esc_url(get_the_author_meta('user_meta_image', $user_id));
$title = (get_user_meta($user_id, 'user_title', true));
$user_status = (get_user_meta($user_id, 'wp-approve-user', true));
// retrieve the thumbnail size of our image
$attachment_url = esc_url(get_the_author_meta('user_meta_image', $user_id));
$attachment_id = profileController::get_attachment_image_by_url($attachment_url);
// retrieve the thumbnail size of our image

$image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
// return the image thumbnail
if (!empty($image_thumb[0])) {
    $wp_user_profile_img = $image_thumb[0];
} else if (!empty($attachment_url)) {
    $wp_user_profile_img = $attachment_url;
} else {
    $args = get_avatar_data($user_id);
    if (!empty($args['url']))
        $wp_user_profile_img = $args['url'];
    else
        $wp_user_profile_img = WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
}


$name = get_the_author_meta('first_name', $user_id) . " " . get_the_author_meta('last_name', $user_id);
$user_mobile = get_the_author_meta('user_mobile', $user_id);
$authors_posts = get_posts(array('author' => $user_id, 'post_status' => 'publish'));
$user_blog_url = (count($authors_posts)) ? get_author_posts_url($user_id) : '';
if (empty(str_replace(' ', '', $name))) {
    $user_info = get_userdata($user_id);
    $name = $user_info->display_name;
    if (empty($name)) {
        $name = $user_info->user_nicename;
    }
    if (empty($name)) {
        $name = $user_info->user_login;
    }
}


$authors_posts = get_posts(array('author' => $user_id, 'post_status' => 'publish'));
// Get all user meta data for $user_id
$meta = get_user_meta($user_id);
$header_block_info = [];
if (get_option('wp_user_disable_posts_my_profile') != 1) {
  $header_block_info = array(
      array(
          "name" => 'Blogs',
          "url" => get_author_posts_url($user_id),
          'icon' => 'fa fa-th-large',
          "count" => count($authors_posts),
      )
  );
}

$current_user = wp_get_current_user();

$email = profileController::wpuser_profile_details('user_email', $user_id);
$user_url = profileController::wpuser_profile_details('user_url', $user_id);
$user_info_box = array(
    "First name" => $meta['first_name'][0],
    "Last name" => $meta['last_name'][0],
    "Email" => $email,//(isset($meta['user_email']) && !empty($meta['user_email'])) ? $meta['user_email'] : '',
    'User URL' => $user_url,
);

$header_block_info = apply_filters('wp_user_member_filter_header_block', $header_block_info, $user_id);
$user_info_box = apply_filters('wp_user_member_info', $user_info_box, $user_id);

if (get_option('wp_user_disable_user_bar') != 1) {
    do_action('wpuser_profile_header', $atts);
}
?>
<div class="row">

    <div id="group_view" class="group_view" style="display:none;">

    </div>
    <div id="profile_view" class="profile_view">


        <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary wpuser-custom-box">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive wpuser_viewimage wpuser_profile_img"
                     src="<?php echo $wp_user_profile_img ?>"
                     alt="User profile picture">

                <h3 class="profile-username text-center wpuser_profile_name"><?php echo $name ?></h3>

                <p class="text-muted text-center"><?php echo $title ?></p>

                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <?php foreach ($header_block_info as $header_block) {
                            $boolIsAccess = $mixViewCount = wpuserAjax::checkAccess($user_id,array(),'views');
                            $link_attr = (($header_block['url']) == '#' && true == $boolIsAccess ) ? 'onclick="getFollowerList(\'' . $user_id . '\',\'\',\'' . $header_block['type'] . '\',\'1\')"' : ' ';
                            $link_attr .=(($header_block['url'])=='#') ? ' ' : " href='" . $header_block['url'] ."' target='_blank' ";
                            $strType = (isset($header_block['type'])) ? $header_block['type'] : '';
                            echo '<li><a '.$link_attr.'><i class="' . $header_block['icon'] . '"></i>&nbsp;&nbsp;' . $header_block['name'] . ' <span class="pull-right badge bg-' . $wp_user_appearance_skin_color . ' wpuser_'.$strType.'_count">' . $header_block['count'] . '</span></a></li>';

                        } ?>
                    </ul>
                </div>

                <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                <?php do_action('wp_user_hook_member_list_button_my_profile'); ?>
            </div>
            <div class="box-footer">
                <a href="<?php echo wp_logout_url(get_permalink()) ?>"
                   class="btn btn-default btn-block"><?php _e('Logout', 'wpuser') ?></a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <?php do_action('wp_user_hook_before_menu_tab_my_profile'); ?>

        <?php
        $wp_user_tab_position_is_vertical = get_option('wp_user_tab_position_is_vertical');
        if (isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) {
            ?>

        <!-- About Me Box -->
        <div class="box box-default wpuser-custom-box">
            <ul class="row nav user_menu text-left">
                <?php foreach ($wp_user_profile as $tab => $user_profile) {
                    if( false == empty($user_profile['tab'])){
                        $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
                        $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
                        $icon = (isset($user_profile['icon']) && !empty($user_profile['icon'])) ? $user_profile['icon'] : 'fa fa-navicon' ;
                        $href=($user_profile['function']=='tab_link_function') ? $user_profile['value'] :'#'. $tab ;
                        $data_toggale=($user_profile['function']=='tab_link_function') ? '' : 'data-toggle="tab"';
                        echo ' <li class="menu_item col-md-12 col-sm-4 ' .$active . '" id="tab_'.$tab.'"> <a id="tab_link_'.$tab.'" class="'. $tab.'" href="'.$href .'" '.$data_toggale.'><spam class="user_menu_icon '.$icon.'"></spam> <spam class="menu_list">' . $user_profile['tab'] . '</spam></a></li>';

                      //  echo ' <li class="' .$active . '"><a class="'. $tab.'" href="'.$href .'" '.$data_toggale.'>' . $user_profile['tab'] . '</a></li>';
                    }
                } ?>
            </ul>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <?php } ?>

        <?php do_action('wp_user_hook_after_menu_tab_my_profile'); ?>

    </div>
    <!-- /.col -->
    <div class="col-md-<?php echo ($wp_user_disable_user_sidebar != 1) ? '6' : '9'; ?>">
        <div class="nav-tabs-custom wpuser-custom-nav" id="myProfileSection">
            <?php if (!(isset($wp_user_tab_position_is_vertical) && !empty($wp_user_tab_position_is_vertical)) ){ ?>
            <ul class="nav nav-tabs">
                <?php foreach ($wp_user_profile as $tab => $user_profile) {
                    if( false == empty($user_profile['tab'])){
                        $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
                        $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
                      //  $icon = (isset($user_profile['icon']) && !empty($user_profile['icon'])) ? $user_profile['icon'] : 'fa fa-navicon' ;
                        $href=($user_profile['function']=='tab_link_function') ? $user_profile['value'] :'#'. $tab ;
                        $data_toggale=($user_profile['function']=='tab_link_function') ? '' : 'data-toggle="tab"';
                        echo ' <li class="' . $active . '"><a class="'.$tab.'" href="' . $href . '" '.$data_toggale.'>' . $user_profile['tab'] . '</a></li>';
                    }
                } ?>
            </ul>
            <?php } ?>
            <div class="tab-content">
                <?php
                foreach ($wp_user_profile as $tab => $user_profile) {
                    $active=(isset($_GET['tab_active'])) ? '' : $user_profile['active'] ;
                    $active = (isset($_GET['tab_active']) && $_GET['tab_active']==$tab)  ? 'active' :$active ;
                    echo '<div class="tab-pane ' . $active . '" id="' . $tab . '">                                    ';
                    $WPclass = $user_profile['class'];
                    $WPfunction = $user_profile['function'];
                    @$atts['form_id']=$form_id;
                    @$atts['wpuser_update_setting_nonce']= wp_create_nonce('wpuser-update-setting');
                    if($WPfunction=='tab_content_function'){
                        $atts=$user_profile['value'];
                    }
                    $WPclass::$WPfunction($atts);
                    echo '</div>';
                } ?>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>

        <div id="myNotification" style="display:none">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-bell-o"></i>
                    <h3 class="box-title"><?php _e('Notifications','wpuser')?></h3>
                    <button type="button" onclick="closeNotification()" class="btn btn-default btn-flat pull-right ">Close</button>
                    (<a href="#" onclick="removeNotification(0)"><?php _e('Clear All','wpuser') ?></a>)
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="myNotificationBody">
                    </div>

                </div>


        </div>
        <!-- /.nav-tabs-custom -->
    </div>

    <!-- /.col -->
    <?php
    if ($wp_user_disable_user_sidebar != 1) {
        echo ' <div class="col-md-3">';
        do_action('wpuser_action_profile_sidebar',$atts);
        echo '</div>';
    }
    ?>

    <!-- /.col -->
        </div>
</div>
</div>
<script>
$(".nav-tabs a").click(function(){
     $(this).tab('show');
 });
</script>
