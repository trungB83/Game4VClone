<?php

if (!class_exists('wpuserTab')) :

    class wpuserTab
    {

        public function __construct()
        {
            add_action('wp_ajax_wpuser_add_tab', array($this, 'wpuser_add_tab'));
            add_action('wp_ajax_wpuser_update_tabs', array($this, 'wpuser_update_tabs'));
            add_action('wp_ajax_wpuser_list_tabs', array($this, 'wpuser_list_tabs'));
            add_action('wp_ajax_wpuser_tab_action', array($this, 'wpuser_tab_action'));
            add_action('wp_ajax_wpuser_tab_sort_action', array($this, 'wpuser_tab_sort_action'));
        }


        function wpuser_add_tab()
        {
            $result = array();
            $responce = array(
                'status' => 'warning',
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {
                if (!isset($_POST['wpuser_update_setting'])) {
                    $responce = array(
                        'status' => 'warning',
                        'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                    );
                    print_r(json_encode($responce));
                    die;
                }
                if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                    $responce = array(
                        'status' => 'warning',
                        'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                    );
                    print_r(json_encode($responce));
                    die;
                }

                if (!((isset($_POST['wpuser_tab']['tab_title']) && !empty($_POST['wpuser_tab']['tab_title'])))) {
                    $result['message'] = __('Tab title is required', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }

                $tab_title = $_POST['wpuser_tab']['tab_title'];
                $wpuser_tab_type = strtolower(str_replace(' ', '_', $tab_title));
                $wpuser_tab = array();
                $user_tab = get_option('wpuser_tabs');
                if (!empty($user_tab)) {
                    $wpuser_tab = unserialize($user_tab);
                }

                $tab_sort_order_index= count($wpuser_tab)+1;

                if (array_key_exists($wpuser_tab_type, $wpuser_tab)) {
                    $result['message'] = __("tab Type alredy exist. Please enter diffrent type or enter as $tab_title 1", 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }
                
                    $wpuser_tab[$wpuser_tab_type] = $_POST['wpuser_tab'];
                    $wpuser_tab[$wpuser_tab_type]['tab_id'] = $wpuser_tab_type;
                    $wpuser_tab[$wpuser_tab_type] ['tab_content']=sanitize_text_field($_POST['wpuser_tab']['tab_content']);
                    $wpuser_tab[$wpuser_tab_type]['tab_visibility']='show';
                    $wpuser_tab[$wpuser_tab_type]['is_link'] = isset($_POST['wpuser_tab']['is_link']) ? $_POST['wpuser_tab']['is_link'] : '';
                    $wpuser_tab[$wpuser_tab_type]['tab_sort_order_index']=$tab_sort_order_index;
                    update_option('wpuser_tabs', serialize($wpuser_tab));

                    $result['message'] = __('Tab added successfully', 'wpuser');
                    $result['status'] = 'success';
                    $result['html'] = SELF::buildTabHtml($wpuser_tab[$wpuser_tab_type]);
                    print_r(json_encode($result));
                    die;               
            }
                print_r(json_encode($responce));
                die;

        }

        function wpuser_update_tabs()
        {
            $result = array();
            $responce = array(
                'status' => 'warning',
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {
            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }

            if (!((isset($_POST['update_tab_id']) && !empty($_POST['update_tab_id'])))) {
                $result['message'] = __('Invalid Request', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if (!((isset($_POST['wpuser_tab']['tab_title']) && !empty($_POST['wpuser_tab']['tab_title'])))){
                    $result['message'] = __('Tab title is required', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
            }
            
            $wpuser_tab_type = $_POST['update_tab_id'];
            $wpuser_tab = array();
                $wpuser_tab_data=array();
            $user_tab = get_option('wpuser_tabs');
            if (!empty($user_tab)) {
                $wpuser_tab =$wpuser_tab_data= unserialize($user_tab);
            }
                //print_r(isset($wpuser_tab[$wpuser_tab_type]['tab_visibility']));
                $wpuser_tab[$wpuser_tab_type] = $_POST['wpuser_tab'];
                $wpuser_tab[$wpuser_tab_type] ['tab_content']=sanitize_text_field($_POST['wpuser_tab']['tab_content']);
                $wpuser_tab[$wpuser_tab_type] ['tab_icon']=sanitize_text_field($_POST['wpuser_tab']['tab_icon']);
                $wpuser_tab[$wpuser_tab_type]['tab_id'] = $wpuser_tab_type;
                $wpuser_tab[$wpuser_tab_type]['tab_visibility']=(isset($wpuser_tab_data[$wpuser_tab_type]['tab_visibility'])) ? $wpuser_tab_data[$wpuser_tab_type]['tab_visibility'] : '' ;
                $wpuser_tab[$wpuser_tab_type]['tab_visible_role_edit_level']=(isset($_POST['wpuser_tab']['tab_visible_role_edit_level'])) ? $_POST['wpuser_tab']['tab_visible_role_edit_level'] : '' ;
                $wpuser_tab[$wpuser_tab_type]['tab_visible_role_view']=(isset($_POST['wpuser_tab']['tab_visible_role_view'])) ? $_POST['wpuser_tab']['tab_visible_role_view'] : '' ;
                $wpuser_tab[$wpuser_tab_type]['tab_visible_role']=(isset($_POST['wpuser_tab']['tab_visible_role'])) ? $_POST['wpuser_tab']['tab_visible_role'] : '' ;
                $wpuser_tab[$wpuser_tab_type]['tab_visible_role_view_level']=(isset($_POST['wpuser_tab']['tab_visible_role_view_level'])) ? $_POST['wpuser_tab']['tab_visible_role_view_level'] : '' ;
                $wpuser_tab[$wpuser_tab_type]['is_link']=(isset($_POST['wpuser_tab']['is_link'])) ? 'on' : '' ;
                $wpuser_tab[$wpuser_tab_type]['tab_sort_order_index']=(isset($wpuser_tab_data[$wpuser_tab_type]['tab_sort_order_index'])) ? $wpuser_tab_data[$wpuser_tab_type]['tab_sort_order_index'] : 100 ;
                //print_r($wpuser_tab[$wpuser_tab_type]);die;
                update_option('wpuser_tabs', serialize($wpuser_tab));

                $result['message'] = __('Tab Updated successfully', 'wpuser');
                $result['status'] = 'success';
                $result['update_tab_id'] = $wpuser_tab_type;
                // $result['data']=$_POST['wpuser_tab'];
                $result['html'] = SELF::buildTabHtml($wpuser_tab[$wpuser_tab_type]);
                print_r(json_encode($result));
                die;
            }
            print_r(json_encode($responce));
            die;
        }

        function wpuser_list_tabs()
        {
            $result = array();
            $responce = array(
                'status' => 'warning',
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {
            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }

            if (!((isset($_POST['type']) && !empty($_POST['type'])))) {
                $result['message'] = __('Type field is required', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if ($user_id) {
                $wpuser_tab_type = strtolower(str_replace(' ', '_', $_POST['type']));
                $user_tab = get_user_meta($user_id, 'wpuser_tab', true);
                if (!empty($user_tab)) {
                    $tab_key='billing';
                    $wpuser_tab = unserialize($user_tab);
                    //print_r($wpuser_tab[$wpuser_tab_type]);die;
                    if(isset($_POST['tab_type']) && $_POST['tab_type']=='shipping') {
                        $wpuser_shipping_tab = array();
                        $tab_key='shipping';
                        foreach ($wpuser_tab[$wpuser_tab_type] as $meta_key => $meta_value) {
                            $shippin_meta_key = preg_replace('/billing/', 'shipping', $meta_key);
                            $wpuser_shipping_tab[$shippin_meta_key] = $meta_value;
                        }
                        $wpuser_tab[$wpuser_tab_type]=$wpuser_shipping_tab;
                    }
                    if (class_exists('WC_Countries')) {
                    $countries = new WC_Countries();
                    if ($states = $countries->get_states()) {
                        foreach ($states as $states) {
                            if (!empty($states)) {
                                foreach ($states as $key => $val) {
                                    if ($wpuser_tab[$wpuser_tab_type][$tab_key . '_state'] == $val) {
                                        $wpuser_tab[$wpuser_tab_type][$tab_key . '_state'] = $key;
                                    }
                                }
                            }
                        }
                    }

                    }
                    $result['status'] = 'success';
                    $result['data'] = $wpuser_tab[$wpuser_tab_type];
                    print_r(json_encode($result));
                    die;
                }
            }

            $result['message'] = __('Please Refresh Page', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die;
            }
            print_r(json_encode($responce));
            die;


        }

        function wpuser_tab_action()
        {
            $result = array();
            $responce = array(
                'status' => 'warning',
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {

            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }

            if (!((isset($_POST['tab_action']) && !empty($_POST['tab_action'])))) {
                $result['message'] = __('Invalid action', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if ((((isset($_POST['tab_title']) && !empty($_POST['tab_title'])))) && $_POST['tab_action'] != 'delete') {
                $result['message'] = __('Invalid title', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }


            if ($_POST['tab_action'] == 'delete') {
                $wpuser_tab_type = strtolower(str_replace(' ', '_', $_POST['tab_id']));
                $user_tab = get_option('wpuser_tabs');
                if (!empty($user_tab)) {
                    $wpuser_tab = unserialize($user_tab);
                    unset($wpuser_tab[$wpuser_tab_type]);
                    update_option('wpuser_tabs', serialize($wpuser_tab));
                    $result['message'] = __($_POST['tab_title'] . ' tab has been deleted successfully', 'wpuser');
                    $result['status'] = 'success';
                    print_r(json_encode($result));
                    die;
                }

            }

            if ($_POST['tab_action'] == 'duplicate') {
                $wpuser_tab_type = strtolower(str_replace(' ', '_', $_POST['tab_id']));
                $user_tab = get_option('wpuser_tabs');
                if (!empty($user_tab)) {
                    $wpuser_tab = unserialize($user_tab);
                    $wpuser_tab_new_type = $wpuser_tab_type . '_copy';
                    if (array_key_exists($wpuser_tab_new_type, $wpuser_tab)) {
                        $wpuser_tab_new_type = $wpuser_tab_new_type . '_copy';
                    }
                    $wpuser_tab[$wpuser_tab_new_type] = ($wpuser_tab[$wpuser_tab_type]);
                    $wpuser_tab[$wpuser_tab_new_type]['tab_title'] = $wpuser_tab[$wpuser_tab_type]['tab_title'] . ' Copy';
                    $wpuser_tab[$wpuser_tab_new_type]['tab_id'] = $wpuser_tab_new_type;
                    update_option('wpuser_tabs', serialize($wpuser_tab));
                    $result['message'] = __($_POST['tab_title'] . 'has been duplicate successfully', 'wpuser');
                    $result['status'] = 'success';
                    $result['html'] = SELF::buildTabHtml($wpuser_tab[$wpuser_tab_new_type]);
                    print_r(json_encode($result));
                    die;
                }

            }

            if ($_POST['tab_action'] == 'edit') {
                $wpuser_tab_type = strtolower(str_replace(' ', '_', $_POST['tab_id']));
                $user_tab = get_option('wpuser_tabs');
                if (!empty($user_tab)) {
                    $wpuser_tab = unserialize($user_tab);
                    $result['message'] = __('Update your tab', 'wpuser');
                    $result['status'] = 'success';
                    $wpuser_tab[$wpuser_tab_type]['tab_content']=stripslashes($wpuser_tab[$wpuser_tab_type]['tab_content']);
                    $result['data'] = $wpuser_tab[$wpuser_tab_type];
                    print_r(json_encode($result));
                    die;
                }

            }

                if ($_POST['tab_action'] == 'show' || $_POST['tab_action'] == 'hide') {
                    $tab_visibility = $_POST['tab_action'];
                    $wpuser_tab_type = strtolower(str_replace(' ', '_', $_POST['tab_id']));
                    $result = SELF::tabVisibility($wpuser_tab_type,$tab_visibility );
                    print_r(json_encode($result));
                    die;
                }

            $result['message'] = __('Please Refresh Page', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die;
            }
            print_r(json_encode($responce));
            die;

        }

        function wpuser_tab_sort_action()
        {
            $result = array();
            $responce = array(
                'status' => 'warning',
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {

                if (!isset($_POST['wpuser_update_setting'])) {
                    $responce = array(
                        'status' => 'warning',
                        'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                    );
                    print_r(json_encode($responce));
                    die;
                }
                if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                    $responce = array(
                        'status' => 'warning',
                        'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                    );
                    print_r(json_encode($responce));
                    die;
                }

                if (!((isset($_POST['tab_ids']) && !empty($_POST['tab_ids'])))) {
                    $result['message'] = __('Invalid action', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }

                $tabs=explode(",",$_POST['tab_ids']);

                $wpuser_tab = array();
                $user_tab = get_option('wpuser_tabs');
                if (!empty($user_tab)) {
                    $wpuser_tab = unserialize($user_tab);
                }
                $i=0;
                foreach($tabs as $tab){
                    $index=str_replace("box_","",$tab);
                    $wpuser_tab[$index]['tab_sort_order_index']=$i;
                    $i++;
                }
                // print_r($wpuser_tab);die;
                update_option('wpuser_tabs', serialize($wpuser_tab));

                $result['message'] = __('Please Refresh Page', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                die;
            }
            print_r(json_encode($responce));
            die;

        }


        public static function buildTabHtml($tab)
        {
            $html = '';

            if (isset($tab['tab_id']) && !empty($tab['tab_id'])) {
                $tab_id = $tab['tab_id'];
                $visibility = (isset($tab['tab_visibility'])) ? $tab['tab_visibility'] : 'show';
                if($visibility=='show'){
                    $tab_visibility='hide';
                    $tab_visibility_icon =  'eye-slash';
                    $box_type = 'success';
                    $bg_type='grey';
                }else{
                    $tab_visibility='show';
                    $tab_visibility_icon =  'eye';
                    $box_type = 'default';
                    $bg_type='green';
                }
                $tab_icon = (isset($tab['tab_icon']) && !empty($tab['tab_icon'])) ? $tab['tab_icon'] : 'fa fa-arrows-v';

                $html .= '<li class="col-md-offset-2 col-md-8 col-md-offset-2 list-item" id="box_' . $tab_id . '">
          <div class="action_box box box-solid box-' . $box_type . '" id="body_' . $tab_id . '">
            <div class="box-header with-border">
              <h3 class="box-title"><button class="btn" title="drag tab for change order"> <i class="'.$tab_icon.'"> </i> </button> ';
                $tab_label = (isset($tab['tab_title']) && !empty($tab['tab_title'])) ? $tab['tab_title'] : '';


                $html .= "<label>" . $tab_label . "</label>";

                $html .= '</h3>
                <div class="box-tools pull-right list-item-action ">';
                if((isset($tab['tab_visible_role']) && !empty($tab['tab_visible_role'])) || (isset($tab['tab_visible_role_edit_level']) && !empty($tab['tab_visible_role_edit_level']))){
                    $tab_visible_role_view = (isset($tab['tab_visible_role']) && !empty($tab['tab_visible_role'])) ?  $tab['tab_visible_role']: array();
                    $tab_visible_role_view_level = (isset($tab['tab_visible_role_edit_level']) && !empty($tab['tab_visible_role_edit_level'])) ? $tab['tab_visible_role_edit_level'] : array();
                    $tab_visible = implode(',', array_merge($tab_visible_role_view, $tab_visible_role_view_level) );
                    $html .= '
                <span data-toggle="tooltip" title="' . __('Visible to '.$tab_visible, 'wpuser') . ' -Edit Profile" class="badge bg-aqua" data-original-title="' . __('Visible to '.$tab_visible ,'wpuser').'">
                <i class="fa fa-user"> </i>
                </span>';
                }

                if((isset($tab['tab_visible_role_view']) && !empty($tab['tab_visible_role_view'])) || (isset($tab['tab_visible_role_view_level']) && !empty($tab['tab_visible_role_view_level']))){
                    $tab_visible_role_view = (isset($tab['tab_visible_role_view']) && !empty($tab['tab_visible_role_view'])) ? $tab['tab_visible_role_view']: array();
                    $tab_visible_role_view_level = (isset($tab['tab_visible_role_view_level']) && !empty($tab['tab_visible_role_view_level'])) ? $tab['tab_visible_role_view_level'] : array();
                    $tab_visible = implode(',', array_merge($tab_visible_role_view, $tab_visible_role_view_level) );
                    $html .= '
                <span data-toggle="tooltip" title="' . __('Visible to '.$tab_visible, 'wpuser') . ' -View Profile" class="badge bg-aqua" data-original-title="' . __('Visible to '. $tab_visible, 'wpuser') . '">
                <i class="fa fa-users"> </i>
                </span>';
                }

                if(isset($tab['is_link']) && $tab['is_link']=='on'){
                    $html .= '
                <span data-toggle="tooltip" title="' . __('Link', 'wpuser') . '" class="badge bg-aqua" data-original-title="' . __('Link', 'wpuser') . '">
                <i class="fa fa-link"> </i>
                </span>';
                }

                $html .= '               
                <span data-toggle="tooltip" title="' . __('Delete tab', 'wpuser') . '"
                          onclick="tab_action(\'' . $tab_id . '\',\'' . $tab_label . '\',\'delete\')"
                          class="badge bg-red" data-original-title="' . __('Delete tab', 'wpuser') . '">
                <i class="fa fa-trash"> </i>
                </span>
                     <span data-toggle="tooltip" title="' . __('Duplicate', 'wpuser') . '" onclick="tab_action(\'' . $tab_id . '\',\'' . $tab_label . '\',\'duplicate\')"
                           class=" badge bg-blue" data-original-title="' . __('Duplicate', 'wpuser') . '">
                 <i class="fa  fa-copy"> </i>
                </span>
                 <span data-toggle="tooltip" title="' . __('Edit tab', 'wpuser') . '" onclick="tab_action(\'' . $tab_id . '\',\'' . $tab_label . '\',\'edit\')"
                       class=" badge bg-blue" data-original-title="' . __('Edit tab', 'wpuser') . '">
                 <i class="fa fa-edit"> </i>
                </span>
                <span id="iconvisible_' . $tab_id . '">
                <span data-toggle="tooltip" title="' . __($tab_visibility .' tab', 'wpuser')  . '" id="visible_' . $tab_id . '" onclick="tab_action(\'' . $tab_id . '\',\'' . $tab_label . '\',\'' . $tab_visibility . '\')"
                       class=" badge bg-'.$bg_type.'" data-original-title="' . __($tab_visibility.' tab', 'wpuser')  . '">
                 <i class="fa fa-'.$tab_visibility_icon.'"> </i>    
                  </span>
                  </span>
                </div>

                <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
               ';

                $html .= '
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </li>';
            }
            return $html;

        }

        public static function buildTabIconHtml($tab)
        {

            $html = '';
            if (isset($tab['tab_id']) && !empty($tab['tab_id'])) {
                $tab_id = $tab['tab_id'];
                $tab_label = $tab['tab_title'];
                $visibility = (isset($tab['tab_visibility'])) ? $tab['tab_visibility'] : 'show';
                if($visibility=='show'){
                    $tab_visibility='hide';
                    $tab_visibility_icon =  'eye-slash';
                    $bg_type='grey';
                }else{
                    $tab_visibility='show';
                    $tab_visibility_icon =  'eye';
                    $bg_type='green';
                }

                $html .= ' <span data-toggle="tooltip" title="' . __($tab_visibility .' tab', 'wpuser')  . '" id="visible_' . $tab_id . '" onclick="tab_action(\'' . $tab_id . '\',\'' . $tab_label . '\',\'' . $tab_visibility . '\')"
                       class=" badge bg-'.$bg_type.'" data-original-title="' . __($tab_visibility.' tab', 'wpuser')  . '">
                 <i class="fa fa-'.$tab_visibility_icon.'"> </i>    
                  </span>';
            }
            return $html;
        }

        Public static function tabVisibility($wpuser_tab_type,$tab_visibility )
        {

            $user_tab = get_option('wpuser_tabs');
            $result = array();
            if (!empty($user_tab)) {
                $wpuser_tab = unserialize($user_tab);
                $wpuser_tab_type_data = $wpuser_tab[$wpuser_tab_type];

                $wpuser_tab[$wpuser_tab_type] = $wpuser_tab_type_data;
                $wpuser_tab[$wpuser_tab_type]['tab_visibility'] = $tab_visibility;
                //print_r($wpuser_tab[$wpuser_tab_type]);die;
                update_option('wpuser_tabs', serialize($wpuser_tab));

                $messaage = ($tab_visibility=='hide') ? 'disable' : 'enable';
                $result['message'] = __('Tab has been '.$messaage.' successfully', 'wpuser');
                $result['status'] = 'success';
                $result['html'] = SELF::buildTabIconHtml( $wpuser_tab[$wpuser_tab_type]);

            }
            return $result;
        }

    }
endif;

$obj = new wpuserTab();