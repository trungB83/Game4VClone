<?php

if (!class_exists('wpuserAjaxgroups')) :

    class wpuserAjaxgroups
    {

        public function __construct()
        {
            add_action('wp_ajax_wpuser_add_group', array($this, 'wpuser_add_group'));
            add_action('wp_ajax_wpuser_update_group', array($this, 'wpuser_update_group'));
            add_action('wp_ajax_wpuser_listgroups', array($this, 'wpuser_listgroups'));
            add_action('wp_ajax_wpuser_group_action', array($this, 'wpuser_group_action'));
            add_action('wp_ajax_wpuser_getLocation', array($this, 'wpuser_getLocation'));
            add_action('wp_ajax_wpuser_getMemberByGroupID', array($this, 'wpuser_getMemberByGroupID'));
            add_action('wp_ajax_wpuser_getGrouprList', array($this, 'wpuser_getGrouprList'));
            add_action('wp_ajax_wpuser_getGroupFilterData', array($this, 'wpuser_getGroupFilterData'));
            add_action('wp_ajax_wpuser_getGroupTitleSearch', array($this, 'wpuser_getGroupTitleSearch'));

            add_action('wp_ajax_nopriv_wpuser_group_action', array($this, 'wpuser_group_action'));
            add_action('wp_ajax_nopriv_wpuser_getMemberByGroupID', array($this, 'wpuser_getMemberByGroupID'));
            add_action('wp_ajax_nopriv_wpuser_getGrouprList', array($this, 'wpuser_getGrouprList'));
            add_action('wp_ajax_nopriv_wpuser_getGroupFilterData', array($this, 'wpuser_getGroupFilterData'));
            add_action('wp_ajax_nopriv_wpuser_getGroupTitleSearch', array($this, 'wpuser_getGroupTitleSearch'));


        }


        function wpuser_add_group()
        {
            $result = array();
            $user_id = get_current_user_id();

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

            if (get_option('wp_user_disable_group_myprofile') == 1) {
                $result['message'] = __('Disable create new group', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            //Check based on name,category and area
          /*  if (array_key_exists($wpuser_group_type, $wpuser_group)) {
                $result['message'] = __("group Type alredy exist. Please enter diffrent type or enter as $wpuser_type 1", 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
          */

            if ($user_id) {
                if(empty($_POST['wpuser']['title'])){
                    $result['message'] = __('Please enter group title', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }
                $wpuser_group = $_POST['wpuser'];
                $wpuser_group['created_by'] = $user_id;

                  //print_r($wpuser_group[$wpuser_group_type]);die;
                global $wpdb;
                $wpdb->insert($wpdb->prefix . 'wpuser_groups', $wpuser_group);


                $wpuser_group_meta['group_id'] = $group_id= $wpdb->insert_id;
                $wpuser_group_meta['meta_key'] = 'admin';
                $wpuser_group_meta['meta_value'] = $user_id;

                $wpdb->insert($wpdb->prefix . 'wpuser_group_meta', $wpuser_group_meta);

                $result['message'] = __('Group created successfully', 'wpuser');
                $result['status'] = 'success';

                $wpuser_group = $_POST['wpuser'];
                $wpuser_group = array_merge($wpuser_group,$wpuser_group_meta);
                $wpuser_group['is_admin']=1;

                $q = "SELECT count(id) as member_count from {$wpdb->prefix}wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=$group_id";
                $user_group = $wpdb->get_results($q,ARRAY_A);
                if (!empty($user_group)) {
                    $wpuser_group['member_count'] = $user_group[0]['member_count'];
                }
                $result['html'] = SELF::buildgroupHtml($wpuser_group);
                print_r(json_encode($result));
                die;
            }

            $result['message'] = __('Please Refresh Page', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die;


        }

        function wpuser_update_group()
        {
            $result = array();
            $user_id = get_current_user_id();

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

            if (!((isset($_POST['update_group_id']) && !empty($_POST['update_group_id'])))) {
                $result['message'] = __('Invalid form data.', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
            $group_id = $_POST['update_group_id'];

            if ($user_id ) {
                $wpuser_group = $_POST['wpuser'];
                $result['message'] = __('Group Updated successfully', 'wpuser');
                $result['status'] = 'success';
                $result['update_group_id'] = $group_id;
                global $wpdb;
                $wpdb->update($wpdb->prefix.'wpuser_groups', $wpuser_group, array('id' => $group_id));
                $wpuser_group['group_id']=$group_id;
                $wpuser_group['meta_key'] = 'admin';
                $wpuser_group['meta_value'] = $user_id;
                $wpuser_group['is_admin']=1;
                $q = "SELECT count(id) as member_count from {$wpdb->prefix}wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=$group_id";
                $user_group = $wpdb->get_results($q,ARRAY_A);
                if (!empty($user_group)) {
                    $wpuser_group['member_count'] = $user_group[0]['member_count'];
                }
                $result['html'] = SELF::buildgroupHtml($wpuser_group);
                print_r(json_encode($result));
                die;
            }

            $result['message'] = __('Please Refresh Page', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die;


        }

        function wpuser_listgroups()
        {
            $result = array();
            $user_id = get_current_user_id();

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
                $wpuser_group_type = strtolower(str_replace(' ', '_', $_POST['type']));
                $user_group = get_user_meta($user_id, 'wpuser_group', true);
                if (!empty($user_group)) {
                    $group_key='billing';
                    $wpuser_group = unserialize($user_group);
                    //print_r($wpuser_group[$wpuser_group_type]);die;
                    if(isset($_POST['group_type']) && $_POST['group_type']=='shipping') {
                        $wpuser_shipping_group = array();
                        $group_key='shipping';
                        foreach ($wpuser_group[$wpuser_group_type] as $meta_key => $meta_value) {
                            $shippin_meta_key = preg_replace('/billing/', 'shipping', $meta_key);
                            $wpuser_shipping_group[$shippin_meta_key] = $meta_value;
                        }
                        $wpuser_group[$wpuser_group_type]=$wpuser_shipping_group;
                    }
                    if (class_exists('WC_Countries')) {
                    $countries = new WC_Countries();
                    if ($states = $countries->get_states()) {
                        foreach ($states as $states) {
                            if (!empty($states)) {
                                foreach ($states as $key => $val) {
                                    if ($wpuser_group[$wpuser_group_type][$group_key . '_state'] == $val) {
                                        $wpuser_group[$wpuser_group_type][$group_key . '_state'] = $key;
                                    }
                                }
                            }
                        }
                    }

                    }
                    $result['status'] = 'success';
                    $result['data'] = $wpuser_group[$wpuser_group_type];
                    print_r(json_encode($result));
                    die;
                }
            }

            $result['message'] = __('Please Refresh Page', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die;


        }

        function wpuser_group_action()
        {
            $result = array();

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



            if (!((isset($_POST['id']) && !empty($_POST['id'])))) {
                $result['message'] = __('Invalid action', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
            global $wpdb;
            $group_id=$_POST['id'];
            $user_id = get_current_user_id();

            if ($_POST['group_action'] == 'view') {
                $result['status'] = 'success';
                $result['html'] = SELF::viewgroupHtml($group_id);
                print_r(json_encode($result));
                die;
            }

            $q = "SELECT id  FROM {$wpdb->prefix}wpuser_group_meta g WHERE group_id=$group_id AND meta_key='admin' AND meta_value='$user_id'";
            $user_group = $wpdb->get_results($q,ARRAY_A);
            if (!empty($user_group)) {
                if ($_POST['group_action'] == 'delete') {
                    $wpdb->delete( $wpdb->prefix . 'wpuser_groups', array( 'id' => $group_id) );
                    $wpdb->delete( $wpdb->prefix . 'wpuser_group_meta', array( 'group_id' => $group_id) );
                    $result['message'] = __('Group has been deleted successfully', 'wpuser');
                    $result['status'] = 'success';
                    print_r(json_encode($result));
                    die;
                }

                else if ($_POST['group_action'] == 'edit') {
                    $q = "SELECT g.id as group_id,g.title,g.category,g.icon,g.created_by FROM {$wpdb->prefix}wpuser_groups g WHERE id=$group_id";
                    $user_group = $wpdb->get_results($q,ARRAY_A);
                    if (!empty($user_group)) {
                        $result['message'] = __('Update your group', 'wpuser');
                        $result['status'] = 'success';
                        $result['data'] = $user_group[0];
                        $result['update_group_id'] = $group_id;
                        print_r(json_encode($result));
                        die;
                    }
                }


            }else{

                if ($_POST['group_action'] == 'join') {
                    $wpuser_group_meta['group_id'] =$group_id;
                    $wpuser_group_meta['meta_key'] = 'member';
                    $wpuser_group_meta['meta_value'] = $user_id;
                    $wpdb->insert($wpdb->prefix . 'wpuser_group_meta', $wpuser_group_meta);
                    $result['message'] = __('You have joined group successfully', 'wpuser');
                    $result['status'] = 'success';
                    $result['group_id'] = $group_id;
                    $result['html'] = ' <button type="button" class="btn btn-default" onclick="group_action(\'' . $group_id . '\',\'leave\')">Leave Group</button>';
                    print_r(json_encode($result));
                    die;
                }

                else if ($_POST['group_action'] == 'leave') {
                    $wpuser_group_meta['group_id'] =$group_id;
                    $wpuser_group_meta['meta_key'] = 'member';
                    $wpuser_group_meta['meta_value'] = $user_id;
                    $wpdb->delete( $wpdb->prefix . 'wpuser_group_meta', $wpuser_group_meta );
                    $result['message'] = __('You have leaved group successfully', 'wpuser');
                    $result['status'] = 'success';
                    $result['group_id'] = $group_id;
                    $result['html'] = ' <button type="button" class="btn btn-primary" onclick="group_action(\'' . $group_id . '\',\'join\')">Join</button>';
                    print_r(json_encode($result));
                    die;
                }

                $result['message'] = __('Invalid Access', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                die;
            }

            $result['message'] = __('Please Refresh Page', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die;


        }

        function wpuser_getGrouprList()
        {

            $result = array();
            $condition =' 1=1 ';
            $limit ='';
            global $wpdb;
            $user_id = get_current_user_id();
            $page = ((isset($_POST['page']) && !empty($_POST['page']))) ? $_POST['page'] : 1;
            $per_page = ((isset($_POST['per_page']) && !empty($_POST['per_page']))) ? $_POST['per_page'] : 10;

            if(isset($_POST['wpuser_filter_category']) && !empty($_POST['wpuser_filter_category']) && $_POST['wpuser_filter_category']!='null'){
                $wpuser_filter_category=sanitize_text_field($_POST['wpuser_filter_category']);
                $condition .=" AND g.category in ('".implode("','",explode(',',$wpuser_filter_category))."') ";
            }

            if(isset($_POST['wpuser_filter_area']) && !empty($_POST['wpuser_filter_area']) && $_POST['wpuser_filter_area']!='null'){
                $wpuser_filter_area=sanitize_text_field($_POST['wpuser_filter_area']);
                $condition .=" AND g.area in ('".implode("','",explode(',',$wpuser_filter_area))."') ";
            }

            if(isset($_POST['wpuser_filter_by_user']) && !empty($_POST['wpuser_filter_by_user']) && $_POST['wpuser_filter_by_user']!='null' && $_POST['wpuser_filter_by_user']!=0) {
                $user_id_group = sanitize_text_field($_POST['wpuser_filter_by_user']);
                $condition .= " AND g.id IN (SELECT group_id from {$wpdb->prefix}wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND meta_value='$user_id_group') ";
                if ($user_id != $user_id_group) {
                    if (class_exists('wpuserAjaxSocial') && is_user_logged_in() && !empty(wpuserAjaxSocial::countFollow($user_id, $user_id_group))) {
                        $condition .= " AND (g.visibility = 'public' OR g.visibility = 'closed')";
                    } else {
                        $condition .= " AND g.visibility = 'public'";
                    }
                }
            }

            if(isset($_POST['wpuser_filter_search']) && !empty($_POST['wpuser_filter_search'])){
                $wpuser_filter_search=sanitize_text_field($_POST['wpuser_filter_search']);
                $condition .=' AND ( g.title like \'%'.$wpuser_filter_search.'%\'
                 OR g.tags like \'%'.$wpuser_filter_search.'%\'
                 OR g.area like \'%'.$wpuser_filter_search.'%\'
                 OR g.category like \'%'.$wpuser_filter_search.'%\'
                 OR g.description like \'%'.$wpuser_filter_search.'%\')';
            }

            //$condition .=" AND (g.visibility='public') ";

                // building limit query string
                $offset = (($page - 1) * $per_page);
                $limit = "LIMIT " . $per_page . " OFFSET " . $offset . " ";



            $q = "SELECT SQL_CALC_FOUND_ROWS g.id,g.title,g.category,g.icon, 
                                      (SELECT count(id) from {$wpdb->prefix}wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=g.id) as member_count, 
                                      (SELECT count(id) from wp_wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=g.id AND meta_value='$user_id') as is_member,
                                      (SELECT count(id) from wp_wpuser_group_meta WHERE (meta_key='admin') AND group_id=g.id AND meta_value='$user_id') as is_admin
                                     FROM {$wpdb->prefix}wpuser_groups g WHERE $condition  ORDER BY g.title DESC $limit";
            $user_group = $wpdb->get_results($q,ARRAY_A);


            $total_count = $wpdb->get_var( "SELECT FOUND_ROWS();" );
            // $total_count=count($user_group);

            //$total_count = 14;//(isset($user_group[0]) && isset($user_group[0]['total_count'])) ? $user_group[0]['total_count'] : 0;
            $total_pages= ($total_count > 0) ? ceil($total_count/$per_page): 0;


            $pagination=array(
                'page'=>(int)$page,
                'per_page'=>$per_page,
                'total_count'=>$total_count,
                'total_pages'=>$total_pages
            );

            $result['list'] = $user_group;
            $result['pagination'] = $pagination;
            $result['status'] = 'success';
            
            print_r(json_encode($result));
            die;
        }

        function wpuser_getGroupFilterData()
        {

            $result = array();
            $condition =' 1=1 ';
            global $wpdb;            
            $q = "SELECT 
                      g.id,g.category,g.area                  
                  FROM 
                    {$wpdb->prefix}wpuser_groups g 
                  WHERE 
                      $condition";
            $user_group = $wpdb->get_results($q,ARRAY_A);
            $result['category'] =  array_filter(array_unique(array_column($user_group, 'category'),SORT_STRING));
            $result['area'] =  array_filter(array_unique(array_column($user_group, 'area'),SORT_STRING));
            $result['status'] = 'success';
            print_r(json_encode($result));
            die;
        }

        function wpuser_getGroupTitleSearch()
        {

            $result = array();
            $condition =' 1=1 ';

            if(isset($_POST['wpuser_filter_search']) && !empty($_POST['wpuser_filter_search'])){
                $wpuser_filter_search=sanitize_text_field($_POST['wpuser_filter_search']);
                $condition .=' AND ( g.title like \'%'.$wpuser_filter_search.'%\')';
            }

            global $wpdb;
            $q = "SELECT 
                      DISTINCT g.title                 
                  FROM 
                    {$wpdb->prefix}wpuser_groups g 
                  WHERE 
                      $condition";
            $user_group = $wpdb->get_results($q,ARRAY_A);
            $result['list'] =  $user_group;
            $result['status'] = 'success';
            print_r(json_encode($result));
            die;
        }

        function wpuser_getMemberByGroupID()
        {

            $result = array();

            if (!((isset($_POST['id']) && !empty($_POST['id'])))) {
                $result['message'] = __('Invalid request', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            $id=$_POST['id'];
            $page = ((isset($_POST['page']) && !empty($_POST['page']))) ? $_POST['page'] : 1;
            $per_page = ((isset($_POST['per_page']) && !empty($_POST['per_page']))) ? $_POST['per_page'] : 10;
            $result=SELF::wpuser_getMemberListByGroupID($id,$page,$per_page);
            print_r(json_encode($result));
            die;
        }

        public static function wpuser_getMemberListByGroupID($group_id,$page,$per_page)
        {
            $result = array();
            $user_count=0;
            global $wpdb;
            $include = array();
            $memberList = array();
            $group_members=array();

            $q = "SELECT u.id,(CASE WHEN gm.meta_key='admin' THEN 1 ELSE 0 END ) AS is_admin
                    FROM {$wpdb->prefix}wpuser_group_meta gm,wp_users u
                            WHERE gm.meta_value=u.id AND (gm.meta_key='admin' OR  gm.meta_key='member') AND group_id=$group_id";
            $member_list = $wpdb->get_results($q, ARRAY_A);
            if (!empty($member_list)) {
                $include = array_column($member_list, 'id');
                $group_members = array_column($member_list, null, 'id');
            }

            if (!empty($include)) {

                $role__in = (isset($atts['role_in']) && !empty($atts['role_in'])) ? explode(',', $atts['role_in']) : array();
                $role__not_in = (isset($atts['role_not_in']) && !empty($atts['role_not_in'])) ? explode(',', $atts['role_not_in']) : array();

                $exclude = (isset($atts['exclude']) && !empty($atts['exclude'])) ? explode(',', $atts['exclude']) : array();
                $meta_key = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 'wp-approve-user' : '';
                $meta_value = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 1 : '';

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
                    'paged' => $page,
                    'number' => $per_page,
                    'count_total' => true,
                    'fields' => 'all',
                );
                $blogusers = get_users($args);
                $user_query = new WP_User_Query($args);
                $user_count=$user_query->get_total();

                foreach ($blogusers as $value) {
                    $title = (get_user_meta($value->ID, 'user_title', true));
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
                    $wp_user_page = get_option('wp_user_page');
                    $genre_url = !empty($wp_user_page) ? add_query_arg('user_id', $value->ID, get_permalink($wp_user_page)) : '#';
                    $memberList[] = array(
                        'id' => $value->ID,
                        'name' => $name,
                        'profile_image' => $wp_user_profile_img,
                        'title' => $title,
                        'profile_url'=>$genre_url,
                        'is_admin' => (isset($group_members[$value->ID]['is_admin'])) ? $group_members[$value->ID]['is_admin'] : 0,
                    );
                }
            }
            $total_pages= ($user_count>0) ? ceil($user_count/$per_page): 0;


            $pagination=array(
                'page'=>$page,
                'per_page'=>$per_page,
                'total_count'=>$user_count,
                'total_pages'=>$total_pages
            );

            $result['list'] = $memberList;
            $result['pagination'] = $pagination;
            $result['status'] = 'success';
            $result['group_id'] = $group_id;
            return $result;
        }


        public static function viewgroupHtml($group_id){
            global $wpdb;
            $user_id = get_current_user_id();
            $html='';
            $mebers_ids=array();
            $q = "SELECT g.id as group_id,g.title,g.category,g.icon, g.visibility,g.description,
                                      (SELECT count(id) from wp_wpuser_group_meta WHERE (meta_key='admin' OR meta_key='member') AND group_id=g.id AND meta_value='$user_id') as is_member,
                                      (SELECT count(id) from wp_wpuser_group_meta WHERE (meta_key='admin') AND group_id=g.id AND meta_value='$user_id') as is_admin
                                     FROM {$wpdb->prefix}wpuser_groups g WHERE id=$group_id";
            $user_group = $wpdb->get_results($q,ARRAY_A);
            if (!empty($user_group)) {
                $result['status'] = 'success';
                $q = "SELECT u.id,u.display_name,
                             (CASE WHEN gm.meta_key='admin' THEN 1 ELSE 0 END ) AS is_admin
                            FROM wp_wpuser_group_meta gm,wp_users u
                            WHERE gm.meta_value=u.id AND (gm.meta_key='admin' OR  gm.meta_key='member') AND group_id=$group_id";
                $member_list = $wpdb->get_results($q, ARRAY_A);
                if (!empty($member_list)) {
                    $wpuser_group['member_list'] = $member_list;
                    $mebers_ids = array_column($member_list, 'id');
                }
                $group = array_merge($user_group[0], $wpuser_group);

                $html .= '<div class="col-md-3">
        <!-- Profile Image -->
        <div class="box box-primary wpuser-custom-box">
            <div class="box-body box-profile">
                <div class="text-center"><span class="fa-5x ' . $group['icon'] . '"></span></div>
                <h3 class="profile-username text-center wpuser_profile_name">' . $group['title'] . '</h3>
                <p class="text-muted text-center"><i class="fa fa-unlock"></i> ' . ucfirst($group['visibility']) . ' Group</p>                          
           </div>
            <div class="box-footer text-center">';
                if(is_user_logged_in()) {
                    if (isset($group['is_admin']) && $group['is_admin'] == 1) {
                        //   $html .= '<button type = "button" class="btn btn-warning" onclick="group_action(\'' . $group_id . '\',\'delete\')"> Delete Group</button >';
                        //   $html .= '<a class="pull-right" title="Edit" onclick="group_action(\'' . $group_id . '\',\'edit\')"><i class="fa fa-fw fa-gear"></i></a>';
                    } else if (isset($group['is_admin']) && $group['is_admin'] != 1 && isset($group['is_member']) && !empty($group['is_member'])) {
                        $html .= '<span class="group_join_' . $group_id . '" id="group_join_' . $group_id . '"><button type="button" class="btn btn-default" onclick="group_action(\'' . $group_id . '\',\'leave\')">Leave Group</button></span>';
                    } else if (isset($group['is_request']) && $group['is_request'] == 1) {
                        $html .= '<span class="group_join_' . $group_id . '" id="group_join_' . $group_id . '"><button type = "button" class="btn btn-primary" onclick="group_action(\'' . $group_id . '\',\'join\')" > Join</button ></span>';
                        $html .= '<span class="group_join_' . $group_id . '" id="group_decline_' . $group_id . '"><button type="button" class="btn btn-default" onclick="group_action(\'' . $group_id . '\',\'decline\')">Decline</button></span>';
                    } else {
                        $html .= '<span class="group_join_' . $group_id . '" id="group_join_' . $group_id . '"><button type = "button" class="btn btn-primary" onclick="group_action(\'' . $group_id . '\',\'join\')"> Join</button ></span>';
                    }
                }
                $html .= '</div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        
        
        <!-- About Me Box -->
        <div class="box box-default wpuser-custom-box">
            <ul class="nav">
                 <li class=""><a class="" href="#group_about" data-toggle="tab">About</a></li> 
                 <li class=""><a class="" href="#group_members" data-toggle="tab" onclick="getMemberListByGroupID(\'' . $group_id . '\',\'1\')">Members</a></li>                   
                 <li class=""><a class="" onclick="backTo(\'groups\')" data-toggle="tab">Groups</a></li>  
                 <li class=""><button onclick="backTo(\'myprofile\')" type="button" class="btn btn-block btn-default"><i class="fa fa-fw fa-arrow-left"></i></button></li>  
         </ul>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        
        
    </div>';

                $html .= '
                <div class="col-md-9" id="group_info">
                <div class="nav-tabs-custom">
                <div class="tab-content">
                <div class="tab-pane active" id="group_about">  
                <h3>About This Group</h3>'
                    . $group['description'];
                if (!empty($group['area'])) {
                    $html .= '</br>' . __('Area', 'wpuser') . ' : <a>' . $group['area'] . '</a>';
                }
                if (!empty($group['category'])) {
                    $html .= '</br>' . __('Category', 'wpuser') . ' : <a>' . $group['category'] . '</a>';
                }
                if (!empty($group['tags'])) {
                    $html .= '</br>' . __('Tags', 'wpuser') . ' : <a>' . $group['tags'] . '</a>';
                }

                $html .='</div>                
                
                <div class="tab-pane" id="group_members">   
                    <h4 class="title" id="gropupLabel"></h4>
                 <div class="row">
                        <div class="col-md-12" id="group_members_list">

                        </div>
                 </div>
                 <div class="row">
                     <div class="col-md-12">
                        <ul id="group_members_pagination" class="pagination pagination-sm">
                        </ul>
                      </div>
                  </div>';
               $html .=' </div>
             </div>
            </div>
            </div>';
            }
           return $html;
        }

        public static function buildgroupHtml($group)
        {           
            $html = '';
                $group_id = $group['group_id'];
             if(!empty($group_id)) {
                $group_label = (isset($group['title']) && !empty($group['title'])) ? $group['title'] : '';

                $html .= '<div id="group_' . $group_id . '" class="col-lg-6 col-xs-6 group_' . $group_id . '">
                          <!-- small box -->
                          <div class="small-box bg-gray">
                            <div class="inner">';

                 $html .= '<label><a class="pull-right" href="#" title="View '. $group_label .'" onclick="group_action(\'' . $group_id . '\',\'view\')">'. $group_label .'</a></label>';
                // if(isset($group['is_admin']) && $group['is_admin']==1) {
                  //   $html .= '<span class="" onclick="group_action(\'' . $group_id . '\',\'edit\')"><i class="fa fa-fw fa-gear"></i></span>';
                // }
                if(isset($group['member_count'])) {
                            $html .= '<p id="group_count"><label id="member_count'.$group_id.'" class="member_count'.$group_id.'">'.$group['member_count'].'</label> members</p>';
                        }
                $html .= '</div>
                            <div class="icon">
                              <i class="' . $group['icon'] . '"></i>
                            </div>
                            <p class="small-box-footer">';
                 if(is_user_logged_in()) {
                     if (isset($group['is_admin']) && $group['is_admin'] == 1) {
                         $html .= '<button type = "button" class="btn btn-warning" onclick="group_action(\'' . $group_id . '\',\'delete\')"> Delete Group</button >';
                     } else if (isset($group['is_admin']) && $group['is_admin'] != 1 && isset($group['is_member']) && !empty($group['is_member'])) {
                         $html .= '<span class="group_join_' . $group_id . '" id="group_join_' . $group_id . '"><button type="button" class="btn btn-default" onclick="group_action(\'' . $group_id . '\',\'leave\')">Leave Group</button></span>';
                     } else if (isset($group['is_request']) && $group['is_request'] == 1) {
                         $html .= '<span class="group_join_' . $group_id . '" id="group_join_' . $group_id . '"><button type = "button" class="btn btn-primary" onclick="group_action(\'' . $group_id . '\',\'join\')" > Join</button ></span>';
                         $html .= '<span id="group_decline_' . $group_id . '"><button type="button" class="btn btn-default" onclick="group_action(\'' . $group_id . '\',\'decline\')">Decline</button></span>';
                     } else {
                         $html .= '<span class="group_join_' . $group_id . '" id="group_join_' . $group_id . '"><button type = "button" class="btn btn-primary" onclick="group_action(\'' . $group_id . '\',\'join\')"> Join</button ></span>';
                     }
                 }

                if(isset($group['is_admin']) && $group['is_admin']==1) {
                    $html .= '<a class="pull-right" title="Edit" onclick="group_action(\'' . $group_id . '\',\'edit\')"><i class="fa fa-fw fa-gear"></i></a>';
                }
                     $html .= '</p>
                          </div>
                        </div>';
            }
            return $html;

        }

        public static function MakeBillinggroup($user_id, $wpuser_type, $label = '', $type = 'both')
        {
            try {
                $wpuser_group_type = strtolower(str_replace(' ', '_', $wpuser_type));
                $wpuser_group = array();
                $user_group = get_user_meta($user_id, 'wpuser_group', true);
                if (!empty($user_group)) {
                    $wpuser_group = unserialize($user_group);
                    foreach ($wpuser_group as $key => $addres) {
                        if ($type == 'both' || $type == 'billing') {
                            $wpuser_group[$key]['is_billing_group'] = ($key != $wpuser_group_type) ? 0 : 1;
                        }
                        if ($type == 'both' || $type == 'shipping') {
                            $wpuser_group[$key]['is_shipping_group'] = ($key != $wpuser_group_type) ? 0 : 1;
                        }

                        if (($type == 'both' || $type == 'billing') && $key == $wpuser_group_type) {
                            $billing_shipping_group = $addres;
                            unset($billing_shipping_group['is_billing_group']);
                            unset($billing_shipping_group['is_shipping_group']);
                            unset($billing_shipping_group['type']);
                            unset($billing_shipping_group['label']);
                            foreach ($billing_shipping_group as $meta_key => $meta_value) {
                                update_user_meta($user_id, $meta_key, $meta_value);
                            }
                        }

                        if (($type == 'both' || $type == 'shipping') && $key == $wpuser_group_type) {
                            $billing_shipping_group = $addres;
                            unset($billing_shipping_group['is_billing_group']);
                            unset($billing_shipping_group['is_shipping_group']);
                            unset($billing_shipping_group['type']);
                            unset($billing_shipping_group['label']);
                            foreach ($billing_shipping_group as $meta_key => $meta_value) {
                                $shippin_meta_key = preg_replace('/billing/', 'shipping', $meta_key);
                                update_user_meta($user_id, $shippin_meta_key, $meta_value);
                            }
                        }
                    }

                }
                update_user_meta($user_id, 'wpuser_group', serialize($wpuser_group));

                $result['message'] = __($label . ' set as ' . $type . ' group successfully', 'wpuser');
                $result['status'] = 'success';
            } catch (Exception $e) {
                $result['message'] = __('Error in update group. Please Refresh Page', 'wpuser');
                $result['status'] = 'warning';
            }
            return $result;
        }
    }
endif;

$obj = new wpuserAjaxgroups();