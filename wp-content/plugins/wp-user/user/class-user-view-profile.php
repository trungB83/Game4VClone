<?php

class WPUserViewProfile
{
    public function __construct()
    {

    }

    static function profile_information($atts = array())
    {
        global $current_user, $wp_roles,$wp_user_appearance_button_type;
        $boolIsdenyPermission = false ;
        $wp_user_profile_field = array();

        $boolIsdenyPermission = apply_filters('wpuser_filter_user_permission', $boolIsdenyPermission, get_current_user_id() );
        if( false == $boolIsdenyPermission ) {
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
        }


        if (isset($atts['id']) && !empty($atts['id'])) {
            //Validation
            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
            $form_fields = get_post_meta($atts['id'], 'fields', true);;
            if ($userplus_field_order) {
                $fields_count = count($userplus_field_order);
                for ($i = 0; $i < $fields_count; $i++) {
                    $key = $userplus_field_order[$i];
                    $array = $form_fields[$key];
                    if (!in_array($array['type'], array('image_upload')) && /*isset($array['visibility']) && $array['visibility']=='1' && */  !in_array($array['meta_key'],
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


        }



        $wp_user_profile_field_filter = apply_filters('wp_user_profile_field_filter', $wp_user_profile_field);

        foreach ($wp_user_profile_field_filter as $key => $array) {
            echo '<div class="" id="accordion" role="tablist" aria-multiselectable="true">

        <div class="box box-default">
          <div class="box-header" role="tab" id="headingOne">
           <h3 class="box-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#my_accout_collapse' . $key . '" aria-expanded="true" aria-controls="collapseOne">';
            echo $array['title'];
            echo '</a>
            </h3>
          </div>
          <div id="my_accout_collapse' . $key . '" class="box-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="box-body">
                    <div class="row">
                    ';

            foreach ($array['fields'] as $key => $value) {
                $textValue = get_the_author_meta($key, $atts['user_id']);
                if ($value['type'] != 'password' && !empty($textValue)) {
                    $icon = (!get_option('wp_user_appearance_icon') && !empty($value['icon'])) ? '<i class="badge fa ' . $value['icon'] . '"> </i> ' : '';
                    $class = ($value['type'] == 'textarea') ? 'col-md-12' : 'col-md-12';
                    $link_open = ($value['type'] == 'url') ? "<a class='wpuser_profile_url_' . $key . '' href='" . $textValue . "' target='_blank'>" : '';
                    $link_close = (!empty($link_open)) ? '</a>' : '';
                    echo '<div class="form-group ' . $class . '">
                     <label for="First name" class=" control-label">' . $link_open . $icon . $link_close . $value['label'] . ':</label>
                     <label id="' . $key . '" class="text-muted wpuser_profile_' . $key . '" style="color:Gray !important">' . $textValue . '</label>
                  </div>';
                }
            }
            echo '</div>
          </div>
          </div>
          </div>
          </div>';
        }

        if (isset($atts['multi_steps']) && !empty($atts['multi_steps'])) {
            $forms = explode(',', $atts['multi_steps']);
            $strParm = " layout = 'block-2' ";
            foreach ($forms as $form){
                echo do_shortcode('[wp_user_form id='.$form.' '.$strParm.']');
            }
        }

    }

    static function posts($atts = array())
    {
      $wp_user_view_profile_layout = (isset($atts['layout']) && !empty($atts['layout'])) ? $atts['layout'] : '';
          if(!empty($wp_user_view_profile_layout)){
              include('view/layout/partial/'.strtolower($wp_user_view_profile_layout).'/posts.php');
      }else{
        include('view/layout/partial/posts.php');
      }
    }

    static function groups($atts = array())
    {
        global $wp_user_appearance_button_type;
        $array = array();
        $user_id = get_current_user_id();

        echo '<div class="row">';
        echo ' <div class="col-md-12 address_response_message" id="address_response_message"></div>';
        global $wpdb;
        echo '</div>';

        ?>
        <div class="group_list" id="tab_find_groups">

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

        <script>
            var $ = jQuery.noConflict();


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

            $("#tab_link_wpuser_group").click(function(){
                getGrouprList(1);
            });


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

            function backTo(action) {
                $('.group_list').show();
                $('#profile_view').show();
                $('.group_view').hide();
                $('#group_view').html('');
            }

        </script>
        <?php

    }


}
