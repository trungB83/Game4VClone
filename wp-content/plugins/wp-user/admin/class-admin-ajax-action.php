<?php

if (!class_exists('wpuserAdminAjax')) :

    class wpuserAdminAjax
    {

        public function __construct()
        {
            add_action("wp_ajax_wpuser_update_setting", array($this, 'wpuser_update_setting'));
            add_action("wp_ajax_wpuser_update_page_setting", array($this, 'wpuser_update_page_setting'));
            add_action("wp_ajax_wpuser_bulk_process", array($this, 'wpuser_bulk_process'));
            add_action("wp_ajax_wpuser_clear_log", array($this, 'wpuser_clear_log'));


        }

        public function wpuser_update_setting()
        {
            $responce = array(
                'status' => 0,
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {

                if (isset($_POST) && !empty($_POST)) {
                    //Validate that the contents of the form request came from the current site and not somewhere else added 21-08-15 V.3.4
                    if (!isset($_POST['wpuser_update_setting'])) {
                        $responce = array(
                            'status' => 0,
                            'message' => _('Invalid form data. form request came from the somewhere else not current site!', 'wpuser')
                        );
                        print_r(json_encode($responce));
                        die;
                    }
                    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                        $responce = array(
                            'status' => 0,
                            'message' => _('Invalid form data. form request came from the somewhere else not current site!', 'wpuser')
                        );
                        print_r(json_encode($responce));
                        die;
                    }
                    foreach ($_POST as $key => $value) {

                        if ($key != 'submit') {
                            //  if (!is_array($_POST[$key])) {
                            if (is_array($_POST[$key])) {
                                $value = serialize($value);
                            }
                            if (!in_array($key, array('wp_user_email_user_forgot_content', '
                            wp_user_email_admin_register_content', 'wp_user_email_admin_register_content', 'wp_user_email_user_register_content', 'wp_user_show_term_data'))
                            ) {
                                if ($key == 'wp_user_login_limit_password') {
                                    update_option($key, stripslashes($value));
                                } else {
                                    update_option($key, sanitize_text_field($value));
                                }
                            } else {
                                update_option($key, ($value));
                            }
                        } else {

                        }
                        // }
                    }
                    $responce = array(
                        'status' => 1,
                        'message' => 'Setting has been updated'
                    );
                }
            }

            print_r(json_encode($responce));
            die;

        }

        public function wpuser_update_page_setting()
        {

            global $wpdb;
            $cu = wp_get_current_user();
            // error_log(print_r($_POST,true));
            if ($cu->has_cap('manage_options')) {
                $data = $_POST;
                //error_log(print_r($data,true));
                //post status and options
                $post = array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => 1,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_name' => $data['wp_user_page_title'],
                    'post_status' => 'publish',
                    'post_title' => $data['wp_user_page_title'],
                    'post_content' => '[wp_user]',
                    'post_type' => 'page',
                );
                $wp_user_page = get_option('wp_user_page');

                if (empty($wp_user_page)) {
                    //insert page and save the id
                    $newvalue = wp_insert_post($post, false);
                    //save the id in the database
                    update_option('wp_user_page', $newvalue);
                } else {
                    $post['ID'] = $wp_user_page;
                    wp_update_post($post);
                }

                $post = array(
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_author' => 1,
                    'post_date' => date('Y-m-d H:i:s'),
                    'post_name' => $data['wp_user_member_page_title'],
                    'post_status' => 'publish',
                    'post_title' => $data['wp_user_member_page_title'],
                    'post_content' => '[wp_user_member]',
                    'post_type' => 'page',
                );
                $wp_user_member_page = get_option('wp_user_member_page');
                if (empty($wp_user_member_page)) {
                    //insert page and save the id
                    $newvalue = wp_insert_post($post, false);
                    //save the id in the database
                    update_option('wp_user_member_page', $newvalue);
                } else {
                    $post['ID'] = $wp_user_member_page;
                    wp_update_post($post);
                }
            }
            $data = array();

            $data['status'] = 1;
            $data['message'] = "Pages Created";
            $data['wp_user_page']['id'] = get_option('wp_user_page');
            $data['wp_user_page']['permalink'] = get_permalink($data['wp_user_page']['id']);
            $data['wp_user_page']['title'] = get_the_title($data['wp_user_page']['id']);
            $data['wp_user_member_page']['id'] = get_option('wp_user_member_page');
            $data['wp_user_member_page']['permalink'] = get_permalink($data['wp_user_member_page']['id']);
            $data['wp_user_member_page']['title'] = get_the_title($data['wp_user_member_page']['id']);

            print_r(json_encode($data));
            die();
            //return json_encode($data);  
        }

        public function wpuser_bulk_process()
        {
            $responce = array(
                'status' => 0,
                'message' => 'Invalid Request'
            );
            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {

                if (isset($_POST) && !empty($_POST)) {
                    //Validate that the contents of the form request came from the current site and not somewhere else added 21-08-15 V.3.4
                    if (!isset($_POST['wpuser_update_setting'])) {
                        $responce = array(
                            'status' => 0,
                            'message' => _('Invalid form data. form request came from the somewhere else not current site!', 'wpuser')
                        );
                        print_r(json_encode($responce));
                        die;
                    }
                    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                        $responce = array(
                            'status' => 0,
                            'message' => _('Invalid form data. form request came from the somewhere else not current site!', 'wpuser')
                        );
                        print_r(json_encode($responce));
                        die;
                    }
                }

                $responce = array(
                    'status' => 1,
                    'message' => 'Setting has been updated',
                    'userlist' => $_POST['userlist'],
                    'bulk_action' => $_POST['bulk_action']
                );

                if ($_POST['bulk_action'] == 'Export') {
                    $fields = (isset($_REQUEST['include_fields']) && !empty($_REQUEST['include_fields'])) ? explode(',', $_REQUEST['include_fields']) : array('ID', 'user_login', 'user_nicename', 'display_name', 'user_email', 'user_activation_key', 'user_registered');
                    $include_users = (isset($_POST['userlist']) && !empty($_POST['userlist'])) ? $_POST['userlist'] : array();
                    $args = array(
                        'role' => '',
                        'role__in' => array(),
                        'role__not_in' => array(),
                        'meta_key' => '',
                        'meta_value' => '',
                        'meta_compare' => '',
                        'meta_query' => array(),
                        'date_query' => array(),
                        'include' => $include_users,
                        'exclude' => array(),
                        'offset' => '',
                        'search' => '',
                        'number' => '',
                        'count_total' => false,
                        'fields' => $fields
                    );
                    $responce['data'] = get_users($args);

                } else {

                    foreach ($_POST['userlist'] as $userlist) {
                        if ($_POST['bulk_action'] == 'Approve') {
                            update_user_meta($userlist, 'wp-approve-user', 1);
                        } else if ($_POST['bulk_action'] == 'Deny') {
                            $user=get_userdata($userlist);
                            if(!in_array('administrator',$user->roles)) {
                                update_user_meta($userlist, 'wp-approve-user', 5);
                                // get all sessions for user with ID $user_id
                                $sessions = WP_Session_Tokens::get_instance($userlist);
                                // destroy sessions
                                $sessions->destroy_all();
                            }
                        }

                    }
                }
            }
            print_r(json_encode($responce));
            die;

        }

        public function wpuser_clear_log()
        {
            $responce = array(
                'status' => 0,
                'message' => 'Invalid Request'
            );

            $cu = wp_get_current_user();
            if ($cu->has_cap('manage_options')) {

                if (isset($_POST) && !empty($_POST)) {
                    //Validate that the contents of the form request came from the current site and not somewhere else added 21-08-15 V.3.4
                    if (!isset($_POST['wpuser_update_setting'])) {
                        $responce = array(
                            'status' => 0,
                            'message' => _('Invalid form data. form request came from the somewhere else not current site!', 'wpuser')
                        );
                        print_r(json_encode($responce));
                        die;
                    }
                    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                        $responce = array(
                            'status' => 0,
                            'message' => _('Invalid form data. form request came from the somewhere else not current site!', 'wpuser')
                        );
                        print_r(json_encode($responce));
                        die;
                    }
                    global $wpdb;
                    $table = $wpdb->prefix . 'wpuser_login_log';
                    $wpdb->query("TRUNCATE TABLE $table");
                    $responce = array(
                        'status' => 1,
                        'message' => 'Log has been cleared'
                    );
                }
            }

            print_r(json_encode($responce));
            die;

        }


    }
endif;

$obj = new wpuserAdminAjax();