<?php

class wpuserTabSetting
{
    public function __construct()
    {

    }
    
    public static function createFormFields($key,$value){
        echo '<div class="col-sm-12"> <label class="control-label">' . $value['label'] . '</label>';
        echo '<div class="">';

        if (isset($value['type']) && $value['type'] == 'textarea') {
            echo '<textarea  id="' . $key . '" name="wpuser_tab[' . $key . ']" cols="100" rows="4"></textarea>';
        }
        else if (isset($value['type']) && $value['type'] == 'checkbox') {
            echo '<input type="checkbox" class="" id="' . $key . '" value="on" name="wpuser_tab[' . $key . ']">';
        }
        else if(isset($value['type']) && $value['type'] == 'multiplecheckbox'){
            echo '<div class="row">';
            $options=$value['options'];
            foreach ($options as $optionKey => $optionValue) {
                echo '<div class="col-md-3">';
                echo '<input id="' .$key.$optionKey . '"  type="checkbox" name="wpuser_tab[' . $key . '][]" value="' . esc_attr(strtolower($optionValue)) . '">' . $optionValue ;
                echo '</div>';
            }
            echo '</div>';
        }
        else if (isset($value['type']) && $value['type'] == 'select') {
            echo '<select class="form-control" id="' . $key . '" name="wpuser_tab[' . $key . ']">';
            foreach ($value['options'] as $optionKey => $optionValue) {
                // $selected = (get_user_meta(get_current_user_id(), $key, true) == $optionKey) ? 'selected' : '';
                $selected = "";
                echo '<option id="' . $optionKey . '" ' . $selected . ' value="' . $optionKey . '">' . $optionValue . '</option>';
            }
            echo '</select>';

        }
        else  {
            $input_value = '';
            echo '<input type="text" class="form-control" id="' . $key . '" placeholder="' . $value['label'] . '" name="wpuser_tab[' . $key . ']" value="' . $input_value . '">';
        }
        echo '<p>' . $value['description'] . '</p>';
        echo '</div>';
        echo '</div>';
    }

