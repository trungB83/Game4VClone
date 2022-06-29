<?php
/** Requiere the JWT library. */
use \Firebase\JWT\JWT;

if (!class_exists('wpuserAjax')) :
    class wpuserAjax
    {
        public $ip;
        public $time;

        public function __construct()
        {

            add_action('wp_user_action_register', array(__CLASS__, 'wp_user_action_register_function'));
            add_action('wp_user_action_login_attempts_admin_notify', array(__CLASS__, 'wp_user_action_login_attempts_admin_notify_function'));
            add_filter('wp_user_filter_email', array(__CLASS__, 'wp_user_filter_email_function'), 10, 6);

            add_action('after_setup_theme', array(__CLASS__, 'remove_admin_bar'));

            add_action('wp_ajax_nopriv_wpuser_login_action', array(__CLASS__, 'wpuser_login'));
            add_action('wp_ajax_nopriv_wpuser_login_otp_action', array(__CLASS__, 'wpuser_login_otp'));
            add_action('wp_ajax_nopriv_wpuser_forgot_action', array(__CLASS__, 'wpuser_forgot'));
            add_action('wp_ajax_nopriv_wpuser_upload_action', array(__CLASS__, 'wpuser_uploadFile'));
            add_action('wp_ajax_nopriv_wpuser_register_action', array(__CLASS__, 'wpuser_register'));

            add_action('wp_ajax_nopriv_wpuser_activation', array(__CLASS__, 'wpuser_activation'));
            add_action('wp_ajax_wpuser_activation', array(__CLASS__, 'wpuser_activation'));

            add_action('wp_ajax_nopriv_wpuser_link_login', array(__CLASS__, 'wpuser_link_login'));
            add_action('wp_ajax_wpuser_link_login', array(__CLASS__, 'wpuser_link_login'));

            add_action('wp_ajax_nopriv_wpuser_user_details', array(__CLASS__, 'wpuser_user_details'));
            add_action('wp_ajax_wpuser_user_details', array(__CLASS__, 'wpuser_user_details'));

            add_action('wp_ajax_nopriv_wpuser_user_list', array(__CLASS__, 'wpuser_user_list'));
            add_action('wp_ajax_wpuser_user_list', array(__CLASS__, 'wpuser_user_list'));

            add_action('wp_ajax_nopriv_wpuser_send_mail_action', array(__CLASS__, 'wpuser_send_mail_action'));
            add_action('wp_ajax_wpuser_send_mail_action', array(__CLASS__, 'wpuser_send_mail_action'));

            add_action('wp_ajax_wpuser_update_profile_action', array(__CLASS__, 'wpuser_update_profile_action'));

            add_action('wp_ajax_nopriv_wpuser_contact', array(__CLASS__, 'wpuser_contact'));
            add_action('wp_ajax_wpuser_contact', array(__CLASS__, 'wpuser_contact'));

            add_action('wp_ajax_wpuser_get_notification', array(__CLASS__, 'wpuser_get_notification'));
            add_action('wp_ajax_wpuser_read_notification', array(__CLASS__, 'wpuser_read_notification'));
            add_action('wp_ajax_wpuser_delete_notification', array(__CLASS__, 'wpuser_delete_notification'));
        }

        public static function wpuser_login( $params = array() )
        {
            if( !empty($params)){
              $_POST = $params;
            }

            $creds = array();
            $loginLog = array();

            $wp_user_email_name = ((isset($_POST['wp_user_email_name'])) ? $_POST['wp_user_email_name'] : '');
            $wp_user_password = ((isset($_POST['wp_user_password'])) ? $_POST['wp_user_password'] : '');
            $wp_user_otp = ((isset($_POST['wp_user_otp'])) ? $_POST['wp_user_otp'] : '');

            @$loginLog['ip'] = $_SERVER["REMOTE_ADDR"];
            $loginLog['user'] = sanitize_text_field($wp_user_email_name);
            $strErrorMsg ='';
            $result['step'] = '1';

            $boolIsValidIp = SELF::validate_ip();
            if( false == $boolIsValidIp ){
              $loginLog['message'] = $result['message'] = __('Access Denied for your IP.', 'wpuser');
              $result['status'] = 'warning';
              $loginLog['status'] = "Failed";
              print_r(json_encode($result));
              SELF::loginLog($loginLog);
              exit;
            }

            if (isset($wp_user_email_name)) {
                if (filter_var($wp_user_email_name, FILTER_VALIDATE_EMAIL)) {
                    $userInfo = get_user_by_email(sanitize_text_field($wp_user_email_name));
                    if (!empty($userInfo->user_login))
                        $creds['user_login'] = $userInfo->user_login;
                } else {
                    $wp_user_email_name = sanitize_text_field($wp_user_email_name);
                    if( true == preg_match( '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i', $wp_user_email_name ) ) {
                      // Query for users based on the meta data
                         $args = array(
                                 'meta_query' => array(
                                 'relation' => 'OR',
                                 0 => array(
                                 'key' => 'user_mobile',
                                 'value' => $wp_user_email_name,
                                 ),
                                 1 => array(
                                 'key' => 'mobile',
                                 'value' => $wp_user_email_name,
                                ),
                                 2 => array(
                                 'key' => 'phone',
                                 'value' => $wp_user_email_name,
                                 )
                              )
                      );

                      $users = get_users( $args );
                      // Get the results from the query, returning the first user
                      if( count($users) == 1 ){
                        $objUserInfo = $users[0]->data;
                        $wp_user_email_name = $objUserInfo->user_login;
                      } else if ( count($users) > 1 ) {
                          $strErrorMsg = __(' Your phone number associated with multiple account. Please try login with username or email address. ','wpuser');
                      }
                    }
                    $creds['user_login'] = $wp_user_email_name;
                }
            } else {
                $creds['user_login'] = '';
            }

            if (isset($wp_user_password)) {
                $creds['user_password'] = sanitize_text_field($wp_user_password);
            } else {
                $creds['user_password'] = '';
            }

            if (isset($_POST['wp_user_remember'])) {
                //$creds['remember'] = sanitize_text_field($data['wp_user_remember']);
            }

            /* Checks if this IP address is currently blocked */
            $attemp_msg = '';
            $wp_user_login_limit_enable = get_option('wp_user_login_limit_enable');
            if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                $confirmResponse = self::confirmIPAddress($_SERVER["REMOTE_ADDR"], $creds['user_login']);
                if ($confirmResponse['status'] == 1) {
                    $wp_user_login_limit_time = get_option('wp_user_login_limit_time');
                    if (empty($wp_user_login_limit_time)) {
                        $wp_user_login_limit_time = 30;
                    }
                    $wp_user_disable_signup = get_option('wp_user_disable_signup');
                    if (empty($wp_user_disable_signup)) {
                        $wp_user_disable_signup = 0;
                    }
                    $loginLog['message'] = $result['message'] = __('Access denied for', 'wpuser') . " " . $wp_user_login_limit_time . " " . __('minuts', 'wpuser');
                    $loginLog['status'] = "Failed";
                    $result['status'] = 'warning';
                    $result['wp_user_disable_signup'] = $wp_user_disable_signup;
                    print_r(json_encode($result));
                    SELF::loginLog($loginLog);
                    exit;
                }

                $attemp_msg = (!empty($confirmResponse['remaning'])) ? $confirmResponse['remaning'] . __(' attempts remaining.', 'wpuser') : '';
            }

            $user = get_user_by('login', $creds['user_login']);

            @$stored_value = get_user_meta($user->ID, 'wp-approve-user', true);
            @$wpuser_activation_key = get_user_meta($user->ID, 'wpuser_activation_key', true);
            if (!empty($user) && ($stored_value == 2 || $stored_value == 5)) {
                $loginLog['message'] = $result['message'] = ($stored_value == 2 && !empty($wpuser_activation_key)) ? __("Access denied : Waiting for approval.
                     Please Activate Your Account. Before you can login, you must active your account with the link sent to your email address", 'wpuser')
                    : __("Access denied : Waiting for admin approval", 'wpuser');
                $result['status'] = 'warning';
                $loginLog['status'] = "Failed";
                print_r(json_encode($result));
                SELF::loginLog($loginLog);
                exit;
            }

            //Get stored value
            @$intOTP = get_user_meta($user->ID, 'wp_user_login_otp', true);
            if( false == empty($wp_user_otp) ){
              if( $intOTP == $wp_user_otp ) {
                  $intOtpTime = get_user_meta($user->ID, 'wp_user_login_otp_validate_time', true);
                  $intCurrentTime = time();
                  if( true == empty ($intOtpTime) || $intCurrentTime > $intOtpTime ){
                    $loginLog['message'] = $result['message'] = __('Your OTP has been Expired.', 'wpuser');
                    $result['status'] = 'warning';
                    $loginLog['status'] = "Failed";
                    print_r(json_encode($result));
                    SELF::loginLog($loginLog);
                    exit;
                  }
                  SELF::userplus_auto_login( $user->user_login, true );
                  if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                      self::clearLoginAttempts($_SERVER["REMOTE_ADDR"]);
                  }
                  $result['message'] = __('Successfully login!! Refresh Page.', 'wpuser');
                  $loginLog['message'] = __('Successfull login', 'wpuser');
                  $loginLog['status'] = __('Successfull', 'wpuser');
                  $result['status'] = 'success';
                  $result['location'] = get_permalink(get_option('wp_user_page'));
                  $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup');
                  if( false == empty($params)){
                      $result['token'] = SELF::generate_jwt_auth_token( $user->ID );
                      $result['user_detils'] = wpuserAjax::getUserDetails( $user->ID );
                  }
                  print_r(json_encode($result));
                  SELF::loginLog($loginLog);
                  exit;
                } else{
                  $loginLog['message'] = $result['message'] = __('Invalid OTP. ', 'wpuser'). $attemp_msg;
                  $result['status'] = 'warning';
                  $loginLog['status'] = "Failed";
                  print_r(json_encode($result));
                  SELF::loginLog($loginLog);
                  if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                      SELF::addLoginAttempt($_SERVER["REMOTE_ADDR"]);
                  }
                  exit;
                }
            }
            /* Start 2-step Verification */
             if(get_option('wp_user_enable_two_step_auth') == 1){
                  if ( $user && wp_check_password( $creds['user_password'], $user->data->user_pass, $user->ID) ){
                            SELF::wpuser_login_otp();
                  } else {
                    $loginLog['message'] = $result['message'] = __('Invalid username or password. ', 'wpuser') . $strErrorMsg. $attemp_msg;
                    $result['status'] = 'warning';
                    $loginLog['status'] = "Failed";
                    $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup') ? 1 : 0;
                    print_r(json_encode($result));
                    SELF::loginLog($loginLog);
                    if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                        SELF::addLoginAttempt($_SERVER["REMOTE_ADDR"]);
                    }
                    exit;
                  }
             }
             /* End 2-step Verification */

            $login_user = @wp_signon($creds, true);
            if (!is_wp_error($login_user)) {
                $args = (isset($_POST) ? $_POST : '');
                do_action_ref_array('wp_user_action_login', array(&$args));
                /* Null login attempts */
                if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                    self::clearLoginAttempts($_SERVER["REMOTE_ADDR"]);
                }
                $result['message'] = __('Successfully login!! Refresh Page.', 'wpuser');
                $loginLog['message'] = __('Successfull login', 'wpuser');
                $loginLog['status'] = __('Successfull', 'wpuser');
                $result['status'] = 'success';
                $result['location'] = get_permalink(get_option('wp_user_page'));
                $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup');
                if( false == empty($params)){
                    $user = get_user_by('login', $creds['user_login']);
                    $result['token'] = SELF::generate_jwt_auth_token( $user->ID );
                    $result['user_detils'] = wpuserAjax::getUserDetails( $user->ID );
                }
                print_r(json_encode($result));
                SELF::loginLog($loginLog);
                exit;
            } elseif (is_wp_error($login_user)) {
                if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                    SELF::addLoginAttempt($_SERVER["REMOTE_ADDR"]);
                }
                $args = array($creds['user_login'], $creds['user_password']);
                do_action_ref_array('wp_user_action_login_invalid', array(&$args));
                $user = get_user_by('login', $creds['user_login']);
                //Get stored value
                @$stored_value = get_user_meta($user->ID, 'wp-approve-user', true);
                @$wpuser_activation_key = get_user_meta($user->ID, 'wpuser_activation_key', true);
                if (!empty($user) && ($stored_value == 2 || $stored_value == 5)) {
                    $loginLog['message'] = $result['message'] = ($stored_value == 2 && !empty($wpuser_activation_key)) ? __("Access denied : Waiting for approval.
                     Please Activate Your Account. Before you can login, you must active your account with the link sent to your email address", 'wpuser')
                        : __("Access denied : Waiting for admin approval", 'wpuser');
                } else {
                    $loginLog['message'] = $result['message'] = __('Invalid username or password. ', 'wpuser') . $strErrorMsg. $attemp_msg;
                }
                $result['status'] = 'warning';
                $loginLog['status'] = "Failed";
                $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup') ? 1 : 0;
                print_r(json_encode($result));
                SELF::loginLog($loginLog);
                exit;
            }
            die;
        }

        public static function wpuser_login_otp( $params = array() )
        {
            $creds = array();
            $loginLog = array();
            $strUserEmail = '';
            $strUserMobile = '';
            $strErrorMsg = '';

            if( !empty($params)){
              $_POST = $params;
            }

            $wp_user_email_name = ((isset($_POST['wp_user_email_name'])) ? $_POST['wp_user_email_name'] : '');

            if (isset($wp_user_email_name)) {
                if (filter_var($wp_user_email_name, FILTER_VALIDATE_EMAIL)) {
                    $userInfo = get_user_by_email(sanitize_text_field($wp_user_email_name));
                    if (!empty($userInfo->user_login))
                        $creds['user_login'] = $userInfo->user_login;
                } else {
                    $wp_user_email_name = sanitize_text_field($wp_user_email_name);
                    if( true == preg_match( '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i', $wp_user_email_name ) ) {
                      // Query for users based on the meta data
                         $args = array(
                                 'meta_query' => array(
                                 'relation' => 'OR',
                                 0 => array(
                                 'key' => 'user_mobile',
                                 'value' => $wp_user_email_name,
                                 ),
                                 1 => array(
                                 'key' => 'mobile',
                                 'value' => $wp_user_email_name,
                                ),
                                 2 => array(
                                 'key' => 'phone',
                                 'value' => $wp_user_email_name,
                                 )
                              )
                      );

                      $users = get_users( $args );
                      // Get the results from the query, returning the first user
                      if( count($users) == 1 ){
                        $objUserInfo = $users[0]->data;
                        $wp_user_email_name = $objUserInfo->user_login;
                      } else if ( count($users) > 1 ) {
                          $strErrorMsg = __(' Your phone number associated with multiple account. Please try login with username or email address. ','wpuser');
                      }
                    }
                    $creds['user_login'] = $wp_user_email_name;
                }
            } else {
                $creds['user_login'] = '';
            }

             if ( true == username_exists( $creds['user_login'] ) ){
                  $objUser = get_user_by('login', $creds['user_login'] );
                  $user_email = $objUser->user_email;
                  $user_id = $objUser->ID;
                  $mobile = get_user_meta( $user_id, 'user_mobile', true );
                  if( true == empty( $mobile ) ){
                    $mobile = get_user_meta( $user_id, 'mobile', true );
                  } else if( true == empty( $mobile ) ){
                    $mobile = get_user_meta( $user_id, 'phone', true );
                  }
                   $intOTP = mt_rand(100000,999999);
                   $boolIsSendOtpEmail = false;
                   $boolIsSendOtpMobile = false;
                   $intTimeValidate = 15;
                   $strMsg = array();
                   $intValidateOtpTime = time() + ( $intTimeValidate * 60 ) ;
                   update_user_meta( $user_id, 'wp_user_login_otp_validate_time', $intValidateOtpTime );
                   update_user_meta( $user_id, 'wp_user_login_otp', $intOTP );

                   if( false == empty($user_email)){
                     $boolIsSendOtpEmail = SELF::send_otp_user_email( $user_email, $intOTP, $objUser, $intTimeValidate );
                     if( true  == $boolIsSendOtpEmail ){
                         $strMsg[] = preg_replace("/(^(\w){1,4}|@|\.)(*SKIP)(*F)|(.)/","$1x",$user_email);
                       }
                   }

                   if( false == empty($mobile)){
                     $boolIsSendOtpEmail = SELF::send_otp_user_mobile( $mobile, $intOTP, $intTimeValidate );
                     if( true  == $boolIsSendOtpEmail ){
                           $strMsg[] = substr_replace( $mobile, 'XXXXXXXX', 0, 8);
                       }
                   }

                   if( true == $boolIsSendOtpEmail || true == $boolIsSendOtpMobile ){
                     $result['message'] = __( 'OTP Send on ','wpuser').implode( ", ", $strMsg ).". ". __( 'Please Enter OTP and click on Sign In.','wpuser');
                     $result['status'] = 'success';
                     $result['step'] = '2';
                     print_r(json_encode($result));
                     exit;
                   }

                 } else{
                 $error = __('There is no user registered with provided information. Please enter valid Username or Email or Mobile', 'wpuser');
                 $result['message'] = $error;
                 $result['status'] = 'warning';
                 print_r(json_encode($result));
                 exit;
             }

             $error = __('Failed to send OTP.', 'wpuser'). $strErrorMsg ;
             $result['message'] = $error;
             $result['status'] = 'warning';
             print_r(json_encode($result));
             exit;

        }

        public static function wpuser_forgot( $params = array())
        {
            if( !empty($params)){
              $_POST = $params;
            }
            $boolIsValidIp = SELF::validate_ip();
            if( false == $boolIsValidIp ){
              $result['message'] = __('Access Denied for your IP.', 'wpuser');
              $result['status'] = 'warning';
              print_r(json_encode($result));
              exit;
            }

            $email = ((isset($_POST['wp_user_email'])) ? $_POST['wp_user_email'] : '');

            if (empty($email)) {
                $error = __('Enter e-mail address', 'wpuser');
            } else if (!is_email($email)) {
                $error = __('Invalid email', 'wpuser');
            } else if (!email_exists($email)) {
                $error = __('There is no user registered with that email address', 'wpuser');
            } else {
                // lets generate our new password
                $random_password = wp_generate_password(12, false);

                // Get user data by field and data, other field are ID, slug, slug and login
                $user = get_user_by('email', $email);

                $update_user = wp_update_user(array(
                        'ID' => $user->ID,
                        'user_pass' => $random_password
                    )
                );
                $args = array($email, $user->ID, $random_password);
                do_action_ref_array('wp_user_action_forgot_password', array(&$args));

                // if  update user return true then lets send user an email containing the new password
                if ($update_user) {
                    $to = $email;
                    $subject = get_option('wp_user_email_user_forgot_subject');
                    $subject = (empty($subject)) ? 'Your new password' : $subject;
                    $message = "";
                    $sender = get_option('name');
                    $site_url = site_url();
                    $user_login = $user->user_login;
                    $email_header_text = get_option('wp_user_email_user_forgot_subject');
                    $email_body_text = apply_filters('wp_user_filter_email', get_option('wp_user_email_user_forgot_content'), $to, $user_login, null, null, $random_password);
                    $email_footer_text = 'You\'re receiving this email because you have register on ' . $site_url;
                    include('template_email/template_email_defualt.php');
                    $headers[] = 'MIME-Version: 1.0' . "\r\n";
                    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                    $headers[] = "X-Mailer: PHP \r\n";
                    $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";

                    $mail = wp_mail($to, $subject, $message, $headers);
                    if ($mail)
                        $success = __('Check your email address for your new password', 'wpuser');
                } else {
                    $error = __('Oops something went wrong updaing your account', 'wpuser');
                }
            }

            if (!empty($error)) {
                $result['message'] = $error;
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
            if (!empty($success)) {
                $result['message'] = $success;
                $result['status'] = 'success';
                print_r(json_encode($result));
                exit;
            }

            $result['message'] = __('Oops something went wrong.', 'wpuser');
            $result['status'] = 'warning';
            print_r(json_encode($result));
            die();
        }

        public static function wpuser_register( $params= array() )
        {
            $data = array();
            $result = array();
            $data = $_POST;
            $arrUserMeta = array();

            if( !empty($params)){
              $_POST = $params;
            }

            $wp_user_email_name = (isset($data['user_login'])) ? $data['user_login'] : ((isset($_POST['user_login'])) ? $_POST['user_login'] : '');
            $wp_user_email = (isset($data['user_email'])) ? $data['user_email'] : ((isset($_POST['user_email'])) ? $_POST['user_email'] : '');
            $wp_user_password = (isset($data['user_pass'])) ? $data['user_pass'] : ((isset($_POST['user_pass'])) ? $_POST['user_pass'] : '');
            $wp_user_re_password = (isset($data['confirm_pass'])) ? $data['confirm_pass'] : ((isset($_POST['confirm_pass'])) ? $_POST['confirm_pass'] : '');

            $boolIsValidIp = SELF::validate_ip();
            if( false == $boolIsValidIp ){
              $result['message'] = __('Access Denied for your IP.', 'wpuser');
              $result['status'] = 'warning';
              print_r(json_encode($result));
              exit;
            }

            if( empty($params)){
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
          }

            $form_role = 'subscriber';

            if (isset($_POST['wpuser_form_id']) && !empty($_POST['wpuser_form_id'])) {
                $form_role = get_post_meta($_POST['wpuser_form_id'], 'userplus_form_role', true);
                $form_role = (isset($_POST['role']) && !empty($_POST['role'])) ? $_POST['role'] : $form_role;
                $_POST['role'] = $form_role;

                if (isset($_POST['user_login'])) {
                    $user_exists = username_exists($_POST['user_login']);
                    $user_login = $_POST['user_login'];
                } else {
                    $user_exists = '';
                    $user_login = $_POST['user_email'];
                }

                if (empty($user_exists) && isset($_POST['user_email']) && email_exists($_POST['user_email']) == false) {
                    if (!isset($_POST['user_pass'])) {
                        $password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    } else {
                        $password = $_POST['user_pass'];
                    }
                }

                if (!isset($_POST['user_email'])) {
                    $_POST['user_email'] = $user_login . '@fakemail.com';
                } else {
                    if (!empty($_POST['user_email'])) {
                        $email = sanitize_text_field($_POST['user_email']);
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $result['message'] = __('Invalid email format', 'wpuser');
                            $result['status'] = 'warning';
                            print_r(json_encode($result));
                            exit;
                        }
                    }
                }

                if (username_exists($_POST['user_login'])) {
                    $result['status'] = 'warning';
                    $result['message'] = __('The username is already taken', 'wpuser');
                }
                if (username_exists($_POST['user_email'])) {
                    $result['status'] = 'warning';
                    $result['message'] = __('The email address already exists', 'wpuser');
                    print_r(json_encode($result));
                    exit;
                }

                $userplus_field_order = get_post_meta($_POST['wpuser_form_id'], 'userplus_field_order', true);
                $form_fields = get_post_meta($_POST['wpuser_form_id'], 'fields', true);;
                if ($userplus_field_order) {
                    $fields_count = count($userplus_field_order);
                    for ($i = 0; $i < $fields_count; $i++) {
                        $key = $userplus_field_order[$i];
                        $array = $form_fields[$key];
                        if( true == isset($_POST[$array['meta_key']])) {
                            $arrUserMeta[$array['meta_key']] = $_POST[$array['meta_key']];
                        }
                        $validationResult = profileController::validation($array, $_POST['wpuser_form_id'] );
                        if (false == $validationResult['status']) {
                            $arrFieldError[$array['meta_key'].$_POST['wpuser_form_id']] = $validationResult;
                        }

                    }
                }

                if( isset( $arrFieldError ) && !empty( $arrFieldError ) ){
                    $result['status'] = 'warning';
                    $result['error'] = $arrFieldError;
                    $result['message'] = __('Invalid input', 'wpuser');
                    print_r( json_encode( $result ) );
                    exit;
                }

                $wp_user_tern_and_condition = get_option('wp_user_tern_and_condition');
                if (isset($wp_user_tern_and_condition) && $wp_user_tern_and_condition == 1) {
                    $wp_user_term_condition = (isset($data['wp_user_term_condition'])) ? $data['wp_user_term_condition'] : ((isset($_POST['wp_user_term_condition'])) ? $_POST['wp_user_term_condition'] : '');
                    if (!(isset($wp_user_term_condition) && !empty($wp_user_term_condition))) {
                        $result['message'] = __('Please accept terms', 'wpuser');
                        $result['status'] = 'warning';
                        print_r(json_encode($result));
                        exit;
                    }
                }

                global $wpdb;
                $register_user = wp_insert_user(array(
                    'user_login' => $user_login,
                    'user_pass' => $password,
                    'display_name' => sanitize_title($user_login),
                    'user_email' => $_POST['user_email']
                ));
            } else if(isset($_POST['wpuser_form_ids']) && !empty($_POST['wpuser_form_ids'])) {
                $errorInForms = array();

                if (isset($_POST['user_login'])) {
                    $user_exists = username_exists($_POST['user_login']);
                    $user_login = $_POST['user_login'];
                } else {
                    $user_exists = '';
                    $user_login = $_POST['user_email'];
                }

                if (empty($user_exists) && isset($_POST['user_email']) && email_exists($_POST['user_email']) == false) {
                    if (!isset($_POST['user_pass'])) {
                        $password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                    } else {
                        $password = $_POST['user_pass'];
                    }
                }

                if (!isset($_POST['user_email'])) {
                    $_POST['user_email'] = $user_login . '@fakemail.com';
                } else {
                    if (!empty($_POST['user_email'])) {
                        $email = sanitize_text_field($_POST['user_email']);
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $result['message'] = __('Invalid email format', 'wpuser');
                            $result['status'] = 'warning';
                            print_r(json_encode($result));
                            exit;
                        }
                    }
                }

                if (username_exists($_POST['user_login'])) {
                    $result['status'] = 'warning';
                    $result['message'] = __('The username is already taken', 'wpuser');
                }
                if (username_exists($_POST['user_email'])) {
                    $result['status'] = 'warning';
                    $result['message'] = __('The email address already exists', 'wpuser');
                    print_r(json_encode($result));
                    exit;
                }

                $forms = explode(',', $_POST['wpuser_form_ids']);
                foreach ($forms as $form){
                $userplus_field_order = get_post_meta( $form, 'userplus_field_order', true);
                $form_fields = get_post_meta( $form, 'fields', true);
                if ($userplus_field_order) {
                    $fields_count = count($userplus_field_order);

                    for ($i = 0; $i < $fields_count; $i++) {
                        $key = $userplus_field_order[$i];
                        $array = $form_fields[$key];
                        if( true == isset($_POST[$array['meta_key']])) {
                            $arrUserMeta[$array['meta_key']] = $_POST[$array['meta_key']];
                        }
                        $validationResult = profileController::validation($array, $form);
                        if (false == $validationResult['status']) {
                            $arrFieldError[$array['meta_key'].$form] = $validationResult;
                            $errorInForms[$form] = $form;
                        }

                    }
                }
                }
                $wp_user_tern_and_condition = get_option('wp_user_tern_and_condition');
                if (isset($wp_user_tern_and_condition) && $wp_user_tern_and_condition == 1) {
                    $wp_user_term_condition = (isset($data['wp_user_term_condition'])) ? $data['wp_user_term_condition'] : ((isset($_POST['wp_user_term_condition'])) ? $_POST['wp_user_term_condition'] : '');
                    if (!(isset($wp_user_term_condition) && !empty($wp_user_term_condition))) {
                        $arrFieldError['message'] = __('Please accept terms', 'wpuser');
                        $arrFieldError['status'] = 'warning';
                        $arrFieldError['field'] = 'wp_user_term_condition';
                    }
                }

                if( isset( $arrFieldError ) && !empty( $arrFieldError ) ){
                    $result['status'] = 'warning';
                    $result['error'] = $arrFieldError;
                    $result['error_in_forms'] = $errorInForms;
                    $result['message'] = __('Invalid input', 'wpuser');
                    print_r( json_encode( $result ) );
                    exit;
                }

                global $wpdb;
                $register_user = wp_insert_user(array(
                    'user_login' => $user_login,
                    'user_pass' => $password,
                    'display_name' => sanitize_title($user_login),
                    'user_email' => $_POST['user_email']
                ));

            } else {
                $arrUserMeta = $_POST;
                $form_role = (isset($_POST['role']) && !empty($_POST['role'])) ? $_POST['role'] : $form_role;
                $_POST['role'] = $form_role;

                if (isset($wp_user_email_name) && !empty($wp_user_email_name)) {
                    $username = sanitize_text_field($wp_user_email_name);
                } else {
                    $result['message'] = __('Username field is required', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    $username = "";
                    exit;
                }

                if (isset($wp_user_email) && !empty($wp_user_email)) {
                    $email = sanitize_text_field($wp_user_email);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $result['message'] = __('Invalid email format', 'wpuser');
                        $result['status'] = 'warning';
                        print_r(json_encode($result));
                        exit;
                    }
                } else {
                    $result['message'] = __('Email field is required', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }

                if (isset($wp_user_password) && !empty($wp_user_password)) {
                    $password = sanitize_text_field($wp_user_password);
                } else {
                    $result['message'] = __('Password field is required', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    $password = "";
                    exit;
                }

                if (isset($wp_user_re_password) && !empty($wp_user_re_password)) {
                    $wp_user_login_limit_password = (get_option('wp_user_login_limit_password'));
                    $wp_user_login_limit_password_enable = get_option('wp_user_login_limit_password_enable');

                    $re_password = sanitize_text_field($wp_user_re_password);
                    if (($password != $re_password)) {
                        $result['message'] = __('Password is not match', 'wpuser');
                        $result['status'] = 'warning';
                        print_r(json_encode($result));
                        exit;
                    }
                    if (isset($wp_user_login_limit_password_enable) && $wp_user_login_limit_password_enable == 1 && !empty($wp_user_login_limit_password) && !(preg_match($wp_user_login_limit_password, $password))) {
                        $wp_user_login_password_valid_message = get_option('wp_user_login_password_valid_message');
                        $result['message'] = !empty($wp_user_login_password_valid_message) ? $wp_user_login_password_valid_message : _e('Invalid Password', 'wpuser');;
                        $result['status'] = 'warning';
                        print_r(json_encode($result));
                        exit;
                    }
                } else {
                    $result['message'] = __('Retype Password field is required', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    $re_password = "";
                    exit;
                }
                $wp_user_tern_and_condition = get_option('wp_user_tern_and_condition');
                if (isset($wp_user_tern_and_condition) && $wp_user_tern_and_condition == 1) {
                    $wp_user_term_condition = (isset($data['wp_user_term_condition'])) ? $data['wp_user_term_condition'] : ((isset($_POST['wp_user_term_condition'])) ? $_POST['wp_user_term_condition'] : '');
                    if (!(isset($wp_user_term_condition) && !empty($wp_user_term_condition))) {
                        $result['message'] = __('Please accept terms', 'wpuser');
                        $result['status'] = 'warning';
                        print_r(json_encode($result));
                        exit;
                    }
                }
                $register_user = wp_create_user($username, $password, $email);
            }

            $autologin = get_post_meta($_POST['wpuser_form_id'], 'userplus_form_autologin', true);
            $wp_user_default_status = get_option('wp_user_default_status');

            if ($register_user && !is_wp_error($register_user)) {
                if ($wp_user_default_status == 3) {
                    $result['message'] = __('Please Activate Your Account. Before you can login,
                    you must active your account with the link sent to your email address', 'wpuser');
                    $wp_user_default_status = 2;
                } elseif($wp_user_default_status == 2) {
			        $result['message'] = __('Your Accout is Pending for Admin approval', 'wpuser');
		        } else {
                    $result['message'] = __('Registration completed', 'wpuser');
                    $user_info = get_userdata($register_user);
                    if(($autologin == 1 && $wp_user_default_status == 1) || ( !is_plugin_active( 'wp-user-form-builder/userplus.php') && $wp_user_default_status == 1 ))
                        {
                            SELF::userplus_auto_login( $user_info->user_login, true );
                        }
                }

                unset($_POST['confirm_pass']);
                unset($_POST['wpuser_update_setting']);
                SELF::wpuser_update_user_profile($register_user, $arrUserMeta);
                add_user_meta($register_user, 'wp-approve-user', $wp_user_default_status);
                $result['status'] = 'success';
                $args = (isset($data)) ? $data : (isset($_POST) ? $_POST : '');
                $args['user_id'] = $register_user;
                print_r(json_encode($result));
                do_action_ref_array('wp_user_action_register', array(&$args));
            } elseif (is_wp_error($register_user)) {
                $result['message'] = $register_user->get_error_message();
                $result['status'] = 'warning';
                print_r(json_encode($result));
            }
            exit;

        }

        public static function wpuser_uploadFile()
        {
            // get details of the uploaded file
            $fileTmpPath = $_FILES['file']['tmp_name'];
            $fileName = $_FILES['file']['name'];
            $fileSize = $_FILES['file']['size'];
            $fileType = $_FILES['file']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                $upload_dir   = wp_upload_dir();
                $uploadFileDir =  $upload_dir['basedir'].'/';
                $uploadFileUrl =  $upload_dir['baseurl'].'/';
                $dest_path = $uploadFileDir . $newFileName;
                if(move_uploaded_file($fileTmpPath, $dest_path))
                {
                    $responce = array(
                        'status' => 'Sucess',
                        'dir' => $uploadFileDir,
                        'url' => $uploadFileUrl.$newFileName,
                        'message' => __('File is successfully uploaded.', 'wpuser')
                    );
                    print_r(json_encode($responce));
                    die;
                }
            }

            $responce = array(
                'status' => 'warning',
                'message' => __('There was some error moving the file to upload.', 'wpuser')
            );
            print_r(json_encode($responce));
            die;



        }

        public static function send_otp_user_email( $user_email, $intOTP, $user, $intTimeValidate = 15 ){
          $subject = 'Your OTP';
          $message = "";
          $sender = get_option('name');
          $site_url = site_url();
          $user_login = $user->user_login;
          if (get_option('wp_user_disable_login_otp_link') != 1) {
            $intOTPText = '<a href="'.admin_url('admin-ajax.php') . '?action=wpuser_link_login&email=' . $to.'&otp='.$intOTP.'">'.$intOTP."</a>";
          } else {
            $intOTPText = $intOTP;
          }
        $email_header_text = 'Your OTP';
          $email_body_text = '<p>Dear User,
                               <br>Please use this one-time verification code to login.</p>';
          $email_body_text .= '<h2 style="text-align:center">'.$intOTPText.'</h2>';
          $email_body_text .= '<p style="text-align:center"><small>ONE TIME VERIFICATION CODE</small></p>';
          $email_body_text .= '<p>Valid for '.$intTimeValidate.' minutes</p>';
          $email_footer_text = '<ul style="text-align:left">';
          $email_footer_text .= '<li>You\'re receiving this email because you have register on ' . $site_url.'</li>';
          $email_footer_text .= '<li>Do not share your credentials or otp with anyone on call, email or sms. </li>';
          $email_footer_text .= '</ul>';
          include('template_email/template_email_defualt.php');
          $headers[] = 'MIME-Version: 1.0' . "\r\n";
          $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
          $headers[] = "X-Mailer: PHP \r\n";
          $headers[] = 'From: ' . $sender . ' < ' . $user_email . '>' . "\r\n";

          $mail = wp_mail($user_email, $subject, $message, $headers);
          if ($mail) {
                 return true;
              }
                return false;
        }

        public static function send_otp_user_mobile( $user_mobile, $intOTP, $intTimeValidate = 15 ){
          if(function_exists('twl_send_sms')){
            if( 10 == strlen( $user_mobile ) ){
              $user_mobile = apply_filters('wpuser_mobile_number_country_prefix', $user_mobile);
            }
                $args = array(
            	             'number_to' => $user_mobile,
            	              'message' => __('Dear user, '.$intOTP.' is your One Time Password(OTP) to login. OTP Valid till '.$intTimeValidate.' min.', 'wpuser'),
                            );
               return twl_send_sms( $args );
            }
          return false;
        }

        public static function userplus_auto_login( $username, $remember=true ) {

            		ob_start();
            		if ( !is_user_logged_in() ) {

            			$user = get_user_by('login', $username );
            			$user_id = $user->ID;

            			wp_set_current_user( $user_id, $username );
            			wp_set_auth_cookie( $user_id, $remember );
            			do_action( 'wp_login', $username,$user );

            		} else {
            			wp_logout();
            			$user = get_user_by('login', $username );
            			$user_id = $user->ID;

            			wp_set_current_user( $user_id, $username );
            			wp_set_auth_cookie( $user_id, $remember );
            			do_action( 'wp_login', $username,$user );
            		}
            		ob_end_clean();
   }

        public static function wpuser_update_profile_action( $params = array() ){
            $result = array();
            if( !empty($params)){
              $_POST = $params;
            }
            $advanced_info = array();
            $wpuser_form_id = '';
            $data = $_POST;
            $register_user = get_current_user_id();
            $wp_user_email = (isset($data['user_email'])) ? $data['user_email'] : ((isset($_POST['user_email'])) ? $_POST['user_email'] : '');
            $wp_user_password = (isset($data['user_pass'])) ? $data['user_pass'] : ((isset($_POST['user_pass'])) ? $_POST['user_pass'] : '');
            $wp_user_re_password = (isset($data['confirm_pass'])) ? $data['confirm_pass'] : ((isset($_POST['confirm_pass'])) ? $_POST['confirm_pass'] : '');

            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
               // print_r(json_encode($responce));
              //  die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
              //  print_r(json_encode($responce));
              //  die;
            }

            if (isset($wp_user_re_password) && !empty($wp_user_re_password)) {
                $wp_user_login_limit_password = (get_option('wp_user_login_limit_password'));
                $wp_user_login_limit_password_enable = get_option('wp_user_login_limit_password_enable');
                $re_password = sanitize_text_field($wp_user_re_password);
                // echo $wp_user_login_limit_password;die;
                if (!($wp_user_password == $re_password)) {
                    $result['message'] = __('Password is not match', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                } else if (isset($wp_user_login_limit_password_enable) && $wp_user_login_limit_password_enable == 1 && isset($wp_user_login_limit_password) && !empty($wp_user_login_limit_password) && !(preg_match($wp_user_login_limit_password, $wp_user_password))) {
                    $wp_user_login_password_valid_message = get_option('wp_user_login_password_valid_message');
                    $result['message'] = !empty($wp_user_login_password_valid_message) ? $wp_user_login_password_valid_message : _e('Invalid Password', 'wpuser');;
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }
            } else if (isset($wp_user_password) && !empty($wp_user_password)) {
                $result['message'] = __('Retype Password field is required', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if (isset($wp_user_email) && !empty($wp_user_email)) {
                $email = sanitize_text_field($wp_user_email);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $result['message'] = __('Invalid email format', 'wpuser');
                    $result['status'] = 'warning';
                    print_r(json_encode($result));
                    exit;
                }
            }

            if (isset($_POST['wpuser_form_id']) && !empty($_POST['wpuser_form_id'])) {
                unset($_POST['user_login']);
                $wpuser_form_id = $_POST['wpuser_form_id'];
                //Validation
                $userplus_field_order = get_post_meta($_POST['wpuser_form_id'], 'userplus_field_order', true);
                $form_fields = get_post_meta($_POST['wpuser_form_id'], 'fields', true);;
                if ($userplus_field_order) {
                    $fields_count = count($userplus_field_order);
                    for ($i = 0; $i < $fields_count; $i++) {
                        $key = $userplus_field_order[$i];
                        $array = $form_fields[$key];
                        if (!in_array($array['meta_key'], array('user_login', 'user_pass'))) {
                            if (isset ($array['is_required']) && $array['is_required'] == 1 &&
                                (!isset($_POST[$array['meta_key']]) || empty($_POST[$array['meta_key']]))
                            ) {
                                $result['status'] = 'warning';
                                $result['message'] = __($array['label'] . ' field is required', 'wpuser');
                                print_r(json_encode($result));
                                exit;
                            }
                        }
                    }
                }
                unset($_POST['wpuser_form_id']);
            } else {

                if (isset($wp_user_email) && !empty($wp_user_email)) {
                    $email = sanitize_text_field($wp_user_email);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $result['message'] = __('Invalid email format', 'wpuser');
                        $result['status'] = 'warning';
                        print_r(json_encode($result));
                        exit;
                    }
                }
                //Validation
                include('view/option.php');
                foreach ($wp_user_options_my_profile_form as $array) {
                    if (!in_array($array['meta_key'], array('user_login'))) {
                        if (isset ($array['is_required']) && $array['is_required'] == 1 &&
                            (!isset($_POST[$array['meta_key']]) || empty($_POST[$array['meta_key']]))
                        ) {
                            $result['status'] = 'warning';
                            $result['message'] = __($array['label'] . ' field is required', 'wpuser');
                            print_r(json_encode($result));
                            exit;
                        }
                    }
                }
            }

            if ($register_user && !is_wp_error($register_user)) {
                $result['message'] = __('Profile updated successfully', 'wpuser');
                unset($_POST['confirm_pass']);
                unset($_POST['wpuser_update_setting']);
                SELF::wpuser_update_user_profile($register_user, $_POST);
                $result['status'] = 'success';

                $user_id = $register_user;
                $attachment_url = esc_url(get_the_author_meta('user_meta_image', $user_id));
                if(empty($attachment_url)){
                    $attachment_url = esc_url(get_the_author_meta('profile_pic', $user_id));
                }
                $attachment_id = profileController::get_attachment_image_by_url($attachment_url);
                $image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');

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

                if (isset($wpuser_form_id) && !empty($wpuser_form_id)) {
                    //Get Advanced meta data
                    $userplus_field_order = get_post_meta($wpuser_form_id, 'userplus_field_order', true);
                    $form_fields = get_post_meta($wpuser_form_id, 'fields', true);;
                    if ($userplus_field_order) {
                        $fields_count = count($userplus_field_order);
                        for ($i = 0; $i < $fields_count; $i++) {
                            $key = $userplus_field_order[$i];
                            $array = $form_fields[$key];
                            if (!in_array($array['type'], array('image_upload')) &&
                                !in_array($array['meta_key'],
                                    array('user_login', 'user_pass', 'user_url', 'user_pass', 'first_name', 'description', 'user_email', 'last_name'))
                            ) {
                                $advanced_info[$array['meta_key']] = get_the_author_meta($key, $register_user);
                            }
                        }
                    }
                }

                $profile_background_pic = get_user_meta($user_id, 'profile_background_pic', true);

                $meta = get_user_meta($user_id);
                $email = profileController::wpuser_profile_details('user_email', $user_id);
                $user_url = profileController::wpuser_profile_details('user_url', $user_id);

                $user_info = array(
                    "name" => $name,
                    "profile_img" => $wp_user_profile_img,
                    "profile_background_pic" => $profile_background_pic,
                    "first_name" => $meta['first_name'],
                    "last_name" => $meta['last_name'],
                    "description" => $meta['description'],
                    "email" => $email,
                    'user_url' => $user_url,
                    'advanced' => $advanced_info
                );

                $user_info = apply_filters('wpuser_profile_info', $user_info, $user_id);
                $result['user_info'] = $user_info;

                print_r(json_encode($result));
            } else if (is_wp_error($register_user)) {
                $result['message'] = $register_user->get_error_message();
                $result['status'] = 'warning';
                print_r(json_encode($result));
            } else {
                $result['message'] = __('Please Refresh Page', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
            }
            exit;

        }

        public static function wpuser_user_details()
        {
            $result = array();
            $user_id = ((isset($_POST['id'])) ? $_POST['id'] : '');

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
            print_r(json_encode(wpuserAjax::getUserDetails($user_id)));
            die();

        }

        public static function wpuser_user_list()
        {

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
            print_r(json_encode(SELF::getUserList()));
            die();

        }

        public static function getUserDetails( $user_id )
        {
            $result = array();
            global $wpdb;
            $wp_user_member_filter = '';
            $member_post = array();
            $value = json_decode(file_get_contents("php://input"));
            $wp_user_labels = apply_filters('wp_user_member_filter', $wp_user_member_filter, $user_id);
            $attachment_url = esc_url(get_the_author_meta('user_meta_image', $user_id));
            if(empty($attachment_url)){
                $attachment_url = esc_url(get_the_author_meta('profile_pic', $user_id));
            }
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
            $header_block_info = array(
                array(
                    "name" => 'Blogs',
                    "type" => 'block',
                    "id" => 'profile_block',
                    "url" => get_author_posts_url($user_id),
                    "user_id" => $user_id,
                    'icon' => 'fa fa-th-large',
                    "count" => count($authors_posts),
                )
            );

            $user_url = profileController::wpuser_profile_details('user_url', $user_id);

            $user_info = array(
                "First name" => $meta['first_name'],
                "Last name" => $meta['last_name'],
                "Description" => $meta['description'],
                'Website' => $user_url,
            );
            $user_info = apply_filters('wpuser_profile_info', $user_info, $user_id);
            //$result['user_info'] = $user_info;
            $user_header_follow_button ='';
            $user_header_follow_button = apply_filters('wp_user_member_filter_header_button', $user_header_follow_button,$user_id);
            $user_badge ='';
            $user_badge = apply_filters('wp_user_member_filter_badge', $user_badge,$user_id);

            $header_block_info = apply_filters('wp_user_member_filter_header_block', $header_block_info, $user_id);
            $user_info = apply_filters('wp_user_member_info', $user_info, $user_id);
            $profile_background_pic = get_user_meta($user_id, 'profile_background_pic', true);
            $data = array(
                'status' => 1,
                "id" => $user_id,
                "name" => $name,
                'labels' => $wp_user_labels,
                "wp_user_profile_img" => $wp_user_profile_img,
                "wp_user_background_img" => $profile_background_pic,
                'header_block_info' => $header_block_info,
                'user_info' => $user_info,
                'user_header_follow_button'=>$user_header_follow_button,
                'user_badge'=>$user_badge
            );
           return $data;

        }

        public static function getUserList( $params = array() )
        {
            $result = array();
            $responce=array();

            if( !empty( $params )){
              $_POST = $params;
            }

            $role__in = (isset($_POST['role_in']) && !empty($_POST['role_in'])) ? explode(',', $_POST['role_in']) : array();
            $role__not_in = (isset($_POST['role_not_in']) && !empty($_POST['role_not_in'])) ? explode(',', $_POST['role_not_in']) : array();
            $include = (isset($_POST['include']) && !empty($_POST['include'])) ? explode(',', $_POST['include']) : array();
            $exclude = (isset($_POST['exclude']) && !empty($_POST['exclude'])) ? explode(',', $_POST['exclude']) : array();
            $meta_key = (isset($_POST['approve']) && ($_POST['approve'] == '1')) ? 'wp-approve-user' : '';
            $meta_value = (isset($_POST['approve']) && ($_POST['approve'] == '1')) ? 1 : '';

            $page = ((isset($_POST['page']) && !empty($_POST['page']))) ? $_POST['page'] : 1;
            $per_page = ((isset($_POST['per_page']) && !empty($_POST['per_page']))) ? $_GET['per_page'] : 12;
            $offset = (($page - 1) * $per_page);

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
            if(isset($_POST['form_id']) && !empty($_POST['form_id'])){
                $userplus_field_order = get_post_meta($_POST['form_id'], 'userplus_field_order', true);
                if ($userplus_field_order) {
                    $fields_count = count($userplus_field_order);
                    for ($i = 0; $i < $fields_count; $i++) {
                        $key = $userplus_field_order[$i];
                        if(!empty($key) && isset($_POST[$key]) && !empty(($_POST[$key])) && 'null' != $_POST[$key] ){
                            $meta_query[] = array(
                                'key' => $key,
                                'value' => $_POST[$key],
                                'compare' => '=='
                            );
                        }
                    }
                }

            }

            if(isset($_POST['search_form_id']) && !empty($_POST['search_form_id'])){
                $userplus_field_order = get_post_meta($_POST['search_form_id'], 'userplus_field_order', true);
                if ($userplus_field_order) {
                    $fields_count = count($userplus_field_order);
                    for ($i = 0; $i < $fields_count; $i++) {
                        $key = $userplus_field_order[$i];
                        if(!empty($key) && isset($_POST[$key]) && !empty(($_POST[$key]) && 'null' != $_POST[$key]) ){
                            $meta_query[] = array(
                                'key' => $key,
                                'value' => $_POST[$key],
                                'compare' => '=='
                            );
                        }
                    }
                }

            }

            if(isset($_POST['key']) && !empty($_POST['key'])){
                $arrStrKeyFilter = (isset($_POST['key']) && !empty($_POST['key']) ) ? explode(',', $_POST['key']) : array();
                if( !empty($arrStrKeyFilter) ){
                    foreach ( $arrStrKeyFilter as $key ){
                        if(!empty($key) && isset($_POST[$key]) && !empty(($_POST[$key])) && 'null' != $_POST[$key]){
                            $meta_query[] = array(
                                'key' => $key,
                                'value' => $_POST[$key],
                                'compare' => 'LIKE'
                            );
                        }
                    }
                }

            }

            if(!empty($meta_query)){
                $args['meta_query']= $meta_query;
            }

            if(empty($meta_query) && isset($_POST['search_user']) &&  !empty($_POST['search_user']) && 'null' != $_POST['search_user'] ){
                if( is_numeric( $_POST['search_user'] ) ){
                    $args['include'] = array(  $_POST['search_user'] );
                }else {
                    $args['meta_query'] = array(
                        'relation' => 'OR',
                        array(
                            'key' => 'first_name',
                            'value' => $_POST['search_user'],
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key' => 'ID',
                            'value' => $_POST['search_user'],
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key' => 'last_name',
                            'value' => $_POST['search_user'],
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key' => 'description',
                            'value' => $_POST['search_user'],
                            'compare' => 'LIKE'
                        ),
                        array(
                            'key' => 'nickname',
                            'value' => $_POST['search_user'],
                            'compare' => 'LIKE'
                        ),
                    );
                }
            }

            if(isset($_POST['orderby']) && !empty($_POST['orderby'])) {
                $args['orderby'] = $_POST['orderby'];
            } else {
                $args['orderby'] ='registered';
            }

            if(isset($_POST['order']) && !empty($_POST['order'])) {
                $args['order'] = $_POST['order'];
            }

            $args_user = $args;
            $args_user['offset'] = $offset;
            $args_user['number'] = $per_page;
            $blogusers = get_users($args_user);

            //$args['count_total'] = true;
            $total_count = count(get_users($args));
            //print_r($total_count);die;

            $total_pages = ($total_count > 0) ? ceil($total_count / $per_page) : 0;
            $pagination = array(
                'page' => (int)$page,
                'per_page' => $per_page,
                'total_count' => $total_count,
                'total_pages' => $total_pages
            );

            $wp_user_page = get_option('wp_user_page');
            $currentpage_url =  add_query_arg('page_no', 1 ,get_permalink());
            $responce_result=array();
            $profile_prefix ='';
            $boolIshideUserName = false ;
            $profile_prefix = apply_filters('wpuser_filter_profile_prefix', $profile_prefix);
            $boolIshideUserName = apply_filters('wpuser_filter_hide_user_name', $boolIshideUserName);

            foreach ($blogusers as $value) {
                    $result['user_id']=$value->ID;
                    $genre_url = !empty($wp_user_page) ? add_query_arg(array('user_id'=>$value->ID,'redirect'=>$currentpage_url,'url_title'=>'Members List'), get_permalink($wp_user_page)) : '#';
                    if(isset($_GET)) {
                        $genre_url = !empty($wp_user_page) ? add_query_arg($_GET, $genre_url) : '#';
                    }
                    $result['genre_url'] = $genre_url;
                    $user_title = (get_user_meta($value->ID, 'user_title', true));
                    $occupation_details = (get_user_meta($value->ID, 'occupation_details', true));
                    $occupation_city = (get_user_meta($value->ID, 'occupation_city', true));
                    $occupation_details = ( !empty($occupation_details)) ? $occupation_details.', '.$occupation_city : $occupation_details;
                    $result['title'] = ( !empty($user_title)) ? $user_title : $occupation_details;
                    $result['user_status'] = (get_user_meta($value->ID, 'wp-approve-user', true));
                    // retrieve the thumbnail size of our image
                    $attachment_url = esc_url(get_the_author_meta('user_meta_image', $value->ID));
                     if(empty($attachment_url)){
                        $attachment_url = esc_url(get_the_author_meta('profile_pic', $value->ID));
                     }
                    $attachment_id = profileController::get_attachment_image_by_url($attachment_url);
                    // retrieve the thumbnail size of our image
                    $image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    // return the image thumbnail
                    if (!empty($image_thumb[0])) {
                        $result['wp_user_profile_img'] = $image_thumb[0];
                    } else if (!empty($attachment_url)) {
                        $result['wp_user_profile_img'] = $attachment_url;
                    } else {
                        $args = get_avatar_data($value->ID);
                        if (!empty($args['url']))
                            $result['wp_user_profile_img'] = $args['url'];
                        else
                            $result['wp_user_profile_img']  = WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
                    }
                    $result['first_name'] = $first_name = get_the_author_meta('first_name', $value->ID);
                    $result['last_name'] = $last_name = get_the_author_meta('last_name', $value->ID);
                    $name =  $first_name. " " .$last_name;
                    $result['user_mobile'] =$user_mobile = get_the_author_meta('user_mobile', $value->ID);
                    $authors_posts=$authors_posts = get_posts(array('author' => $value->ID, 'post_status' => 'publish'));
                    $authors_posts_count =  count($authors_posts);
                    $result['get_author_posts_url'] = $user_blog_url= ($authors_posts_count) ? get_author_posts_url($value->ID) : '#';
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
                    $profile_prefix_user = apply_filters('wpuser_filter_profile_user_id_prefix', $profile_prefix, $value->ID);
                    $result['user_name'] =  $profile_prefix_user.(( false == $boolIshideUserName ) ? $name : $value->ID);

                $user_icon=array();
                $user_body =array();

                $user_status = (get_user_meta($value->ID, 'wp-approve-user', true));

                $user_follow['is_follow_setting'] = 0;
                if ( ( is_user_logged_in() || get_current_user_id() ) && function_exists('wp_user_list_button_view_profile')) {
                    $info['user_id'] = is_user_logged_in() ? $value->ID : get_current_user_id() ;
                    $user_follow['is_follow_setting'] = 1;
                    $user_follow['is_follow'] = wp_user_list_button_view_profile($info);
                }

                $boolIsValidAccess = self::checkAccess( $value->ID );
                $header_block_info = array();
                $user_id = $value->ID;

                if($user_status=1 || $user_status==2) {
                    $header_info = array(
                        "name" => ($user_status == 1) ? 'Approved' : 'Pending',
                        "type" => 'status',
                        "id" => 'status',
                        "url" => 0,
                        "user_id" => $user_id,
                        'icon' =>  ($user_status == 1) ? 'fa fa-check' : 'fa fa-exclamation',
                        "count" => '',
                        'class'=> ($user_status == 1) ? 'green' :'orange',
                        "is_click"=>1
                    );
                    array_push($header_block_info, $header_info);

                }

                if( true == $boolIsValidAccess ) {
                    $header_info = array(
                        "name" => 'Send Mail',
                        "type" => 'block',
                        "id" => 'sendMail',
                        "url" => 0,
                        "user_id" => $user_id,
                        'icon' => 'fa fa-envelope',
                        "count" => '',
                        "is_click"=>1
                    );
                    array_push($header_block_info, $header_info);
                }



                if($authors_posts_count) {
                    $header_info = array(
                        "name" => 'Blogs',
                        "type" => 'block',
                        "id" => 'profile_block',
                        "url" => get_author_posts_url($user_id),
                        "user_id" => $user_id,
                        'icon' => 'fa fa-th-large',
                        "count" => count($authors_posts)
                    );
                    array_push($header_block_info, $header_info);
                }



                $facebook = get_user_meta( $user_id , 'facebook', true);

                if($facebook) {
                    $header_info = array(
                        "name" => 'Facebook',
                        "type" => 'facebook',
                        "id" => 'facebook',
                        "url" => $facebook,
                        "user_id" => $user_id,
                        'icon' => 'fa fa-facebook',
                        "class" => 'blue',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $linkedin = get_user_meta( $user_id , 'linkedin', true);

                if($linkedin) {
                    $header_info = array(
                        "name" => 'Linkedin',
                        "type" => 'linkedin',
                        "id" => 'linkedin',
                        "url" => $linkedin,
                        "user_id" => $user_id,
                        'icon' => 'fa fa-linkedin',
                        "class" => 'blue',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $googleplus = get_user_meta( $user_id , 'googleplus', true);

                if($googleplus) {
                    $header_info = array(
                        "name" => 'Google Plus',
                        "type" => 'googleplus',
                        "id" => 'googleplus',
                        "url" => $googleplus,
                        "user_id" => $user_id,
                        'icon' => 'fa fa-google-plus',
                        "class" => 'red',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $twitter = get_user_meta( $user_id , 'twitter', true);
                if($twitter) {
                    $header_info = array(
                        "name" => 'Twitter',
                        "type" => 'twitter',
                        "id" => 'twitter',
                        "url" => $twitter,
                        "user_id" => $user_id,
                        "class" => 'aqua',
                        'icon' => 'fa fa-twitter',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $website = get_user_meta( $user_id , 'website', true);
                if($website) {
                    $header_info = array(
                        "name" => 'Website',
                        "type" => 'website',
                        "id" => 'website',
                        "url" => $website,
                        "user_id" => $user_id,
                        'icon' => 'fa fa-globe',
                        "class" => 'blue',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $gender = get_user_meta( $user_id , 'gender', true);
                if($gender) {
                    $header_info = array(
                        "name" => ucfirst($gender),
                        "type" => 'gender',
                        "id" => 'gender',
                        "url" => 0,
                        "user_id" => $user_id,
                        'icon' => ( 'male' == strtolower($gender) ? 'fa fa-male' :  ('female' == strtolower($gender) ? 'fa fa-female' : 'fa fa-user') ),
                        "class" => 'gray',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $diet = get_user_meta( $user_id , 'diet', true);
                if($diet) {
                    $header_info = array(
                        "name" => $diet,
                        "type" => 'diet',
                        "id" => 'diet',
                        "url" => 0,
                        "user_id" => $user_id,
                        'icon' => 'fa fa-coffee',
                        "class" => ( 'veg' == strtolower($diet)) ? 'green' : 'red',
                        "count" => ''
                    );
                    array_push($header_block_info, $header_info);
                }

                $user_icon = $header_block_info;


                if(isset($_POST['profile_form_id']) && !empty($_POST['profile_form_id'])) {
                    $wpuser_form_id = $_POST['profile_form_id'];
                }else{
                    $wpuser_form_id = get_the_author_meta('wpuser_form_id', $value->ID);
                }

                if (isset($wpuser_form_id) && !empty($wpuser_form_id)) {
                    //Validation
                    $profile_fields =array();
                    $userplus_field_order = get_post_meta($wpuser_form_id, 'userplus_field_order', true);
                    $form_fields = get_post_meta($wpuser_form_id, 'fields', true);
                    if ($userplus_field_order) {
                        $fields_count = count($userplus_field_order);
                        for ($i = 0; $i < $fields_count; $i++) {
                            $key = $userplus_field_order[$i];
                            $array = $form_fields[$key];
                            if (!in_array($array['type'], array('image_upload'))  && !in_array($array['meta_key'],
                                    array('user_login', 'user_pass', 'user_url', 'first_name', 'description', 'user_email', 'last_name'))
                            ) {
                                if(!empty($array['meta_key'])) {
                                    $profile_fields[] = array(
                                        'meta_key' => $array['meta_key'],
                                        'label' => $array['label'],
                                        'icon' => $array['icon'],
                                        'value' => get_the_author_meta($array['meta_key'], $value->ID)
                                    );
                                }
                            }
                        }
                    }

                    $user_body=$profile_fields;
                }
                $result['user_follow'] = $user_follow;
                $result['user_icon']=$user_icon;
                $result['user_body']=$user_body;
                $responce_result[]=$result;

            }

            $responce['is_header'] = 1;
            //$responce['header'] = $header;
            $responce['list'] = $responce_result;
            $responce['pagination'] = $pagination;
            $responce['status'] = 'success';

            return $responce;

        }

        public static function wpuser_contact( $params = array() )
        {
            global $wpdb;
            $error = array();
            if( !empty($params)){
              $_POST = $params;
            }
            global $current_user, $wp_roles;
            $current_user = wp_get_current_user();
            $WP_USER_INPUT = (isset($_POST)) ? $_POST : '';
            if (!empty($WP_USER_INPUT['wp_user_email_subject']) && !empty($WP_USER_INPUT['wp_user_email_content'])) {

                $wp_user_email_name = get_option('wp_user_email_name');
                $wp_user_email_id = get_option('wp_user_email_id');
                $sender = !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname');
                $email = !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email');
                $subject = get_option('wp_user_email_admin_register_subject');
                $site_url = site_url();
                $headers[] = 'MIME-Version: 1.0' . "\r\n";
                $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers[] = "X-Mailer: PHP \r\n";
                $headers[] = 'From: ' . $sender . ' < ' . $current_user->user_email . '>' . "\r\n";
                $message = $WP_USER_INPUT['wp_user_email_content'];

                $message .= '<br>Username: ' . $current_user->user_login . '<br />';
                $message .= 'User email: ' . $current_user->user_email . '<br />';

                if (!(wp_mail($email, $WP_USER_INPUT['wp_user_email_subject'], $message, $headers)))
                    $error[] = "Error : Mail Not send";

            } else {
                $error[] = "All field are required";
            }

            if (count($error) == 0) {
                $result['message'] = ' Mail send to admin';
                $result['status'] = 'success';
            } else {
                $result['message'] = implode(',', $error);
                $result['status'] = 'warning';
            }

            print_r(json_encode($result));
            die();
        }

        public static function wpuser_send_mail_action( $params = array())
        {
            $result = array();
            if( !empty($params)){
              $_POST = $params;
            }
            $user_id = ((isset($_POST['id'])) ? $_POST['id'] : '');
            if( true == empty($params)){
            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
          }

            if (!isset($_POST['id']) || empty($_POST['id'])) {
                $result['message'] = __('Invalid receiver', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if (!isset($_POST['message']) || empty($_POST['message'])) {
                $result['message'] = __('Please enter message', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }
            $user_info = get_userdata($_POST['id']);

            $to = $user_info->user_email;
            $subject = (isset($_POST['subject']) && !empty($_POST['subject'])) ? $_POST['subject'] : "New Message From " . get_bloginfo('name');
            $body = $_POST['message'];
            $headers[] = 'MIME-Version: 1.0' . "\r\n";
            $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers[] = "X-Mailer: PHP \r\n";
            $headers [] = 'Content-Type: text/html; charset=UTF-8';
            if (isset($_POST['from']) && !empty($_POST['from'])) {
                $headers[] = 'From: ' . get_bloginfo('name') . ' < ' . $_POST['from'] . '>' . "\r\n";
            }

            if (!wp_mail($to, $subject, $body, $headers)) {
                $responce = array(
                    'status' => 'warning',
                    'message' => __('Error : Mail send', 'wpuser')
                );

            } else {
                $responce = array(
                    'status' => 'success',
                    'message' => __('Mail send successfully', 'wpuser')
                );
            }

            print_r(json_encode($responce));
            die();

        }

        public static function wpuser_activation( $params = array() )
        {
          if( !empty($params)){
            $_REQUEST = $params;
          }

            $wp_user_email_name = ((isset($_REQUEST['email'])) ? sanitize_text_field($_REQUEST['email']) : '');
            $key = ((isset($_REQUEST['key'])) ? $_REQUEST['key'] : '');
            if (!empty($wp_user_email_name)) {
                if (filter_var($wp_user_email_name, FILTER_VALIDATE_EMAIL)) {
                    $userInfo = get_user_by_email(sanitize_text_field($wp_user_email_name));
                    if (!empty($userInfo->ID)) {
                        $wpuser_activation_key = get_user_meta($userInfo->ID, 'wpuser_activation_key', true);
                        if ($wpuser_activation_key == $key) {
                            delete_user_meta($userInfo->ID, 'wpuser_activation_key');
                            update_user_meta($userInfo->ID, 'wp-approve-user', 1);
                            _e('Your account has been aprroved. ');
                            echo '<a href="' . get_permalink(get_option('wp_user_page')) . '">';
                            _e('Click here to login.', 'wpuser');
                            echo '</a>';
                            die;
                        }
                    }
                }
            }
            die('Invalid URL');
        }

        public static function wpuser_link_login( $params = array() )
        {
          if( !empty($params)){
            $_REQUEST = $params;
          }
            $wp_user_email_name = ((isset($_REQUEST['email'])) ? sanitize_text_field($_REQUEST['email']) : '');
            $key = ((isset($_REQUEST['key'])) ? $_REQUEST['key'] : '');
            $wp_user_otp = ((isset($_REQUEST['otp'])) ? $_REQUEST['otp'] : '');
            $wp_user_redirect = ((isset($_REQUEST['redirect'])) ? $_REQUEST['redirect'] : get_permalink(get_option('wp_user_page')));
            if (!empty($wp_user_email_name)) {
                if (filter_var($wp_user_email_name, FILTER_VALIDATE_EMAIL)) {
                    $user = get_user_by_email(sanitize_text_field($wp_user_email_name));

                    if (!empty($user->ID)) {
                        @$intOtpTime = get_user_meta($user->ID, 'wp_user_login_otp_validate_time', true);
                      //  if ($wpuser_activation_key == $intOTP) {
                          //Get stored value
                          @$intOTP = get_user_meta($user->ID, 'wp_user_login_otp', true);
                          if( false == empty($wp_user_otp) ){
                            if( $intOTP == $wp_user_otp ) {
                                $intOtpTime = get_user_meta($user->ID, 'wp_user_login_otp_validate_time', true);
                                $intCurrentTime = time();
                                if( true == empty ($intOtpTime) || $intCurrentTime > $intOtpTime ){
                                  _e('Your OTP has been Expired.', 'wpuser');
                                  die;
                                }
                                SELF::userplus_auto_login( $user->user_login, true );
                                if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                                    self::clearLoginAttempts($_SERVER["REMOTE_ADDR"]);
                                }
                                $wpuser_activation_key = get_user_meta($user->ID, 'wpuser_activation_key', true);
                                if ($wpuser_activation_key == $key) {
                                    delete_user_meta($user->ID, 'wpuser_activation_key');
                                    update_user_meta($user->ID, 'wp-approve-user', 1);
                                }
                                delete_user_meta($user->ID, 'wp_user_login_otp');
                                _e('Successfully login!! Refresh Page.', 'wpuser');
                                $loginLog['message'] = __('Successfull login', 'wpuser');
                                $loginLog['status'] = __('Successfull', 'wpuser');
                                $result['location'] = get_permalink(get_option('wp_user_page'));
                                $result['wp_user_disable_signup'] = get_option('wp_user_disable_signup');
                                if( false == empty($params)){
                                    $result['token'] = SELF::generate_jwt_auth_token( $user->ID );
                                    $result['user_detils'] = wpuserAjax::getUserDetails( $user->ID );
                                }
                                SELF::loginLog($loginLog);
                                wp_redirect($wp_user_redirect);
                                die;
                              } else{
                                $loginLog['message'] = $result['message'] = __('Invalid OTP. ', 'wpuser'). $attemp_msg;
                                $loginLog['status'] = "Failed";
                                SELF::loginLog($loginLog);
                                if (isset($wp_user_login_limit_enable) && !empty($wp_user_login_limit_enable)) {
                                    SELF::addLoginAttempt($_SERVER["REMOTE_ADDR"]);
                                }
                                _e('Invalid OTP. ', 'wpuser');
                                die;
                              }
                          }
                          /* Start 2-step Verification */
                    }
                }
            }
            die('Invalid URL');
        }

        public static function wpuser_register_form_validation()
        {

        }

        public static function validate_ip(){

          if (isset($_SERVER['HTTP_CLIENT_IP'])) {
              $client_ip = $_SERVER['HTTP_CLIENT_IP'];
          } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
              $client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
          } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
              $client_ip = $_SERVER['HTTP_X_FORWARDED'];
          } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
              $client_ip = $_SERVER['HTTP_FORWARDED_FOR'];
          } else if(isset($_SERVER['HTTP_FORWARDED'])) {
              $client_ip = $_SERVER['HTTP_FORWARDED'];
          } else if(isset($_SERVER['REMOTE_ADDR'])) {
              $client_ip = $_SERVER['REMOTE_ADDR'];
          }

          $strBlackListIp = get_option('wp_user_security_blacklist_ips');
          if( false == empty($strBlackListIp)){
              $arrBlackListIps = (explode(",",$strBlackListIp));
              $boolIsBlock = SELF::check_in_ip_list($client_ip,$arrBlackListIps);
              if( true == $boolIsBlock ){
                return false;
              }
          }

          $strWhiteListIp = get_option('wp_user_security_whiltelist_ips');
          if( false == empty($strWhiteListIp)){
              $arrWhiteListIps = (explode(",",$strWhiteListIp));
              $boolIsBlock = SELF::check_in_ip_list($client_ip,$arrWhiteListIps);
              if( false == $boolIsBlock ){
                return false;
              }
          }
          return true;
        }

        public static function check_in_ip_list($client_ip,$arrWhiteListIps){
              $blocked = false;
              foreach ($arrWhiteListIps as $ip) {
                      if($client_ip == $ip){
                        $blocked = true;
                        break;
                      } else if(strpos($ip, '*') !== false){
                        $digits = explode(".", $ip);
                        $client_ip_digits = explode(".", $client_ip);
                        if($digits[1] == '*' && $digits[0] == $client_ip_digits[0])
                        {
                          $blocked = true;
                          break;
                        }else if($digits[2] == '*' && $digits[0] == $client_ip_digits[0] && $digits[1] == $client_ip_digits[1]){
                          $blocked = true;
                          break;
                        }else if($digits[3] == '*' && $digits[0] == $client_ip_digits[0] && $digits[1] == $client_ip_digits[1] && $digits[2] == $client_ip_digits[2]){
                          $blocked = true;
                          break;
                        }
                      }
                      else if(strpos($ip, "-") !== false){
                        list($start_ip, $end_ip) = explode("-", $ip);
                        $start_ip = preg_replace('/\s+/', '', $start_ip);
                        $end_ip = preg_replace('/\s+/', '', $end_ip);
                        $start_ip_long = ip2long($start_ip);
                        $end_ip_long = ip2long($end_ip);
                        $client_ip_long = ip2long($ip);
                        if($client_ip_long >= $start_ip_long && $client_ip_long <= $end_ip_long){
                          $blocked = true;
                          break;
                        }
                      }
              }
              return $blocked;
        }


        public static function wpuser_update_user_profile($user_id, $form_data)
        {

            foreach ($form_data as $key => $form_value) {

                if (isset($key) && in_array($key, array('user_url', 'display_name', 'role', 'user_login', 'user_pass', 'user_email'))) {
                    wp_update_user(array('ID' => $user_id, $key => $form_value));
                } else {
                    update_user_meta($user_id, $key, $form_value);
                }
            }
        }

        public static function confirmIPAddress($value, $user_login = null)
        {
            global $wpdb;
            $wp_user_login_limit_time = get_option('wp_user_login_limit_time');
            if (empty($wp_user_login_limit_time)) {
                $wp_user_login_limit_time = 30;
            }

            $wwp_user_login_limit = get_option('wp_user_login_limit');
            if (empty($wwp_user_login_limit)) {
                $wwp_user_login_limit = 5;
            }
            $accessTime = date('Y-m-d h:i:m');

            $q = "SELECT Attempts, (CASE when lastlogin is not NULL and DATE_ADD(LastLogin, INTERVAL " . $wp_user_login_limit_time .
                " MINUTE)>'" . $accessTime . "' then 1 else 0 end) as Denied FROM {$wpdb->prefix}WPUser_LoginAttempts WHERE ip = '$value'";
            //echo $q;die;
            $data = $wpdb->get_results($q);
            //Verify that at least one login attempt is in database
            if (!$data) {
                return array(
                    'status' => 0,
                    'remaning' => 0
                );
            }
            if ($data[0]->Attempts >= $wwp_user_login_limit) {
                $args = array($value, $accessTime, $user_login);
                do_action_ref_array('wp_user_action_login_attempts_admin_notify', array(&$args));
                if ($data[0]->Denied == 1) {
                    return array(
                        'status' => 1,
                        'remaning' => ($wwp_user_login_limit - $data[0]->Attempts)
                    );
                } else {
                    self::clearLoginAttempts($value);
                    return array(
                        'status' => 0,
                        'remaning' => ($wwp_user_login_limit - $data[0]->Attempts)
                    );
                }
            }
            return array(
                'status' => 0,
                'remaning' => ($wwp_user_login_limit - $data[0]->Attempts)
            );
        }

        public static function addLoginAttempt($value)
        {
            //Increase number of Attempts. Set last login attempt if required.
            global $wpdb;
            $q = "SELECT * FROM {$wpdb->prefix}WPUser_LoginAttempts WHERE ip = '$value'";
            $data = $wpdb->get_results($q);

            if ($data) {
                $Attempts = $data[0]->Attempts + 1;

                if ($Attempts == 3) {
                    $values['Attempts'] = $Attempts;
                    $values['lastlogin'] = date('Y-m-d h:i:m');
                    $wpdb->update($wpdb->prefix . 'WPUser_LoginAttempts', $values, array('IP' => $value));
                } else {
                    $values['Attempts'] = $Attempts;

                    $wpdb->update($wpdb->prefix . 'WPUser_LoginAttempts', $values, array('IP' => $value));
                }
            } else {
                $values['Attempts'] = 1;
                $values['IP'] = $value;
                $values['lastlogin'] = date('Y-m-d h:i:m');
                $result = $wpdb->insert($wpdb->prefix . 'WPUser_LoginAttempts', $values);
            }
        }

        static function clearLoginAttempts($value)
        {
            global $wpdb;
            $values['Attempts'] = 0;
            return $wpdb->update($wpdb->prefix . 'WPUser_LoginAttempts', $values, array('IP' => $value));
        }

        public static function loginLog($value)
        {
            global $wpdb;
            @$value['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $wpdb->insert($wpdb->prefix . 'wpuser_login_log', $value);
        }

        public static function wp_user_action_register_function(&$args)
        {
            //error_log("WP USER :Inside wp_user_action_register action");
            $to = $args['user_email'];
            $message = "";
            $wp_user_email_name = get_option('wp_user_email_name');
            $wp_user_email_id = get_option('wp_user_email_id');
            $sender = !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname');
            $email = !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email');
            $subject = get_option('wp_user_email_admin_register_subject');
            $subject = (empty($subject)) ? 'New User Registration' : $subject;
            $site_url = site_url();
            $headers[] = 'MIME-Version: 1.0' . "\r\n";
            $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers[] = "X-Mailer: PHP \r\n";
            $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";

            if (get_option('wp_user_email_admin_register_enable')) {
                $email_header_text = get_option('wp_user_email_admin_register_subject');
                $email_body_text = apply_filters('wp_user_filter_email', get_option('wp_user_email_admin_register_content'), $to, $args['user_email'], null, null, null);
                $email_footer_text = 'You\'re receiving this email because you have Enable notifiacion for new user register on ' . $site_url;
                include('template_email/template_email_defualt.php');
                $mail = wp_mail($email, $subject, $message, $headers);
            }

            if (get_option('wp_user_email_user_register_enable')) {
                $email_header_text = get_option('wp_user_email_user_register_subject');
                $email_body_text = apply_filters('wp_user_filter_email', get_option('wp_user_email_user_register_content'), $to, $args['user_email'], null, null, null);
                $email_footer_text = 'You\'re receiving this email because you have register on ' . $site_url;
                include('template_email/template_email_defualt.php');
                $mail = wp_mail($to, $subject, $message, $headers);
            }

            $wp_user_default_status = get_option('wp_user_default_status');
            if ($wp_user_default_status == 3) {

              $intOTP = mt_rand(100000,999999);
              $boolIsSendOtpEmail = false;
              $boolIsSendOtpMobile = false;
              $intTimeValidate = 90;
              $strMsg = array();
              $intValidateOtpTime = time() + ( $intTimeValidate * 60 ) ;
              update_user_meta( $args['user_id'], 'wp_user_login_otp_validate_time', $intValidateOtpTime );
              update_user_meta( $args['user_id'], 'wp_user_login_otp', $intOTP );
                $email_header_text = get_option('wp_user_email_user_register_subject');
                $random_key = wp_generate_password(12, false);
                $email_body_text = __('Click on following link to activate your account ', 'wpuser');
                $activationLink = admin_url('admin-ajax.php') . '?action=wpuser_activation&key=' . $random_key . '&email=' . $to;
                $email_body_text .= $activationLink;
                $email_body_text .= '<div class="row">';
                $email_body_text .= '<a type="button" href="'.$activationLink.'" class="btn btn-primary btn-flat">'.__('ACTIVATE', 'wpuser').'</a>';
                if (get_option('wp_user_disable_login_otp_link') != 1) {
                  $login_redirect = '';
                  if(true == isset($args['email_login_redirect']) && false == empty($args['email_login_redirect'])){
                    $login_redirect = '&redirect='.$args['email_login_redirect'];
                  }
                  $email_body_text .= ' <a class="btn btn-primary btn-flat" href="'.admin_url('admin-ajax.php') . '?action=wpuser_link_login&key=' . $random_key . '&email=' . $to.'&otp='.$intOTP.$login_redirect.'">'.__('LOGIN', 'wpuser')."</a>";
                }
                $email_body_text .= '</div>';
                $email_footer_text = 'You\'re receiving this email because you have register on ' . $site_url;
                include('template_email/template_email_defualt.php');
                update_user_meta($args['user_id'], 'wpuser_activation_key', $random_key);
                $subject = __('Confirm your ' . get_option('blogname') . ' account', 'wpuser');
                //echo $message;die;
                if(!wp_mail($to, $subject, $message, $headers)){
                }
            }


        }

        public static function wpuser_get_notification(){
            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            $mynotifications=profileController::getNotification(get_current_user_id());
            $responce = array(
                'status' => 'success',
                'notifications' => $mynotifications
            );
                print_r(json_encode($responce));
                die();
        }

        public static function wpuser_read_notification(){
            global $wpdb;
            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }

            if (!isset($_POST['id']) || empty($_POST['id'])) {
                $result['message'] = __('Invalid receiver', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if($wpdb->update(
                $wpdb->prefix.'wpuser_notification',
                array(
                    'is_unread' => 0
                ),
                array( 'ID' => $_POST['id'])
            )){
                $result['status'] = 'success';
                print_r(json_encode($result));
                exit;
            }
        }

        public static function wpuser_delete_notification(){
            global $wpdb;
            if (!isset($_POST['wpuser_update_setting'])) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }
            if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
                $responce = array(
                    $result['status'] = 'warning',
                    'message' => __('Invalid form data. form request came from the somewhere else not current site! Please Refresh Page.', 'wpuser')
                );
                print_r(json_encode($responce));
                die;
            }

            if (!isset($_POST['id'])) {
                $result['message'] = __('Invalid receiver', 'wpuser');
                $result['status'] = 'warning';
                print_r(json_encode($result));
                exit;
            }

            if($_POST['id']==0){
               $delete=array( 'recipient_id' => get_current_user_id() );
            }else{
                $delete= array( 'ID' => $_POST['id'] );
            }

            if($wpdb->delete( $wpdb->prefix.'wpuser_notification', $delete)){
                $result['status'] = 'success';
                print_r(json_encode($result));
                exit;
            }


        }

        public static function wp_user_action_login_attempts_admin_notify_function(&$args)
        {
            if (get_option('wp_user_login_limit_admin_notify')) {
                //error_log("WP USER :Inside wp_user_action_login_attempts_admin_notify_function action");
                $subject = 'Login Attempts';
                $message = "";
                $wp_user_email_name = get_option('wp_user_email_name');
                $wp_user_email_id = get_option('wp_user_email_id');
                $sender = !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname');
                $email = !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email');
                $site_url = site_url();
                $headers[] = 'MIME-Version: 1.0' . "\r\n";
                $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers[] = "X-Mailer: PHP \r\n";
                $headers[] = 'From: ' . $sender . ' < ' . $email . '>' . "\r\n";
                $ip = $args[0];
                $accesTime = $args[1];
                $accesUserName = $args[2];
                $bodyText = '<p>A failed login attempt has occurred on ' . $accesTime . '.
                            Someone from the ' . $ip . ' IP address  used the ' . $accesUserName . ' username  to attempt to login on ' . $site_url . '</p>
                 <p>If you did not attempt to access your site, please contact your Information Technology Security Team immediately.</p>
                 <p>
                 Server Date & Time: ' . $accesTime . ' <br>
                 From IP Address: ' . $ip . '
                 </p>';
                $email_header_text = 'SECURITY ALERT: Failed Login Attempt on ' . $site_url;
                $email_body_text = apply_filters('wp_user_filter_email', $bodyText, null, $accesUserName, null, null, null);
                $email_footer_text = ' You\'re receiving this email because you have enable setting (WP User) "Notify on lockout (Email to admin after)" on ' . $site_url;
                include('template_email/template_email_defualt.php');
                $mail = wp_mail($email, $subject, $message, $headers);
                //error_log("WP USER :Login Attempts $ip");
            }
        }

        public static function wp_user_filter_email_function($value, $userEmail = null, $userName = null, $userFirstName = null, $userLastName = null, $newPassword = null)
        {
            $wp_user_email_name = get_option('wp_user_email_name');
            $wp_user_email_id = get_option('wp_user_email_id');
            $replace = array(
                '{WPUSER_ADMIN_EMAIL}' => !empty($wp_user_email_id) ? $wp_user_email_id : get_option('admin_email'),
                '{WPUSER_BLOGNAME}' => get_option('blogname'),
                '{WPUSER_LOGIN_URL}' => get_permalink(get_option('wp_user_page')),
                '{WPUSER_BLOG_ADMIN}' => !empty($wp_user_email_name) ? $wp_user_email_name : get_option('blogname'),
                '{WPUSER_BLOG_URL}' => get_option('siteurl'),
                '{WPUSER_USERNAME}' => $userName,
                '{WPUSER_FIRST_NAME}' => $userFirstName,
                '{WPUSER_LAST_NAME}' => $userLastName,
                '{WPUSER_NAME}' => $userName,
                '{WPUSER_EMAIL}' => $userEmail,
                '{WPUSER_NEW_PASSWORD}' => $newPassword
            );
            $value = str_replace(array_keys($replace), $replace, $value);
            return $value;
        }

        static function remove_admin_bar()
        {
            $wp_user_disable_admin_bar = get_option('wp_user_disable_admin_bar');
            if (!empty($wp_user_disable_admin_bar) && $wp_user_disable_admin_bar == 1) {
                if (!current_user_can('administrator') && !is_admin()) {
                    show_admin_bar(false);
                }
            }
        }

        static function checkAccess( $user_id, $arrArg = array(), $type ='list' ){
            global $wpdb;

            if( 'view-profile' == $type ){
                if( $arrArg['view_by'] == 0 ){
                    return true;
                }
                $limit = 0;
                $limit = apply_filters('wpuser_filter_profile_view_validation', $limit, $user_id );

                $querystr = "
                                SELECT
                                 count(id)
                                FROM
                                 " . $wpdb->prefix . "wpuser_views
                                WHERE
                                view_by = ".$arrArg['view_by']."
                             ";
                $count = $wpdb->get_var( $querystr );
                if ( 0 != $limit && $limit <= $count ) {
                    return false;
                }
                return true;

            }


            return $isUserLogged = (is_user_logged_in() || get_current_user_id() ) ? 1 : 0;
        }

        public static function generate_jwt_auth_token( $user_id ){

            $issuedAt = time();
            $notBefore = apply_filters('jwt_auth_not_before', $issuedAt, $issuedAt);
            $expire = apply_filters('jwt_auth_expire', $issuedAt + (DAY_IN_SECONDS * 180), $issuedAt);

            $token = array(
                'iss' => get_bloginfo('url'),
                'iat' => $issuedAt,
                'nbf' => $notBefore,
                'exp' => $expire,
                'data' => array(
                    'user' => array(
                        'id' => $user_id,
                    ),
                ),
            );
            $wpuser_site_key  = get_option('wpuser_site_key');
            $token = JWT::encode( $token, $wpuser_site_key);
            return $token;
        }

    }
endif;

$obj = new wpuserAjax();
