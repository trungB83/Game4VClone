<?php

class WPUserMyProfile
{
    public function __construct()
    {

    }

    static function my_account($atts = array())
    {
        global $current_user, $wp_roles,$wp_user_appearance_button_type;


        $wp_user_profile_field['basic'] =
            array(
                'title' => __('Basic Information', 'wpuser'),
                'fields' => array(
                    'first_name' => array(
                        'label' => __('First Name', 'wpuser'),
                        'icon' => '',
                        'description' => '',
                        'type' => 'text',
                    ),
                    'last_name' => array(
                        'label' => __('Last Name', 'wpuser'),
                        'icon' => '',
                        'description' => '',
                        'type' => 'text',
                    ),
                    'user_email' => array(
                        'label' => __('Email', 'wpuser'),
                        'icon' => '',
                        'description' => '',
                        'type' => 'email',
                        'required' => 'required'
                    ),
                    'user_url' => array(
                        'label' => __('Website', 'wpuser'),
                        'icon' => '',
                        'description' => '',
                        'type' => 'text',
                    ),
                    'description' => array(
                        'label' => __('Description', 'wpuser'),
                        'description' => '',
                        'icon' => '',
                        'type' => 'textarea'
                    )
                )
            );


        if (isset($atts['id']) && !empty($atts['id'])) {
            //Validation
            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
            $form_fields = get_post_meta($atts['id'], 'fields', true);;
            if ($userplus_field_order) {
                $fields_count = count($userplus_field_order);
                for ($i = 0; $i < $fields_count; $i++) {
                    $key = $userplus_field_order[$i];
                    $array = $form_fields[$key];
                    if (!in_array($array['type'], array('image_upload')) && !in_array($array['meta_key'],
                            array('user_login', 'user_pass', 'user_url', 'first_name', 'description', 'user_email', 'last_name'))
                    ) {
                        $profile_fields[$array['meta_key']] = array(
                            'label' => $array['label'],
                            'icon' => $array['icon'],
                            'description' => (isset($array['description']) && !empty($array['description'])) ? $array['description'] : '',
                            'type' => $array['type'],
                        );
                    }
                }
            }

            if (!empty($profile_fields)) {
                $wp_user_profile_field['advanced'] = array(
                    'title' => __('Advanced Information', 'wpuser'),
                    'fields' => $profile_fields
                );
            }
        }

        do_action('wp_user_profile_my_account_header');


        $wp_user_profile_field_filter = apply_filters('wp_user_profile_field_filter', $wp_user_profile_field);

        foreach ($wp_user_profile_field_filter as $key => $array) {
            echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#my_accout_collapse' . $key . '" aria-expanded="true" aria-controls="collapseOne">';
            echo $array['title'];
            echo '</a>
            </label>
          </div>
          <div id="my_accout_collapse' . $key . '" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                    <div class="row">
                    
                    ';
            $yit_ywpar_options_enabled  = get_option('yit_ywpar_options');
            if( !empty( $yit_ywpar_options_enabled) && $yit_ywpar_options_enabled['enabled'] == 'yes') {
                $intUserTotalPoints =get_user_meta( get_current_user_id(), '_ywpar_user_total_points', true );
                echo '<div class="col-lg-10 col-xs-6">';
            }

            if ($key == 'basic') {
                echo '<div class="form-group col-md-6">
                     <label for="First name" class=" control-label">';
                _e('Username', 'wpuser');
                echo ':</label>
                     <label id="" class="" style="color:Gray !important">' . $current_user->user_login . '</label>
                  </div>';

                echo '<div class="form-group col-md-6">
                     <label for="First name" class=" control-label">';
                _e('Display name', 'wpuser');
                echo ':</label>
                     <label id="" class="text-muted" style="color:Gray !important">' . $current_user->display_name . '</label>
                  </div>';


            }


            foreach ($array['fields'] as $key => $value) {
                $textValue = get_the_author_meta($key, get_current_user_id());
                if ($value['type'] != 'password' && !empty($textValue)) {
                    $icon = (!get_option('wp_user_appearance_icon') && !empty($value['icon'])) ? '<i class="' . $value['icon'] . '"> </i> ' : '';
                    $class = ($value['type'] == 'textarea') ? 'col-md-12' : 'col-md-6';
                    $link_open = ($value['type'] == 'url') ? "<a class='wpuser_profile_url_' . $key . '' href='" . $textValue . "' target='_blank'>" : '';
                    $link_close = (!empty($link_open)) ? '</a>' : '';
                    echo '<div class="form-group ' . $class . '">
                     <label for="First name" class=" control-label">' . $link_open . $icon . $link_close . $value['label'] . ':</label>
                     <label id="' . $key . '" class="text-muted wpuser_profile_' . $key . '" style="color:Gray !important">' . $textValue . '</label>
                  </div>';
                }
            }

            if( !empty( $yit_ywpar_options_enabled) && $yit_ywpar_options_enabled['enabled'] == 'yes') {
                $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
                    (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');
                echo '</div>
                         <div class="col-lg-2 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-'.$wp_user_appearance_skin_color.'">
                            <div class="inner text-center">
                              <h3>'.$intUserTotalPoints.'</h3>
                            </div>      
                            <a href="#" class="small-box-footer">'.__('Reward Points','wpuser').'</i></a>
                          </div>
                        </div>';
            }

            echo '</div>
          </div>
          </div>
          </div>
          </div>';
        }
        do_action('wp_user_profile_my_account_footer');
    }

    static function edit_profile($atts = array())
    {
        global $wpdb;
        global $current_user, $wp_roles, $wp_user_appearance_button_type;

        $wp_user_login_limit_password = get_option('wp_user_login_limit_password');
        $wp_user_login_limit_password_enable = get_option('wp_user_login_limit_password_enable');
        $wp_user_login_password_valid_message = (isset($wp_user_login_limit_password_enable) && isset($wp_user_login_limit_password)) ?
            get_option('wp_user_login_password_valid_message') : '';

        include('view/option.php');

        // print_r($wp_user_options_signup_form);
        // print_r($atts);

        $form_id = isset($atts['form_id']) ? $atts['form_id'] : "";
        $wp_user_appearance_icon = (isset($atts['icon'])) ? $atts['icon'] : get_option('wp_user_appearance_icon');
        $wp_user_appearance_skin = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
            (get_option('wp_user_appearance_skin') ? get_option('wp_user_appearance_skin') : 'default');
        $wp_user_register_enable = get_option('wp_user_disable_signup');
        $user_id = get_current_user_id();
        $wpuser_update_setting_nonce = isset($atts['wpuser_update_setting_nonce']) ? $atts['wpuser_update_setting_nonce'] : '';

        echo '<div style="display: none;" id="wpuser_errordiv_register">         
            </div>';

        echo '<form method="post" id="google_form">
                <input name="wpuser_update_setting" type="hidden"
               value="' .$wpuser_update_setting_nonce . '"/>';

        do_action('wp_user_hook_myprofile_form_header');
        $wpuser_form_id = get_user_meta($user_id, 'wpuser_form_id', true);
        if (isset($wpuser_form_id) && !empty($wpuser_form_id)) {
            $atts['id']=$wpuser_form_id;
        }
        if (isset($atts['id']) && !empty($atts['id'])) {
            echo '<input name="wpuser_form_id" type="hidden"
                       value="' . $atts['id'] . '">';
            global $userplus;
            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
            $form_fields = get_post_meta($atts['id'], 'fields', true);;
            if ($userplus_field_order) {
                $fields_count = count($userplus_field_order);
                for ($i = 0; $i < $fields_count; $i++) {
                    $key = $userplus_field_order[$i];
                    $array = $form_fields[$key];
                    if ($key != 'user_login')
                        echo profileController::edit_fields($key, $array, $wp_user_appearance_skin, $form_id, $user_id);
                }
            }
        } else {
            foreach ($wp_user_options_my_profile_form as $array) {
                echo profileController::edit_fields($array['meta_key'], $array, $wp_user_appearance_skin, $form_id, $user_id);
            }
        }
        do_action('wp_user_hook_myprofile_form');

        $button_name = __('Save', 'wpuser');

        echo '<div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <input type="button" class="wpuser_button btn btn-primary '.$wp_user_appearance_button_type.' wpuser-custom-button"
                               id="wpuser_update_profile_button" name="wpuser_register"
                               value="' . $button_name . '">        
                 </div>
        </div>
        </form>';
        ?>
        <script>
            var $ = jQuery.noConflict();

         
        </script>
<?php
    }

    static function address($atts = array())
    {
        if (class_exists('WC_Admin_Profile')) {
            echo ' 
   <div style="display: none;" id="wp_user_address_div" class="wp-user-alert alert alert-dismissible fade in" role="alert"><label id="wp_user_address_label"></label>
                        <button id="wp_user_address_div_close" class="close" type="button">
                          <span aria-hidden="true">&times;</span>
                      </button>
                         </div>
                          <form  id="wp_user_address_field_form" class="" name="wp_user_address_field_form" method="post" action="">
                          <div class="row">';
            $array = WC_Admin_Profile::get_customer_meta_fields();
            foreach ($array as $array) {
                echo '<div class="col-md-6">';
                echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" id="headingOne">
            <label class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="true" aria-controls="collapseOne">';
                echo $array['title'];
                echo '</a>
            </label>
          </div>
          <div id="collapse" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body">';
                foreach ($array['fields'] as $key => $value) {
                    echo '<div class="form-group"> <label>' . $value['label'] . '</label>';
                    if (empty($value['type'])) {
                        echo '<input type="text" class="form-control" id="' . $key . '" placeholder="' . $value['label'] . '" name="' . $key . '" value="' . get_user_meta(get_current_user_id(), $key, true) . '">';
                    }
                    if (($value['type'] == 'select')) {
                        echo '<select class="form-control" id="' . $key . '"  name="' . $key . '">';
                        foreach ($value['options'] as $optionKey => $optionValue) {
                            $selected = (get_user_meta(get_current_user_id(), $key, true) == $optionKey) ? 'selected' : '';
                            echo '<option id="' . $optionKey . '" ' . $selected . ' value="' . $optionKey . '">' . $optionValue . '</option>';
                        }
                        echo '</select>';

                    }
                    echo '<p>' . $value['description'] . '</p>';
                    echo '</div>';
                }
                echo '</div>
          </div>
        </div>
                </div>';
                echo '</div>';
            }
            $wpuser_update_setting_nonce = isset($atts['wpuser_update_setting_nonce']) ? $atts['wpuser_update_setting_nonce'] : '';
            echo '</div>
      <input name="wpuser_action" type="hidden" value="address_wp_user">
        <input name="wpuser_update_setting" type="hidden" value="' . $wpuser_update_setting_nonce . '"/>
        <input type="submit" id="wp_user_address_field_submit" class="wpuser_button btn '.$wp_user_appearance_button_type.' btn-primary wpuser-custom-button" name="wpuser_address" value="Save">
      </form>';         
            
        }
    }

    static function groups($atts = array())
    {
        global $wp_user_appearance_button_type;
        $is_woo_exist = 0;
        $array = array();
        $user_id = get_current_user_id();
        $user_group = get_user_meta($user_id, '$wpuser_group', true);

            $wpuser_group['title'] = array(
                'woo' => 1,
                'description' => '',
                'required'=>1,
                'label' => 'Group Title'
            );
            $wpuser_group['description'] = array(
                'woo' => 1,
                'description' => '',
                'label' => 'Description',
                'type'=>'textarea'
            );
            $wpuser_group['category'] = array(
                'woo' => 1,
                'description' => '',
                'label' => 'Category',
                'type'=>'select',
                'options' => ['other','sport','education'],
                'placeholder' => 'Select Group category'
            );
            $wpuser_group['tags'] = array(
                'woo' => 1,
                'description' => '',
                'label' => 'Tags'
            );
            $wpuser_group['area'] = array(
                'woo' => 1,
                'description' => 'Group Area',
                'label' => 'Area'
            );
            $wpuser_group['visibility'] = array(
            'woo' => 1,
            'description' => __('Select privacy. Public-Anyone can see the group, Closed - Only Admin Follower see the group, Private - Only Admin added see the group.','wpuser'),
            'label' => 'Select Group visibility',
            'type'=>'select',
            'options' => ['public','closed','private'],
            'placeholder' => 'Select Group visibility'
          );

            $array ['title'] = 'Create New Group';
            $array ['fields'] = $wpuser_group;

        echo '<div class="row">';
        echo ' <div class="col-md-12 address_response_message" id="address_response_message"></div>';
         global $wpdb;
         echo '</div>';

        ?>
        <input type="hidden" name="wpuser_my_profile_group" id="wpuser_my_profile_group" value="1">
        <div class="nav-tabs-custom group_list">

            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_groups" data-toggle="tab" aria-expanded="true">Manage Groups</a></li>
              <li class=""><a href="#tab_find_groups" data-toggle="tab" aria-expanded="false" onclick="getGrouprList('1')">Find Groups</a></li>
                <li class="pull-right">
                <?php
                echo '<label id="wp_user_profile_add_group" class="wpuser_button wp_user_profile_add_group btn ' . $wp_user_appearance_button_type . ' btn-primary wpuser-custom-button">';
                _e('Create Group', 'wpuser');
                echo '</label><br>';
                ?>
                </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_groups">
                  <div class="box-body" id="group_list" style="">
                      <?php
                      $q = "SELECT g.id as group_id,g.title,g.category,g.icon, 
                                      (SELECT count(id) FROM {$wpdb->prefix}wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=g.id) as member_count, 
                                      (SELECT count(id) FROM wp_wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=g.id AND meta_value='$user_id') as is_member,
                                      (SELECT count(id) FROM wp_wpuser_group_meta WHERE (meta_key='admin') AND group_id=g.id AND meta_value='$user_id') as is_admin
                                     FROM {$wpdb->prefix}wpuser_groups g WHERE g.id IN (SELECT group_id from {$wpdb->prefix}wpuser_group_meta WHERE (meta_key='admin') AND meta_value='$user_id')  ORDER BY g.title DESC";
                      $user_group = $wpdb->get_results($q,ARRAY_A);
                      if (!empty($user_group)) {
                          foreach ($user_group as $group) {
                              echo wpuserAjaxgroups::buildgroupHtml($group);
                          }

                      }
                      ?>
                  </div>

              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_find_groups">

                  <div class="box box-default collapsed-box">
                      <div class="box-header with-border">
                          <h3 class="box-title"><span id="wpuser_filter" class="wpuser_filter"><span class="fa fa-filter"></span> Filter</span> </h3>

                          <div class="box-tools pull-right">
                              <button type="button" onclick="getGroupFilterData()" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                              </button>
                          </div>
                          <!-- /.box-tools -->
                      </div>
                      <!-- /.box-header -->
                      <div class="box-body" style="display: none;">
                          <form name="wpuser_filter_form" id="wpuser_filter_form">
                              <div class="form-group">
                                  <input type="text" name="wpuser_filter_search" class="wpuser_filter_search" id="wpuser_filter_search" class="form-control" onkeyup="showFilterResult(this.value)" placeholder="Search">
                                  <div id="filterlivesearch" class="" style="position: absolute"></div>
                              </div>
                              <div class="col-md-12">
                                  <a class="pull-right" data-toggle="collapse" href="#collapseFilter" aria-expanded="false" aria-controls="collapseExample">
                                      <span class="fa fa-gear"></span> Advance Filter
                                  </a>
                              </div>
                              <div class="collapse" id="collapseFilter">
                                      <div id="advanced_filter" class="advanced_filter">
                                      </div>
                              </div>
                              <button type="button" onclick="getGrouprList('1')" class="btn btn-primary btn-flat">Filter</button>
                              <button type="button" id="resetFilter" class="btn btn-default btn-flat" onclick="this.form.reset();">Reset</button>
                          </form>
                      </div>
                      <!-- /.box-body -->
                  </div>

                  <h4 class="title" id="groupTitle"></h4>
                  <div class="row">
                      <div class="col-md-12" id="find_groups">

                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          <ul id="group_pagination" class="pagination pagination-sm"></ul>
                      </div>
                  </div>

              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
        <?php



        // if ($is_woo_exist == 1) {
        echo ' 
 <div id="wp_user_add_group_div" style="display:none;">
   <div style="display:none;" id="wp_user_group_div">
                         </div>';

        echo '<form  id="wp_user_group_field_form" class="form-horizontal" name="wp_user_group_field_form">
                        <input name="wpuser_update_setting" type="hidden" value="' . wp_create_nonce("wpuser-update-setting") . '"/>
                       <input name="update_group_id" id="update_group_id" type="hidden" value=""/>


                          <div class="row">';
        echo '<h3>'.$array ['title'].'</h3>';
        ?>
        <p>Groups are great for getting things done and staying in touch with just the people you want. Share posts,photos, videos,
            have conversations, make plans and more</p>
        <div class="col-sm-6">
            <label class="control-label"><?php _e('Group Icon', 'wpuser'); ?></label>
            <div class="">
                <select class="form-control" id="form_icon" name="wpuser[icon]"
                        style="font-family: 'FontAwesome', Helvetica;">
                    <option value=""><?php _e('Set Icon', 'wpuser'); ?></option>
                    <option value="fa fa-home">&#xf015;</option>
                    <option value="fa fa-graduation-cap">&#xf19d;</option>
                    <option value="fa fa-plane">&#xf072;</option>
                    <option value="fa fa-bank">&#xf19c;</option>
                    <option value="fa fa-building">&#xf1ad;</option>
                    <option value="fa fa-desktop">&#xf108;</option>
                    <option value="fa fa-globe">&#xf0ac;</option>
                    <option value="fa fa-bed">&#xf236;</option>
                    <option value="fa fa-industry">&#xf275;</option>
                    <option value="fa fa-shopping-cart">&#xf07a;</option>
                    <option value="fa fa-address-card">&#xf2bb;</option>
                    <option value="fa fa-amazon">&#xf270;</option>
                </select>
            </div>
        </div>
        <?php


        foreach ($array['fields'] as $key => $value) {
            SELF::createFormFields($key,$value);
        }

        ?> </div>
        <?php if($is_woo_exist==1){ ?>
        <div class="col-sm-6 wp_user_profile_woo_group">

            <input type="hidden" class="" id="is_billing_group" " name="wpuser_group[is_billing_group]"
            value="0">
            <input type="checkbox" class="" id="is_billing_group" " name="wpuser_group[is_billing_group]"
            value="1">
            <label><?php _e('Mark this billing address as defualt', 'wpuser'); ?></label>
        </div>
        <div class="col-sm-6 wp_user_profile_woo_group">
            <input type="hidden" class="" id="is_shipping_group" " name="wpuser_group[is_shipping_group]"
            value="0">
            <input type="checkbox" class="" id="is_shipping_group" " name="wpuser_group[is_shipping_group]"
            value="1">
            <label><?php _e('Mark this shipping address as defualt', 'wpuser'); ?></label>
        </div>
    <?php } ?>
        <div class="form-group">
        <div class="pull-right col-sm-offset-6 col-sm-6">
        <?php
        echo '<label id="wp_user_profile_close_group" style="display:none;" class="wpuser_button btn ' . $wp_user_appearance_button_type . ' btn-default wpuser-custom-button">';
        _e('Close', 'wpuser');
        echo '</label>  ';
        echo '<label id="wp_user_profile_group_submit" class="wpuser_button btn ' . $wp_user_appearance_button_type . ' btn-primary wpuser-custom-button">';
        _e('Save', 'wpuser');
        echo '</label>';
        echo '</label>  ';
        echo '<label id="wp_user_profile_group_update" style="display:none" class="wpuser_button btn ' . $wp_user_appearance_button_type . ' btn-primary wpuser-custom-button">';
        _e('Update', 'wpuser');
        echo '</label>';
        echo '</div>
                  </div>
                </form>
                </div>';

        //   }
        ?>
        <script>
            var $ = jQuery.noConflict();
            $("#wp_user_profile_group_submit").click(function () {
                $.ajax({
                    type: "post",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_add_group',
                    data: $("#wp_user_group_field_form").serialize(),
                    success: function (data) {
                        var parsed = $.parseJSON(data);
                        $("#wp_user_group_div").html('<div class="wp-user-alert alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button>' + parsed.message + '</div>');
                        if (parsed.status == 'success') {
                            if (parsed.is_added_billing == '1') {
                                $('.action_box').removeClass('box-success');
                                $('.action_box').removeClass('box-primary');
                                $('.action_billing').removeClass().addClass('action_billing badge bg-blue');
                            }
                            if (parsed.is_added_shiping == '1') {
                                $('.action_box').removeClass('box-warning');
                                $('.action_box').removeClass('box-primary');
                                $('.action_shiping').removeClass().addClass('action_billing badge bg-blue');
                            }
                            $("#wp_user_group_field_form")[0].reset();
                            $('#group_list').append(parsed.html);
                        }
                        $('#wp_user_group_div').show();
                        $('body, html').animate({scrollTop:$('#wp_user_add_group_div').offset().top}, 'slow');
                        return false;
                    },
                });
            });

            $("#wp_user_profile_group_update").click(function () {
                $.ajax({
                    type: "post",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_update_group',
                    data: $("#wp_user_group_field_form").serialize() + '&form_action=edit',
                    success: function (data) {
                        var parsed = $.parseJSON(data);
                        $("#wp_user_group_div").html('<div class="wp-user-alert alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button>' + parsed.message + '</div>');
                        if (parsed.status == 'success') {
                            $("#wp_user_group_field_form")[0].reset();
                            $("#wp_user_profile_group_update").css("display", "none");
                            $("#wp_user_add_group_div").css("display", "none");
                            $("#wp_user_profile_group_submit").css("display", "inline-block");
                            $(".group_list").css("display", "block");
                            $("#wp_user_profile_close_group").css("display", "inline-block");
                            $("#wp_user_profile_add_group").css("display", "inline-block");
                            $(".wp_user_profile_woo_group").css("display", "block");
                            $('#group_' + parsed.update_group_id).replaceWith(parsed.html);
                        }
                        $('#group_list').show();
                        $('.group_view').hide();
                    },
                });
            });

            $("#wp_user_profile_add_group").click(function () {
                $('#wp_user_add_group_div').show();
                $('#wp_user_profile_close_group').show();
                $('#wp_user_profile_add_group').hide();
                $("#wp_user_profile_group_submit").css("display", "inline-block");
                $('.group_list').hide();
                $('.group_view').hide();
            });
            $("#wp_user_profile_close_group").click(function () {
                $('#wp_user_add_group_div').hide();
                $('#wp_user_profile_add_group').show();
                $('#wp_user_profile_close_group').hide();
                $('.group_list').show();
                $('.group_view').hide();
            });

            function backTo(action) {
                if (action == 'myprofile') {
                    $('#wp_user_add_group_div').hide();
                    $('#wp_user_profile_add_group').show();
                    $('#wp_user_profile_close_group').hide();
                    $('.group_list').show();
                    $('#profile_view').show();
                    $('.group_view').hide();
                    $('#group_view').html('');
                }
                else  if (action == 'groups') {
                    $('#wp_user_add_group_div').hide();
                    $('#wp_user_profile_add_group').show();
                    $('#wp_user_profile_close_group').hide();
                    $('.group_list').show();
                    $('#profile_view').show();
                    $('.group_view').hide();
                    $('#group_view').html('');
                }

            }

           

            function group_action(id, action) {
                    if (action == 'delete') {
                        var r = confirm('<?php _e('Are you sure want to delete?', 'wpuser') ?>');
                        if (r == true) {
                            groupAction(id, action);
                        }
                    }else {
                        groupAction(id, action);
                    }    
            }

            function groupAction(id, action) {
                var wpuser_update_setting = '<?php echo wp_create_nonce('wpuser-update-setting')?>';
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_group_action',
                    data: 'id=' + id + '&group_action=' + action  + '&wpuser_update_setting=' + wpuser_update_setting,
                    success: function (response) {
                        if (!(action == 'edit' || action == 'view' || action == 'join' || action == 'leave')) {
                            jQuery("#address_response_message").html('<div class="wp-user-alert alert alert-' + response.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button>' + response.message + '</div>');
                        }
                        if (response.status == 'success') {
                            if (action == 'delete') {
                                $('.group_' + id + ' div').remove();
                            } else if (action == 'edit') {
                                $("#wp_user_profile_group_update").css("display", "inline-block");
                                $("#wp_user_add_group_div").css("display", "block");
                                $("#wp_user_profile_group_submit").css("display", "none");
                               // $("#group_list").css("display", "none");
                                $('.group_list').hide();
                                $('.group_view').hide();
                                $("#wp_user_profile_close_group").css("display", "none");
                                $("#wp_user_profile_add_group").css("display", "none");
                                $('#update_group_id').val(response.update_group_id);
                                $.each(response.data, function (i, val) {
                                    $('#form_' + i).val(val);
                                });
                            }
                            else if (action == 'view') {
                                $("#wp_user_profile_group_submit").css("display", "none");
                                // $("#group_list").css("display", "none");
                                $('#profile_view').hide();
                                $('#group_view').show();
                                $("#wp_user_profile_close_group").css("display", "none");
                                $("#wp_user_profile_add_group").css("display", "none");
                                $('#group_view').html(response.html)
                            }
                            else if (action == 'join') {
                                $('.group_join_'+id).html(response.html);
                                var member_count = $('#member_count'+id).html();
                                member_count= parseInt(member_count) +1 ;
                                $('.member_count'+id).html(member_count)
                            }
                            else if (action == 'leave') {
                                $('.group_join_'+id).html(response.html);
                                var member_count = $('#member_count'+id).html();
                                member_count= parseInt(member_count) - 1 ;
                                $('.member_count'+id).html(member_count)
                            }
                        }
                    }
                })
            }

        </script>
        <style>
            .list-item-action {
                display: none;
                cursor: pointer;
            }

            .list-item:hover .list-item-action {
                display: inline;
            }

            #wp_user_profile_group_update {

            }
        </style>
        <?php

    }

    static function contact_us($atts = array())
    {
        global $wp_user_appearance_button_type;
        $wpuser_update_setting_nonce = isset($atts['wpuser_update_setting_nonce']) ? $atts['wpuser_update_setting_nonce'] : '';

        echo '<div class="row">
                <div class="col-sm-12">
                <div style="display:none;" id="wp_user_contact_div">
                </div>                                
                <form  id="wp_user_profile_contact_form" class="form-horizontal" name="wp_user_profile_contact_form">
                <input name="wpuser_update_setting" type="hidden" value="' .$wpuser_update_setting_nonce . '"/>

                  <div class="form-group">                  

                    <div class="col-sm-12">
                      <input type="text" class="form-control" id="wp_user_email_subject" name="wp_user_email_subject" placeholder="Subject" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <textarea placeholder="Mail Content" id="wp_user_email_content" name="wp_user_email_content" required> </textarea>
                    </div>
                    </div>
             
                  <div class="form-group">
                    <div class="col-sm-offset-9">
                <label id="wp_user_profile_contact_submit" class="wpuser_button btn '.$wp_user_appearance_button_type.' btn-primary wpuser-custom-button">';
            _e('Send', 'wpuser');
        echo '</label>';
        echo '</div>
              </div>
              </form>
              </div>
              </div>';
    }

    static function support($atts = array()){
        echo do_shortcode('[supportcandy]');
    }

    static function wishlist($atts = array()){
        echo do_shortcode('[yith_wcwl_wishlist]');
    }
    
    public static function tab_content_function($tab_content){
        echo apply_filters( 'the_content', $tab_content);
    }

    public static function tab_link_function(){
        echo 'tab_function';
    }

    public static function array_sort($array, $on, $order=SORT_ASC){

        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public static function createFormFields($key,$value){
        $required = (isset($value['required']) && !empty($value['required'])) ? '*' : '';
        $is_required= ($required) ? 'required' : '';
        echo '<div class="col-sm-12"> <label class="control-label">' . ucfirst($value['label']) . $required. '</label>';
        echo '<div class="">';

        if (isset($value['type']) && $value['type'] == 'textarea') {
            echo '<textarea  id="form_' . $key . '" name="wpuser[' . $key . ']" cols="100" rows="4"></textarea>';
        }
        else if (isset($value['type']) && $value['type'] == 'checkbox') {
            echo '<input type="checkbox" class="" id="form_' . $key . '" value="on" name="wpuser[' . $key . ']">';
        }
        else if(isset($value['type']) && $value['type'] == 'multiplecheckbox'){
            echo '<div class="row">';
            $options=$value['options'];
            foreach ($options as $optionKey => $optionValue) {
                echo '<div class="col-md-3">';
                echo '<input id="form_' . $optionKey . '"  type="checkbox" name="wpuser[' . $key . '][]" value="' . esc_attr(strtolower($optionValue)) . '">' . ucfirst($optionValue) ;
                echo '</div>';
            }
            echo '</div>';
        }
        else if (isset($value['type']) && $value['type'] == 'select') {
            echo '<select class="form-control" id="form_' . $key . '" name="wpuser[' . $key . ']">';
            foreach ($value['options'] as $optionKey => $optionValue) {
                // $selected = (get_user_meta(get_current_user_id(), $key, true) == $optionKey) ? 'selected' : '';
                $selected = "";
                echo '<option id="form_' . $optionKey . '" ' . $selected . ' value="' . esc_attr(strtolower($optionValue)) . '">' . ucfirst($optionValue) . '</option>';
            }
            echo '</select>';

        }
        else  {
            $input_value = '';
            echo '<input type="text" class="form-control" '.$is_required.' id="form_' . $key . '" placeholder="' . $value['label'] . '" name="wpuser[' . $key . ']" value="' . $input_value . '">';
        }
        echo '<p>' . $value['description'] . '</p>';
        echo '</div>';
        echo '</div>';
    }

}