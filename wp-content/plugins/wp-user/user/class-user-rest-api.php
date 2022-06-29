<?php
use \Firebase\JWT\JWT;
/**
 * Manage User in the WordPress REST API.
 *
 * @link       http://wpuserplus.com
 * @since      6.0
 *
 * @package    WPUserRestAPI
 */

if (!class_exists('WPUserRestAPI')) :
    class WPUserRestAPI {

      /**
    	 * Register actions and filters.
    	 *
    	 * @since    6.0
       	 */
    	public static function init() {
        if( true == get_option('wp_user_enable_rest_api')) {
      		add_action( 'rest_api_init', array( __CLASS__, 'api_register' ) );
          add_filter( 'determine_current_user',	array( __CLASS__, 'determine_current_user' ) );
        }
    	}

      /**
    	 * Register REST API.
    	 *
    	 * @since  6.0
    	 */
    	public static function api_register() {
    			register_rest_route( 'wpuser/v1', '/users', array(
    				'callback' => array( __CLASS__, 'api_get_users' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
    				'methods' => 'GET'
    			));

          register_rest_route( 'wpuser/v1', '/posts', array(
    				'callback' => array( __CLASS__, 'api_add_post' ),
    				'methods' => 'POST',
            'permission_callback' => array( __CLASS__, 'api_get_permissions_publish_posts' )
    			));

    			register_rest_route( 'wpuser/v1', '/users/(?P<id>\d+)', array(
    				'callback' => array( __CLASS__, 'api_get_user_details' ),
    				'methods' => 'GET',
    				'args' => array(
    					'id' => array(
    						'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
    					),
    				),
    				'permission_callback' => array( __CLASS__, 'api_user_details_permissions' ),
    			));

          register_rest_route( 'wpuser/v1', '/user/login', array(
            'callback' => array( __CLASS__, 'api_wpuser_login' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
            'methods' => 'POST'
          ));

          register_rest_route( 'wpuser/v1', '/token/generate', array(
            'callback' => array( __CLASS__, 'api_wpuser_login' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
            'methods' => 'POST'
          ));

          register_rest_route( 'wpuser/v1', '/user/forgot', array(
            'callback' => array( __CLASS__, 'api_wpuser_forgot' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
            'methods' => 'POST'
          ));

          register_rest_route( 'wpuser/v1', '/user/register', array(
            'callback' => array( __CLASS__, 'api_wpuser_register' ),
            'methods' => 'POST',
            'permission_callback' => array( __CLASS__, 'api_user_register_permissions' ),
          ));

          register_rest_route( 'wpuser/v1', '/login/otp', array(
            'callback' => array( __CLASS__, 'api_wpuser_login_otp' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
            'methods' => 'POST'
          ));

          register_rest_route( 'wpuser/v1', '/user/upload', array(
            'callback' => array( __CLASS__, 'api_wpuser_upload' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
            'methods' => 'POST'
          ));


          register_rest_route( 'wpuser/v1', '/token/validate', array(
            'callback' => array( __CLASS__, 'api_validate_token' ),
            'permission_callback' => array( __CLASS__, 'api_validate_api_key' ),
            'methods' => 'POST'
          ));

          //user
          register_rest_route( 'wpuser/v1', '/mail/(?P<id>\d+)', array(
            'callback' => array( __CLASS__, 'wpuser_send_mail_action' ),
            'methods' => 'POST',
            'args' => array(
              'id' => array(
                'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
              ),
            ),
            'permission_callback' => array( __CLASS__, 'api_validate_user_logged_in' ),
          ));

          //user
          register_rest_route( 'wpuser/v1', '/user/(?P<id>\d+)', array(
            'callback' => array( __CLASS__, 'wpuser_update_profile_action' ),
            'methods' => 'POST',
            'args' => array(
              'id' => array(
                'validate_callback' => array( __CLASS__, 'api_validate_numeric' ),
              ),
            ),
            'permission_callback' => array( __CLASS__, 'api_validate_user_logged_in' ),
          ));

          register_rest_route( 'wpuser/v1', '/contact)', array(
            'callback' => array( __CLASS__, 'wpuser_contact' ),
            'methods' => 'POST',
            'permission_callback' => array( __CLASS__, 'api_validate_user_logged_in' ),
          ));

          //admin
          register_rest_route( 'wpuser/v1', '/user/activation', array(
            'callback' => array( __CLASS__, 'wpuser_activation' ),
            'methods' => 'POST',
            'permission_callback' => array( __CLASS__, 'api_get_permissions_admin' )
          ));

    	}

      /**
    	 * Handle list users call to the REST API.
    	 *
    	 * @since 6.0
    	 */
    	public static function api_get_users( $request ) {
    		// Parameters.
    		$params = $request->get_params();
        SELF::set_current_user($params['user_id']);
        return wpuserAjax::getUserList($params);

      }

      /**
       * @since 6.0
       */
      public static function api_get_user_details( $request ) {
        // Parameters.
        $params = $request->get_params();
        $user_id = isset( $params['id'] ) ? $params['id'] : 0;
        return wpuserAjax::getUserDetails( $user_id );

      }

      /**
       * @since 6.0
       */
      public static function api_wpuser_login( $request ) {
        // Parameters.
        $params = $request->get_params();
        return wpuserAjax::wpuser_login( $request );

      }

      /**
       * @since 6.0
       */
      public static function api_wpuser_register( $request ) {
        // Parameters.
        $params = $request->get_params();
        return wpuserAjax::wpuser_register( $request );

      }

      /**
       * @since 6.0
       */
      public static function api_wpuser_upload( $request ) {
        // Parameters.
        $params = $request->get_params();
      //  return wpuserAjax::wpuser_uploadFile( $request );

      }

      /**
       * @since 6.0
       */
      public static function wpuser_activation( $request ) {
        // Parameters.
        $params = $request->get_params();
        return wpuserAjax::wpuser_uploadFile( $request );

      }

      /**
       * @since 6.0
       */
      public static function api_wpuser_forgot( $request ) {
        // Parameters.
        $params = $request->get_params();
        return wpuserAjax::wpuser_forgot( $request );

      }

      /**
    	 * @since 6.0
    	 */
    	public static function api_user_login( $request ) {
    		// Parameters.
    		$params = $request->get_params();
        return wpuserAjax::getUserList($params);

      }

      /**
    	 * @since 6.0
    	 */
    	public static function wpuser_update_profile_action( $request ) {
    		// Parameters.
    		$params = $request->get_params();
        return wpuserAjax::wpuser_update_profile_action($params);

      }

      /**
    	 * @since 6.0
    	 */
    	public static function wpuser_send_mail_action( $request ) {
    		// Parameters.
    		$params = $request->get_params();
        return wpuserAjax::wpuser_send_mail_action($params);

      }

      /**
    	 * @since 6.0
    	 */
    	public static function wpuser_contact( $request ) {
    		// Parameters.
    		$params = $request->get_params();
        return wpuserAjax::wpuser_contact($params);

      }

      /**
    	 * @since 6.0
    	 */
    	public static function api_wpuser_login_otp( $request ) {
    		// Parameters.
    		$params = $request->get_params();
        return wpuserAjax::wpuser_login_otp($params);

      }

      public static function api_add_post( $request ) {
        // Parameters.
        $params = $request->get_params();
        $current_user = wp_get_current_user();
        $postarr = array();

        $postarr['post_author'] = get_current_user_id();

        if( true == isset($params['post_title'])){
          $postarr['post_title'] = sanitize_text_field($params['post_title']);
        }

        if( true == isset($params['post_name'])){
          $postarr['post_name'] = sanitize_text_field($params['post_name']);
        }

        if( true == isset($params['post_content'])){
          $postarr['post_content'] = sanitize_text_field($params['post_content']);
        }

        if( true == isset($params['post_status'])){
          $postarr['post_status'] = sanitize_text_field($params['post_status']);
        }

        if( true == isset($params['comment_status'])){
          $postarr['comment_status'] = sanitize_text_field($params['comment_status']);
        }

        if( true == isset($params['ping_status'])){
          $postarr['ping_status'] = sanitize_text_field($params['ping_status']);
        }

        if( true == isset($params['post_parent'])){
          $postarr['post_parent'] = sanitize_text_field($params['post_parent']);
        }

        if( true == isset($params['post_type'])){
          $postarr['post_type'] = sanitize_text_field($params['post_type']);
        }

        if( true == isset($params['post_excerpt'])){
          $postarr['post_excerpt'] = sanitize_text_field($params['post_excerpt']);
        }

        if( true == isset($params['post_password'])){
          $postarr['post_password'] = sanitize_text_field($params['post_password']);
        }

        if( true == isset($params['post_category'])){
          $postarr['post_category'] = sanitize_text_field($params['post_category']);
        }

        if( true == isset($params['tags_input'])){
          $postarr['tags_input'] = sanitize_text_field($params['tags_input']);
        }

        if( true == isset($params['meta_input'])){
          $postarr['meta_input'] = sanitize_text_field($params['meta_input']);
        }

        if( wp_insert_post( $postarr )){
          return array(
              'code' => 'wpuser_save_post',
              'message' => __('Data Saved Successfull','wpuser'),
              'status' => 'success',
              'data' => array(
                  'status' => 200,
              ),
          );
        }

        return new WP_Error(
            'wpuser_post_bad_request',
            __('Fail to add data','wpuser'),
            array(
                'status' => 403,
            )
        );

      }

      public static function set_current_user( $user_id = 0 ) {
          set_current_user($user_id);
      }


      public static function determine_current_user( $user_id = 0 ) {
        if( true == $user_id ){
            return $user_id;
        }
        $response = SELF::api_validate_token();
        if(  true == is_array($response) && true == isset($response['code']) && 'jwt_auth_valid_token' == $response['code'] && true == isset($response['user_id']) ) {
          $user_id = $response['user_id'];
          wp_set_current_user($user_id);
          return $user_id;
        }
        return 0;
      }

      /**
       * Check permissions for the API.
       *
       * @since 6.0
       */
      public static function api_get_permissions() {
        $validate_api = SELF::api_validate_api_key();
        if( true == is_object($validate_api)){
          return $validate_api;
        }
        return true;
      }

      public static function api_get_permissions_publish_posts( $request ) {
        $validate_api = SELF::api_validate_api_key();
        if( true == is_object($validate_api)){
          return $validate_api;
        }
          return current_user_can('publish_posts');
      }

      public static function api_get_permissions_admin( $request ) {
        $validate_api = SELF::api_validate_api_key();
        if( true == is_object($validate_api)){
          return $validate_api;
        }
          return current_user_can('administrator');
      }

      public static function api_validate_user_logged_in( $request ) {
        $validate_api = SELF::api_validate_api_key();
        if( true == is_object($validate_api)){
          return $validate_api;
        }
          return get_current_user_id();
      }
      /**
       * @since 6.0
       */
      public static function api_user_details_permissions() {
        $validate_api = SELF::api_validate_api_key();
        if( true == is_object($validate_api)){
          return $validate_api;
        }
        return true;
      }

      /**
       * @since 6.0
       */
      public static function api_user_register_permissions() {
         SELF::api_validate_api_key();
        return true;
      }

      /**
       * Validate ID in API call.
       *
      * @since 6.0
       * @param mixed           $param Parameter to validate.
       * @param WP_REST_Request $request Current request.
       * @param mixed           $key Key.
       */
      public static function api_validate_numeric( $param, $request, $key ) {
        return is_numeric( $param );
      }

      public static function api_validate_api_key(  $request = array() ) {
        if( true == get_option('wp_user_enable_rest_api_key_auth')){
            $api_key = isset($_SERVER['API_KEY']) ? $_SERVER['API_KEY'] : false;
            if( $api_key == get_option('wpuser_api_key') || $api_key == get_option('connectwp_site_key') ){
              return true;
            } else {
              return new WP_Error(
                  'wpuser_bad_api_key',
                  'Invalid API Key Provided.',
                  array(
                      'status' => 403,
                  )
              );
            }
        }
        return true;

      }

      public static function api_validate_token(  $request = array() ) {

        $auth = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;

        /* Double check for different auth header string (server dependent) */
        $token ='';
        if ($auth) {
          list($token) = sscanf( $auth, 'Bearer %s' );
        }else if( false == empty($request) ){
          $params = $request->get_params();
          if( isset($params['token'])){
            $token = $auth = $params['token'];
          }
        }

        if (true == empty($token)) {
            return new WP_Error(
                'jwt_auth_bad_auth_header',
                'Authorization header malformed.',
                array(
                    'status' => 403,
                )
            );
        }

      /** Get the Secret Key */
      $secret_key =  get_option('wpuser_site_key');
      if (!$secret_key) {
          return new WP_Error(
              'jwt_auth_bad_config',
              'JWT is not configurated properly, please contact the admin',
              array(
                  'status' => 403,
              )
          );
      }

      /** Try to decode the token */
      try {
          $token = JWT::decode($token, $secret_key, array('HS256'));
          /** The Token is decoded now validate the iss */
          if ($token->iss != get_bloginfo('url')) {
              /** The iss do not match, return error */
              return new WP_Error(
                  'jwt_auth_bad_iss',
                  'The iss do not match with this server',
                  array(
                      'status' => 403,
                  )
              );
          }
          /** So far so good, validate the user id in the token */
          if (!isset($token->data->user->id)) {
              /** No user id in the token, abort!! */
              return new WP_Error(
                  'jwt_auth_bad_request',
                  'User ID not found in the token',
                  array(
                      'status' => 403,
                  )
              );
          }

          return array(
              'code' => 'jwt_auth_valid_token',
              'user_id' => $token->data->user->id,
              'data' => array(
                  'status' => 200,
              ),
          );
      } catch (Exception $e) {
          return new WP_Error(
              'jwt_auth_invalid_token',
              $e->getMessage(),
              array(
                  'status' => 403,
              )
          );
      }
  }

    }
endif;

WPUserRestAPI::init();
