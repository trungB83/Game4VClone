<?php
$wp_user_login_limit = get_option('wp_user_login_limit');
$wp_user_login_limit_time = get_option('wp_user_login_limit_time');

$wp_user_options_general = array(
    array(
        'type' => 'tab',
        'name' => __('General', 'wpuser'),
        'id' => 'general',
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_signup',
                'name' => __('Disable Signup Form', 'wpuser'),
                'description' => __('Disable user to register new account from front end', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_login_otp',
                'name' => __('Disable Login with OTP', 'wpuser'),
                'description' => __('Disable user to login with OTP from front end', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_login_otp_link',
                'name' => __('Disable Login with OTP Link', 'wpuser'),
                'description' => __('Disable user to login with OTP link in mail', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_group_myprofile',
                'name' => __('Disable create and join groups', 'wpuser'),
                'description' => __('If enable - Ability of each member to create and join groups from the front end profile page', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_view_myprofile',
                'name' => __('Disable View on My Profile', 'wpuser'),
                'description' => __('If enable - Display People Viewed List', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_posts',
                'name' => __('Disable Posts', 'wpuser'),
                'description' => __('If Disable - Hide post tab on user profile', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_posts_my_profile',
                'name' => __('Disable Blogs Link', 'wpuser'),
                'description' => __('If Disable - Hide Blogs link on My Profile', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_contact_form_myprofile',
                'name' => __('Disable Contact Form', 'wpuser'),
                'description' => __('Disable Contact Form from my profile section', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_send_mail_view_profile',
                'name' => __('Disable Send Mail', 'wpuser'),
                'description' => __('Disable Send Mail from view profile section', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_admin_bar',
                'name' => __('Disable Admin Bar', 'wpuser'),
                'description' => __(' Disable WordPress Admin Bar for All Users Except Admin', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_user_bar',
                'name' => __('Disable User Bar', 'wpuser'),
                'description' => __(' Disable User Notification Bar on Front end My Account', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_user_sidebar',
                'name' => __('Disable User Sidebar', 'wpuser'),
                'description' => __(' Disable Right Side Bar on Front end My Account', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'select',
                'id' => 'wp_user_default_status',
                'name' => __('Default New User Approval status', 'wpuser'),
                'description' => __('Set default user status when register new user', 'wpuser'),
                'options' => array(
                    __('Approve', 'wpuser') => '1',
                    __('Deny', 'wpuser') => '5',
                    __('Pending', 'wpuser') => '2',
                    __('Require E-mail Activation', 'wpuser') => '3'
                ),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'notification',
        'name' => __('Notifications', 'wpuser'),
        'description' => '',
        'icon' => 'fa fa-bell-o',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_user_notification',
                'name' => __('Disable User notification', 'wpuser'),
                'description' => __(' Disable user notification on Front end My Account', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_disable_user_notification_comment',
                'name' => __('Disable comment notification', 'wpuser'),
                'description' => __(' Disable user notification on new comment', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'rest_api',
        'name' => __('REST API', 'wpuser'),
        'description' => '',
        'icon' => 'fa fa-exchange',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_enable_rest_api',
                'name' => __('Enable REST API', 'wpuser'),
                'description' => __('Extends the WP REST API using JSON Web Tokens Authentication as an authentication method.', 'wpuser'),
                'icon' => '',
                'help_link' => 'https://wpuserplus.com/docs/category/rest-api/',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_enable_rest_api_key_auth',
                'name' => __('Enable API Key Auth Verification', 'wpuser'),
                'description' => __('REST API Only return response to Authorised API key.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wpuser_api_key',
                'name' => __('REST API Key', 'wpuser'),
                'description' => __('You need to pass api_key parameter in every request header if Enable API Key Auth Verification setting.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'term',
        'name' => __('Terms and Conditions', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_tern_and_condition',
                'name' => __('Enable Terms and Conditions', 'wpuser'),
                'description' => __(' Display and require user agreement to Terms and Conditions', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'textarea',
                'id' => 'wp_user_show_term_data',
                'name' => __('Terms and Conditions', 'wpuser'),
                'description' => __('Enter text or a URL starting with http. If you use a URL, the Terms and Conditions text will be replaced by a link to the appropiate page', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            )
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'appearance',
        'name' => __('Appearance and Look', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_appearance_icon',
                'name' => __('Field Icons', 'wpuser'),
                'description' => __('Disable Field Icons', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_tab_position_is_vertical',
                'name' => __('Display User menu tab vertically', 'wpuser'),
                'description' => __('Display User menu tab(My Account, Edit Profile etc.) vertically on user profile.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'textarea',
                'id' => 'wp_user_appearance_custom_css',
                'name' => __('Custom CSS Styles', 'wpuser'),
                'description' => __(' If you want to override existing styles, or add some specific CSS rules, put them here. They will survive the updates.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'select',
                'id' => 'wp_user_appearance_skin',
                'name' => __('Layout', 'wpuser'),
                'description' => __(' By default layout', 'wpuser'),
                'options' => array(
                    'default' => 'default',
                    'block' => 'block',
                    'block-2' => 'block-2',
                    'float' => 'float',
                    'rounded' => 'rounded'
                ),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            )
        )
    )
);
if (is_plugin_active('wp-user-subscription/wp_user_subscription.php')) {
    $subscription = array(
        'type' => 'checkbox',
        'id' => 'wp_user_enable_subscription',
        'name' => __('Enable Subscription', 'wpuser'),
        'description' => __('Enable subscription when new user register i.e Mailchimp, Aweber, Campain Monitor', 'wpuser'),
        'icon' => '',
        'help_link' => 'admin.php?page=wp-user-subscription',
        'help_description' => 'MailChimp, AWeber, Campaign Monitor setting',
    );
    array_push($wp_user_options_general[0]['fields'], $subscription);
}

if( is_plugin_active( 'supportcandy/supportcandy.php' ) ) {
    $support = array(
        'type' => 'checkbox',
        'id' => 'wp_user_disable_support_myprofile',
        'name' => __('Disable Support Section', 'wpuser'),
        'description' => __('Disable Support from my profile section', 'wpuser'),
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
    );
    array_push($wp_user_options_general[0]['fields'], $support);

}

if( is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) ) {
    $support = array(
        'type' => 'checkbox',
        'id' => 'wp_user_disable_wishlist_myprofile',
        'name' => __('Disable Wishlist Section', 'wpuser'),
        'description' => __('Disable Wishlist from my profile section', 'wpuser'),
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
    );
    array_push($wp_user_options_general[0]['fields'], $support);

}

$wp_user_options_security = array(
    array(
        'type' => 'tab',
        'name' => __('Limit Login Attempts', 'wpuser'),
        'id' => 'general',
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => 'Limit rate of login attempts and block IP temporarily. It is protecting from brute force attacks.',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_login_limit_enable',
                'name' => __('Enable Limit Login Attempts Setting', 'wpuser'),
                'description' => __('Blocking access to the login page after n unsuccessful login attempts', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_login_limit',
                'name' => __('Limit Login Attempts', 'wpuser'),
                'description' => __('Allowed retries : Allows user login ' . $wp_user_login_limit . ' attempts', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_login_limit_admin_notify',
                'name' => __('Notify on lockouts (Email to admin after)', 'wpuser'),
                'description' => __($wp_user_login_limit . ' lockouts', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_login_limit_time',
                'name' => __('Lockouts time', 'wpuser'),
                'description' => __('Minutes Lockout : Access will be blocked for ' . $wp_user_login_limit_time . ' minutes', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_enable_two_step_auth',
                'name' => __('Enable 2-Step Verification', 'wpuser'),
                'description' => __('With 2-Step Verification you add an extra layer of security to user account. After you set it up, user sign in to there account in two steps using password and OTP.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'term',
        'name' => __('Password (Form Validation & Security)', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => __('Example (Default)
            <br>$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
            <br>$ = beginning of string
            <br>\S* = any set of characters
            <br>(?=\S{8,}) = of at least length 8
            <br>(?=\S*[a-z]) = containing at least one lowercase letter
            <br>(?=\S*[A-Z]) = and at least one uppercase letter
            <br>(?=\S*[\d]) = and at least one number
            <br>(?=\S*[\W]) = and at least a special character (non-word characters)
            <br>$ = end of the string', 'wpuser'),
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_login_limit_password_enable',
                'name' => __('Enable Password Regular Expression', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_login_password_valid_message',
                'name' => __('Password Validation Message', 'wpuser'),
                'description' => __('Password Validation Message for front end user.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_login_limit_password',
                'name' => __('Password Regular Expression', 'wpuser'),
                'description' => __('The regex used to validate the Password fields specified above, please do not change this unless you know what you are doing.', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            )
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'block_ip',
        'name' => __('Blacklisting / Whitelisting IP addresses', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => __('', 'wpuser'),
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'textarea',
                'id' => ' wp_user_security_blacklist_ips',
                'name' => __('Blacklisting IP Addresses', 'wpuser'),
                'description' => '<a href="https://www.wpseeds.org/wordpress-security/block-an-ip-address-from-visiting-a-wordpress-website/" target="_blank">Blacklisting</a> - IP address to block a specific set of IP addresses. Enter Single IP addresses,IP ranges,IP masks
                <br>Enter IP (,) Comma separated like as follow :
                 <br>111.77.248.200,
                 <br>111.77.248.221,
                 <br>221.88.177.122-221.88.177.222,
                 <br>221.88.197.122-221.88.197.222,
                 <br>210.98.*.*.,
                 <br>211.78.100.*
                 <br>Leave Blank for disable this setting.',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'textarea',
                'id' => ' wp_user_security_whiltelist_ips',
                'name' => __('Whitelisting IP Addresses', 'wpuser'),
                'description' => '<a href="https://www.wpseeds.org/wordpress-security/whitelisting-ip-addresses-in-wordpress/" target="_blank">Whitelisting</a> - IP addresses are blocked except for specific IP addresses that are allowed.
                <br>Enter IP (,) Comma separated like as follow :
                 <br>111.77.248.200,
                 <br>111.77.248.221,
                 <br>221.88.177.122-221.88.177.222,
                 <br>221.88.197.122-221.88.197.222,
                 <br>210.98.*.*.,
                 <br>211.78.100.*
                 <br>Leave Blank for disable this setting.',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'reCAPTCHA',
        'name' => __('reCAPTCHA', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => __('1.Sign up for an API key at <a href="https://www.google.com/recaptcha/admin#createsite" target="_blank"> https://www.google.com/recaptcha/admin#createsite</a>
        <br>2.Check documentation for No CAPTCHA reCAPTCHA at  <a href="https://developers.google.com/recaptcha/intro" target="_blank"> https://developers.google.com/recaptcha/intro</a>', 'wpuser'),
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => ' wp_user_security_reCaptcha_enable',
                'name' => __('Enable reCAPTCHA', 'wpuser'),
                'description' => 'Captchas are meant to protect your website from spam and abuse.',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_security_reCaptcha_secretkey',
                'name' => __('Secret Key', 'wpuser'),
                'description' => __('', 'wpuser'),
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            )
        )
    ),

);
$wp_user_options_email = array(
    array(
        'type' => 'tab',
        'name' => __('Send Mail Settings', 'wpuser'),
        'id' => 'send_email',
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => '<h5 Variables!</h5>
                            The variables in {CURLY BRACKETS} are used to present data and info in email. You can use them to customize your email template.<br>
                            {WPUSER_ADMIN_EMAIL} Displays the admin email that users can contact you at. You can configure it under Mail settings.<br>
                            {WPUSER_BLOGNAME} Displays blog name<br>
                            {WPUSER_BLOG_URL} Displays blog URL<br>
                            {WPUSER_BLOG_ADMIN} Displays blog WP-admin URL<br>
                            {WPUSER_LOGIN_URL} Displays the login page<br>
                            {WPUSER_USERNAME} Displays the Username of user<br>
                            {WPUSER_FIRST_NAME} Displays the user first name<br>
                            {WPUSER_LAST_NAME} Displays the user last name<br>
                            {WPUSER_NAME} Displays the user display name or public name<br>
                            {WPUSER_EMAIL} Displays the E-mail address of user<br>
                            {WPUSER_NEW_PASSWORD} Display new password of user ',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'text',
                'id' => 'wp_user_email_name',
                'name' => __('The name that appears on mails sent by WP USER', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_email_id',
                'name' => __('The address that appears on mails sent by WP User', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            )
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'term',
        'name' => __('New Registration', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_email_admin_register_enable',
                'name' => __('Send an e-mail to admin when user is register', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_email_admin_register_subject',
                'name' => __('Subject', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'textarea',
                'id' => 'wp_user_email_admin_register_content',
                'name' => __('Email Content', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'checkbox',
                'id' => 'wp_user_email_user_register_enable',
                'name' => __('Send an e-mail to user when user is register', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'text',
                'id' => 'wp_user_email_user_register_subject',
                'name' => __('Subject', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'textarea',
                'id' => 'wp_user_email_user_register_content',
                'name' => __('Email Content', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),
    array(
        'type' => 'tab',
        'id' => 'forgot_password',
        'name' => __('Forgot Password', 'wpuser'),
        'description' => '',
        'icon' => '',
        'help_link' => '',
        'help_description' => '',
        'is_open' => 1,
        'fields' => array(
            array(
                'type' => 'text',
                'id' => 'wp_user_email_user_forgot_subject',
                'name' => __('Subject', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
            array(
                'type' => 'textarea',
                'id' => 'wp_user_email_user_forgot_content',
                'name' => __('Email Content', 'wpuser'),
                'description' => '',
                'icon' => '',
                'help_link' => '',
                'help_description' => '',
            ),
        )
    ),

);

if (class_exists('WC_Admin_Profile')) {
    $notification_order =
        array(
            'type' => 'checkbox',
            'id' => 'wp_user_disable_user_notification_order',
            'name' => __('Disable order notification', 'wpuser'),
            'description' => __(' Disable user notification on woocommerce order status changed to refund/complete', 'wpuser'),
            'icon' => '',
            'help_link' => '',
            'help_description' => '',
    );
    array_push($wp_user_options_general[1]['fields'], $notification_order);
}