    static function my_profile_tab($atts = array())
    {
        $user_tab = get_option('wpuser_tabs');

            $wpuser_tab['tab_title'] = array(
                'is_required' => 1,
                'description' => '',
                'label' => 'Tab Title'
            );

        $wpuser_tab['tab_icon'] = array(
            'is_required' => 0,
            'description' => 'Set icon for tab. Ex: fa fa-home',
            'label' => 'Tab Icon'
        );
        
            $wpuser_tab['tab_content'] = array(
                'is_required' => 0,
                'description' => 'You can add Text, HTML, Shortcode, href in tab content section.',
                'type'=>'textarea',
                'label' => 'Tab Content'
            );

            $wpuser_tab['is_link'] = array(
                'type'=>'checkbox',
                'description' => 'Check if you want to redirect custom url on click tab. Enter url in <b>Tab Content</b>. Ex.www.yourdomain.com/page',
                'label' => 'Is Link'
            );

        $wp_roles = wp_roles();
        foreach ($wp_roles->role_names as $role_names) {
            $options[str_replace(' ', '_', strtolower($role_names))] = $role_names;
        }

        $optionsAccessLevel = array(
            'login' => 'login',
            'level_1' => 'level_1',
            'level_2' => 'level_2',
        );

        $wpuser_tab['tab_visible_role'] = array(
            'is_required' => 0,
            'description' => __('Choose the user role that user can see this tab in my profile/edit profile. Leave blank for visible to all user role.', 'wpuser'),
            'type'=>'multiplecheckbox',
            'label' => 'Edit : Tab Visible to',
            'options' => $options
        );

        $wpuser_tab['tab_visible_role_edit_level'] = array(
            'is_required' => 0,
            'description' => __('Choose the user level that user can see this tab in my profile/edit profile. Leave blank for visible to all user level.', 'wpuser'),
            'type'=>'multiplecheckbox',
            'label' => 'Edit : Tab Visible to',
            'options' => $optionsAccessLevel
        );

        $wpuser_tab['tab_visible_role_view'] = array(
            'is_required' => 0,
            'description' => __('Choose the user role that user can see this tab in other user profile view. Leave blank for visible to all user role.', 'wpuser'),
            'type'=>'multiplecheckbox',
            'label' => 'View : Tab Visible to',
            'options' => $options
        );

        $wpuser_tab['tab_visible_role_view_level'] = array(
            'is_required' => 0,
            'description' => __('Choose the user level that user can see this tab in other user profile view. Leave blank for visible to all user level.', 'wpuser'),
            'type'=>'multiplecheckbox',
            'label' => 'Edit : Tab Visible to',
            'options' => $optionsAccessLevel
        );

            $array ['title'] = 'Tabs';
            $array ['fields'] = $wpuser_tab;
           

            echo '<div class="row">';
            echo ' <div class="col-md-12 tab_response_message" id="tab_response_message"></div>';
            echo '<ul class="tab_list sortable" id="tab_list">';
        if (!empty($user_tab)) {
            $wpuser_tab = unserialize($user_tab);

            $wpuser_tab = SELF::array_sort($wpuser_tab, 'tab_sort_order_index', SORT_ASC);
            foreach ($wpuser_tab as $tab) {
                    echo wpuserTab::buildTabHtml($tab);
            }

        }
        echo '</ul>';
        echo '</div>';


        echo '<label id="wp_user_profile_add_tab" class="wpuser_button wp_user_profile_add_tab btn btn-primary wpuser-custom-button">';
        _e('Add Tab', 'wpuser');
        echo '</label><br>';
            echo ' 
    <div id="wp_user_add_tab_div" style="display:none;">
    <div style="display:none;" id="wp_user_tab_div">
    </div>';
           echo '<form  id="wp_user_tab_field_form" class="form-horizontal" name="wp_user_tab_field_form">
            <input name="wpuser_update_setting" type="hidden" value="' . wp_create_nonce("wpuser-update-setting") . '"/>
                       <input name="update_tab_id" id="update_tab_id" type="hidden" value=""/> 
                          <div class="row">';

            foreach ($array['fields'] as $key => $value) {
                wpuserTabSetting::createFormFields($key,$value);
            }

            ?> </div>
            <div class="form-group">
            <div class="">
            <?php
            echo '<label id="wp_user_profile_close_tab" style="display:none;" class="wpuser_button btn  btn-default wpuser-custom-button">';
            _e('Close', 'wpuser');
            echo '</label>  ';
            echo '<label id="wp_user_profile_tab_submit" class="wpuser_button btn  btn-primary wpuser-custom-button">';
            _e('Save', 'wpuser');
            echo '</label>';
            echo '</label>  ';
            echo '<label id="wp_user_profile_tab_update" style="display:none" class="wpuser_button btn  btn-primary wpuser-custom-button">';
            _e('Update', 'wpuser');
            echo '</label>';
            echo '</div>
                  </div>
                </form>
                </div>';
        ?>
        <script>
            var $ = jQuery.noConflict();
            $("#wp_user_profile_tab_submit").click(function () {
                $.ajax({
                    type: "post",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_add_tab',
                    data: $("#wp_user_tab_field_form").serialize(),
                    success: function (data) {
                        var parsed = $.parseJSON(data);
                        $("#wp_user_tab_div").html('<div class="alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + parsed.message + '</div>');
                        if (parsed.status == 'success') {                          
                            $("#wp_user_tab_field_form")[0].reset();
                            $('#tab_list').append(parsed.html);
                        }
                        $('#wp_user_tab_div').show();
                    },
                });
            });

            $("#wp_user_profile_tab_update").click(function () {
                $.ajax({
                    type: "post",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_update_tabs',
                    data: $("#wp_user_tab_field_form").serialize() + '&form_action=edit',
                    success: function (data) {
                        var parsed = $.parseJSON(data);
                        $("#tab_response_message").html('<div class="alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + parsed.message + '</div>');
                        if (parsed.status == 'success') {
                            $("#wp_user_tab_field_form")[0].reset();
                            $("#wp_user_profile_tab_update").css("display", "none");
                            $("#wp_user_add_tab_div").css("display", "none");
                            $("#wp_user_profile_tab_submit").css("display", "inline-block");
                            $("#tab_list").css("display", "block");
                            $("#wp_user_profile_close_tab").css("display", "inline-block");
                            $("#wp_user_profile_add_tab").css("display", "inline-block");
                            $(".wp_user_profile_woo_tab").css("display", "block");
                            $('#box_' + parsed.update_tab_id).replaceWith(parsed.html);
                        }
                        $('#wp_user_tab_div').show();
                    },
                });
            });

            $("#wp_user_profile_add_tab").click(function () {
                $('#wp_user_add_tab_div').show();
                $('#wp_user_profile_close_tab').show();
                $('#wp_user_profile_add_tab').hide();
            });
            $("#wp_user_profile_close_tab").click(function () {
                $('#wp_user_add_tab_div').hide();
                $('#wp_user_profile_add_tab').show();
                $('#wp_user_profile_close_tab').hide();
            });

            function tab_action(type, label, action) {
                if (action == 'delete') {
                    var r = confirm("<?php _e('Are you sure want to delete?', 'wpuser') ?>");
                    if (r == true) {
                        tabAction(type, label, action);
                    }
                } else {
                    tabAction(type, label, action);
                }
            }

            function tabAction(tab_id, label, action) {
                var wpuser_update_setting = '<?php echo wp_create_nonce('wpuser-update-setting')?>';
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_tab_action',
                    data: 'tab_id=' + tab_id + '&tab_action=' + action + '&tab_title=' + label + '&wpuser_update_setting=' + wpuser_update_setting,
                    success: function (response) {
                        if (action != 'edit') {
                            jQuery("#tab_response_message").html('<div class="alert alert-' + response.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + response.message + '</div>');
                        }
                        if (response.status == 'success') {
                            if (action == 'delete') {
                                $('#box_' + tab_id + ' div').remove();
                            } else if (action == 'duplicate') {
                                // var parsed = $.parseJSON(response);
                                $('#tab_list').append(response.html);
                            }else if (action == 'show') {
                                     $('#body_' + tab_id).removeClass('box-default').addClass('box-success');
                                     $('#iconvisible_'+tab_id).html(response.html);
                            }
                            else if (action == 'hide') {
                                  $('#body_' + tab_id).removeClass('box-success').addClass('box-default');
                                  $('#iconvisible_'+tab_id).html(response.html);
                            } else if (action == 'edit') {
                                $("#wp_user_profile_tab_update").css("display", "inline-block");
                                $("#wp_user_add_tab_div").css("display", "block");
                                $("#wp_user_profile_tab_submit").css("display", "none");
                                $("#tab_list").css("display", "none");
                                $("#wp_user_profile_close_tab").css("display", "none");
                                $("#wp_user_profile_add_tab").css("display", "none");
                                $(".wp_user_profile_woo_tab").css("display", "none");
                                $("#wpuser_tab_type").val(response.data.tab_title);
                                $('#update_tab_id').val(response.data.tab_id);
                                $.each(response.data, function (i, val) {
                                    $('#' + i).val(val);
                                });
                                if (!(typeof response.data.is_link === 'undefined')) {
                                    if (response.data.is_link == 'on') {
                                        $('#is_link').prop('checked', true);
                                        //$('#is_link').attr('checked');
                                    }
                                }
                                if (response.data.tab_visible_role.length != 0) {
                                    $.each(response.data.tab_visible_role, function (i, val) {
                                        $('#tab_visible_role' + val.replace(" ", "_")).prop('checked', true);
                                    });
                                }

                                if (response.data.tab_visible_role_edit_level.length != 0) {
                                    $.each(response.data.tab_visible_role_edit_level, function (i, val) {
                                        $('#tab_visible_role_edit_level' + val.replace(" ", "_")).prop('checked', true);
                                    });
                                }

                                if (response.data.tab_visible_role_view.length != 0) {
                                    $.each(response.data.tab_visible_role_view, function (i, val) {
                                        $('#tab_visible_role_view' + val.replace(" ", "_")).prop('checked', true);
                                    });
                                }

                                if (response.data.tab_visible_role_view_level.length != 0) {
                                    $.each(response.data.tab_visible_role_view_level, function (i, val) {
                                        $('#tab_visible_role_view_level' + val.replace(" ", "_")).prop('checked', true);
                                    });
                                }
                            }
                        }
                    }
                })
            }

            function tabSortAction(productOrder) {
                var wpuser_update_setting = '<?php echo wp_create_nonce('wpuser-update-setting')?>';
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_tab_sort_action',
                    data: 'tab_ids=' + productOrder +'&wpuser_update_setting=' + wpuser_update_setting,
                    success: function (response) {
                    }
                })
            }

            $(function() {
                $('.sortable').sortable({
                    update: function(event, ui) {
                        var productOrder = $(this).sortable('toArray').toString();
                        tabSortAction(productOrder);
                    }
                });
            });
        </script>
        <?php

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

}