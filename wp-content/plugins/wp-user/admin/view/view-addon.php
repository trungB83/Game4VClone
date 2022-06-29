<?php
$installed_count = 0;
$addons = array(
    array(
        'name' => __('Social Login', 'wpuser'),
        'addon_name' => __('Social Login Addon for Wp User', 'wpuser'),
        'addon' => 'wp_user_social_login',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-social-login/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-social-login/wp_user_social_login.php',
        'description' => __('WP User Social Login allow your website readers and customers to login and register on using
         their existing social accounts IDs', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/wpuser-social-login.png',
        'documentation_url' => WPUSER_DOC_URL
    ),
    array(
        'name' => __('Subscription', 'wpuser'),
        'addon_name' => __('Subscription Addon for WP User', 'wpuser'),
        'addon' => 'wp_user_subscription',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-subscription/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-subscription/wp_user_subscription.php',
        'description' => __('WP User Subscription is a simple but powerful WP User Addon which supports MailChimp, Aweber and Campaign Monitor.
        On new user registration subscribe user', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/Wordpress-Mailchimp-Email-Signup-Forms-wp-user.jpg',
        'documentation_url' => WPUSER_DOC_URL
    ),
   
    array(
        'name' => __('Multiple Registration Forms ', 'wpuser'),
        'addon_name' => __('Form Builder Addon for WP User', 'wpuser'),
        'addon' => 'wp_user_multiple_forms',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-multiple-forms/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-form-builder/userplus.php',
        'description' => __('Create multiple front-end registration forms (Show different fields each form).
            Unlimited <b>custom fields</b>.', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/wp-user-multiple-form-addon.png',
        'documentation_url' => WPUSER_DOC_URL
    ),
    array(
        'name' => __('Addresses Book ', 'wpuser'),
        'addon_name' => __('WP User Multiple Addresses', 'wpuser'),
        'addon' => 'wp_user_multiple_addresses',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-multiple-addresses/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-multiple-addresses/wp_user_multiple_addresses.php',
        'description' => __('Add Multiple Addresses and set shipping and billing address. Integration with WooCommerce', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/wp-user-addon-multiple-address-woocommerce.jpeg',
        'documentation_url' => WPUSER_DOC_URL
    ),
    array(
        'name' => __('User Profile Completeness', 'wpuser'),
        'addon_name' => __('WP User Profile Completeness', 'wpuser'),
        'addon' => 'wp_user_profile_completeness',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-profile-completeness/',
        'addon_url_buy' =>WPUSER_PRO_URL,
        'addon_path' => 'wp-user-profile-completeness/wp_user_profile_completeness.php',
        'description' => __('Show the percentage of user profile completion.', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/wp-user-profile-completeness.jpeg',
        'documentation_url' => WPUSER_DOC_URL
    ),
    array(
        'name' => __('Customize Layout', 'wpuser'),
        'addon_name' => __('WP User Layout', 'wpuser'),
        'addon' => 'wp_user_layout',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-layout/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-layout/wp_user_layout.php',
        'description' => __('Customize skin color,buttons, link, box, form background etc.', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/wpuser-logintemplate.jpg',
        'documentation_url' => WPUSER_DOC_URL
    ),
    array(
        'name' => __('Social Network', 'wpuser'),
        'addon_name' => __('WP User Social Network', 'wpuser'),
        'addon' => 'wp_user_social_network',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-social-network/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-social-network/wp_user_social_network.php',
        'description' => __('Automatically or manually assign badges to users based on different criteria,  Follow / Unfollow Feature In addition, It adds followers list, and following list', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/wp-user-social-networking.jpg',
        'documentation_url' => WPUSER_DOC_URL
    ),
 array(
        'name' => __('WooCommerce integration ', 'wpuser'),
        'addon_name' => __('WooCommerce integration Addon For WP User', 'wpuser'),
        'addon' => 'wp-user-woo',
        'addon_url' => 'http://wpuserplus.com/blog/wp-user-woo/',
        'addon_url_buy' => WPUSER_PRO_URL,
        'addon_path' => 'wp-user-woo/wp-user-woo.php',
        'description' => __('Integrates Woo orders and purchases with user profiles', 'wpuser'),
        'img' => 'http://wpuserplus.com/wp-content/uploads/2017/11/woocommerce-wp-user-integration.jpg',
        'documentation_url' => WPUSER_DOC_URL
    ),	
);
?>
<div class="bootstrap-wrapper hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include('view-header.php'); ?>
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    <?php _e('WP User', 'wpuser'); ?>
                    <small> <?php _e('Add-ons', 'wpuser'); ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> <?php _e('Home', 'wpuser'); ?></a></li>
                    <li class="active"><?php _e('Add-ons', 'wpuser'); ?></li>
                </ol>
            </section>
            <section class="content">
                <div ng-controller="addon">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="nav-tabs-custom">

                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="/settingController#tab_1"
                                                          aria-expanded="true"><?php _e('Pro Add-ons', 'wpuser') ?></a>
                                    </li>
                                    <li class=""><a data-toggle="tab" href="/settingController#tab_Features"
                                                    aria-expanded="true"><?php _e('Features', 'wpuser') ?></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab_1" class="tab-pane active">

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h2 class="page-header">
                                                    <i class="fa fa-plug"></i> <?php _e('WP User Add-ons') ?>
                                                    <?php if (WPUSER_TYPE=='FREE') { ?>
                                                    <small
                                                        class=""><?php _e('Get all add-ons access and Premium Support with', 'wpuser'); ?>
                                                        <a target="_blank" href="<?php echo WPUSER_PRO_URL ?>">
                                                            <?php _e('Personal/Business/Developer', 'wpuser') ?> </a>
                                                        <?php _e('Pack (All Pro Features)', 'wpuser'); ?>.
                                                    </small>
                                                    <?php } ?>
                                                </h2>
                                            </div>
                                            <div class="col-md-12">
                                                <?php foreach ($addons as $addon) {
                                                    ?>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="box box-solid" style="min-height: 230px">
                                                            <div class="box-body">
                                                                <h4 style="background-color:#f7f7f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;">
                                                                    <?php echo $addon['name'] ?>
                                                                </h4>
                                                                <div class="media">
                                                                    <div class="media-left">
                                                                        <a target="_blank"
                                                                           href="<?php echo $addon['addon_url'] ?>"
                                                                           class="ad-click-event">
                                                                            <img src="<?php echo $addon['img'] ?>"
                                                                                 alt="<?php echo $addon['addon_name'] ?>"
                                                                                 class="media-object"
                                                                                 style="width: 150px;height: auto;border-radius: 4px;box-shadow: 0 1px 3px rgba(0,0,0,.15);">
                                                                        </a>
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <div class="clearfix">
                                                                            <p class="pull-right">
                                                                                <?php if (is_plugin_active($addon['addon_path'])) {
                                                                                    echo '<a href="' . WPUserAdminGlobal::generatePluginActivationLinkUrl($addon['addon_path'], 'deactivate') . '" class="btn btn-block btn-success btn-flat">';
                                                                                    _e('Deactivate', 'wpuser');
                                                                                    echo '</a>';
                                                                                    $installed_count++;
                                                                                } else if (WPUserAdminGlobal::is_plugin_installed($addon['addon_name'])) {
                                                                                    echo '<a href="' . WPUserAdminGlobal::generatePluginActivationLinkUrl($addon['addon_path']) . '" class="btn btn-block btn-default btn-flat">';
                                                                                    _e('Activate', 'wpuser');
                                                                                    echo '</a>';
                                                                                    $installed_count++;
                                                                                } else {
                                                                                    echo '<a target="_blank" href="' . $addon['addon_url_buy'] . '" class="btn btn-block btn-primary btn-flat">';
                                                                                    _e('Buy Now', 'wpuser');
                                                                                    echo '</a>';
                                                                                } ?>

                                                                                </a>
                                                                            </p>

                                                                            <a target="_blank"
                                                                               href="<?php echo $addon['addon_url'] ?>"
                                                                               class="ad-click-event"><h4
                                                                                    style="margin-top: 0"><?php echo $addon['addon_name'] ?></h4>
                                                                            </a>

                                                                            <p><?php echo $addon['description'] ?></p>
                                                                            <p style="margin-bottom: 0">
                                                                                <a target="_blank"
                                                                                   href="<?php echo WPUSER_DOC_URL ?>">
                                                                                    <i class="fa fa-book margin-r5"
                                                                                       title="<?php _e('Documentation', 'wpuser') ?>"></i></a>
                                                                                <a target="_blank"
                                                                                   href="<?php echo WPUSER_SUPPORT_URL ?>">
                                                                                    <i class="fa fa-support  margin-r5"
                                                                                       title="<?php _e('Support', 'wpuser') ?>"></i></a>
                                                                                <a target="_blank"
                                                                                   href="<?php echo WPUSER_PRO_URL ?>">
                                                                                    <i class="fa fa-shopping-cart margin-r5"
                                                                                       title="<?php _e('Buy Now', 'wpuser') ?>"></i></a>

                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="addon-footer">
                                            <div class="pull-right hidden-xs">
                                                <a target="_blank" href="<?php echo WPUSER_SUPPORT_URL ?>"> <i
                                                        class="fa fa-support  margin-r5"
                                                        title="<?php _e('Support', 'wpuser') ?>"></i> <?php _e('Paid Customization', 'wpuser') ?>
                                                </a>
                                            </div>
                                            <?php if (count($addons) != ($installed_count) && WPUSER_TYPE=='FREE') { ?>
                                                <h4><strong>Get All <a target="_blank"
                                                                       href="<?php echo WPUSER_PRO_URL ?>">Pro
                                                            Feature</a> (All
                                                        Add-ons).</strong> <?php echo WPUSER_COUPON ?></h4>
                                            <?php } else {
                                                echo '<h4><a target="_blank" href="<?php echo WPUSER_SUPPORT_URL ?>"> <i class="fa fa-support  margin-r5"></i> ';
                                                _e('Support', 'wpuser');
                                                echo '</a></h4>';
                                            } ?>
                                        </div>

                                    </div>
                                    <div id="tab_Features" class="tab-pane">

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h2 class="page-header">
                                                    <?php _e('Free') ?>
                                                </h2>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                    <ul>
                                                        <li>
                                                        <li class="fa fa-user"></li>
                                                        <strong>Login : Login with Username or Email Id</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user"></li>
                                                        <strong>Registration</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user"></li>
                                                        <strong>Forgot Password</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user"></li>
                                                        View Profile</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user"></li>
                                                        Edit Profile</li>
                                                        <li>
                                                        <li class="fa fa-user-secret"></li>
                                                        <strong>security</strong>
                                                        <ul>
                                                            <li>
                                                            <li class="fa fa-fw fa-user-secret"></li>
                                                            <strong>Limit Login</strong> Attempts</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user-secret"></li>
                                                            Mechanism for slow down brute force attack</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user-secret"></li>
                                                            Notify on lockout (Email to admin after cross limit the
                                                            number of login attempts)</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user-secret"></li>
                                                            Password Regular Expression (Form Validation &amp; Security
                                                            )</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user-secret"></li>
                                                            Google <strong>reCAPTCHA</strong></li>
                                                            <li>
                                                            <li class="fa fa-fw fa-file-text-o"></li>
                                                            <strong>View Login Log.</strong></li>
                                                        </ul>
                                                        </li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user"></li>
                                                        <strong>Front-end profile</strong>
                                                        <ul>
                                                            <li>
                                                            <li class="fa fa-fw fa-user"></li>
                                                            View/Edit user information user front end dashboard</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user"></li>
                                                            <strong>User Avatar</strong> : for users to upload images or
                                                            enter url to their profile</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user"></li>
                                                            Change the Default Gravatar</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-user"></li>
                                                            View/Edit billing, shipping Address on user dashboard</li>
                                                        </ul>
                                                        <li class="fa fa-fw fa-users"></li>
                                                        <strong>Member Directory.</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-users"></li>
                                                        Member Pagination,Search.</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-users"></li>
                                                        View Member Profile.</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-envelope"></li>
                                                        Send Mail to Member.</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-unlock-alt"></li>
                                                        Restrict an entire post or page.</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-unlock-alt"></li>
                                                        Restrict section of content within a post/page.</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-key"></li>
                                                        Logged in or selected role users only access content.</li>
                                                        <li>


                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul>
                                                        <li>
                                                        <li class="fa fa-envelope"></li>
                                                        <strong>Email Notification</strong>
                                                        <ul>
                                                            <li>
                                                            <li class="fa fa-fw fa-envelope"></li>
                                                            New Registration</li>
                                                            <li>

                                                            <li class="fa fa-fw fa-envelope"></li>
                                                            Email to admin after cross limit the number of login
                                                            attempts</li>
                                                            <li>
                                                            <li class="fa fa-fw fa-envelope"></li>
                                                            Custom email subject,content</li>
                                                        </ul>
                                                        </li>
                                                        <li class="fa fa-fw fa-users"></li>
                                                        <strong>Admin : User list with pagination,Search</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-file-excel-o"></li>
                                                        <strong>Admin : Export Users CSV</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user-secret"></li>
                                                        <strong>Approve/Deny User.</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-user-secret"></li>
                                                        <strong>Auto / Email Approval user.</strong></li>
                                                        <li>
                                                        <li>
                                                        <li class="fa fa-fw fa-file-code-o"></li>
                                                        Auto <strong>Generate page</strong> for Login,Register</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-eraser"></li>
                                                        Enable / <strong>Disable Admin Bar</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-desktop"></li>
                                                        Templates : 4 login,register front end
                                                        <strong>templates</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-tv"><strong></li>
                                                        Customizable CSS</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-spinner"></li>
                                                        AJAX based verification for username and email accounts</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-spinner"></li>
                                                        Add smooth ajax login/registration effects</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-tv"></li>
                                                        Login redirection</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-file-text-o"></li>
                                                        Login/registration/forgot password <strong>popup model</strong>
                                                        : You can create one popup that contains all 3 with a great
                                                        interface for switching between them</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-rocket"></li>
                                                        Light weight plugin</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-tv"></li>
                                                        login,register , forgot password form using shortcode, widget,
                                                        popup</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-mobile"></li>
                                                        <strong>Responsive</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-cubes"></li>
                                                        MultiSite</li>
                                                        <li>
                                                        <li class="fa fa-fw fa-globe"></li>
                                                        Multi language</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <h2 class="page-header">
                                                    <?php _e('Pro') ?>
                                                </h2>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                    <ul>
                                                        <li>
                                                        <li class="fa fa-list-alt"></li>
                                                        <strong>Ultimate Registration form</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-list"></li>
                                                        <strong>Custom form fields</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-picture-o"></li>
                                                        <strong>Custom profile background image</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-list"></li>
                                                        <strong>Create required fields</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-facebook"></li>
                                                        <li class="fa fa-fw fa-google"></li>
                                                        <li class="fa fa-fw fa-twitter"></li>
                                                        <strong>Social Login/Register i.e
                                                            Facebook,Google,Twitter</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-map-marker"></li>
                                                        <strong>Add / Edit / Delete / Duplicate Multiple Address</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-map-pin"></li>
                                                        <strong>Get user current location(address) using Geolocation</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-book"></li>
                                                        <strong>Set defualt WooCommerce billing/shipping address from address list</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-shopping-cart"></li>
                                                        <strong>Select WooCommerce billing/shipping address from address book on checkout page</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-trophy"></li>
                                                        <strong>Badges and Achievements </strong> - Automatically or manually assign badges to users based on different criteria’s like
                                                            <ul>
                                                                <li><li class="fa fa-fw fa-trophy"></li> Specific user roles</li>
                                                                <li><li class="fa fa-fw fa-trophy"></li> Based on activity score i.e Number of posts, comments, followers etc.</li>
                                                                <li><li class="fa fa-fw fa-trophy"></li> Admin can manually assign badge</li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <ul>
                                                        <li class="fa fa-fw fa-users"></li>
                                                        <strong>Follow / Unfollow Feature </strong>
                                                        <ul>
                                                            <li><li class="fa fa-fw fa-user-plus"></li> The follow/ unfollow feature lets users follow other users. </li>
                                                            <li><li class="fa fa-fw fa-users"></li> Whenever a user posts, all the followers will receive a notification regarding the update.</li>
                                                            <li><li class="fa fa-fw fa-users"></li> Keeps your user community more interactive and engaging.</li>
                                                        </ul>
                                                        </li>
                                                        <li class="fa fa-fw fa-envelope-o"></li>
                                                        <strong>Subscription newslatter on new user Registration with
                                                            MailChimp, Aweber and Campaign Monitor</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-line-chart"></li>
                                                        <strong>Show the percentage of user profile
                                                            completion.</strong>
                                                        </li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-line-chart"></li>
                                                        <strong>On click Improve button it will show highlighted fields for improve profile strength.</strong>
                                                        </li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-line-chart"></li>
                                                        <strong>Set custom weight for field</strong>
                                                        </li>
                                                        <li>
                                                        <li class="fa fa-fw  fa-line-chart"></li>
                                                        <strong>Profile progress on member profile</strong>
                                                        </li>
                                                        <li>
                                                        <li class="fa fa-fw fa-support"></li>
                                                        <strong>Premium Support</strong></li>
                                                        <li>
                                                        <li class="fa fa-fw fa-briefcase"></li>
                                                        <strong>New features added regularly!</strong></li>
                                                        <li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="addon-footer">
                                            <div class="pull-right hidden-xs">
                                                <a target="_blank" href="<?php echo WPUSER_SUPPORT_URL ?>"> <i
                                                        class="fa fa-support  margin-r5"
                                                        title="<?php _e('Support', 'wpuser') ?>"></i> <?php _e('Paid Customization', 'wpuser') ?>
                                                </a>
                                            </div>
                                            <?php if (count($addons) != ($installed_count) && WPUSER_TYPE=='FREE') { ?>
                                                <h4><strong>Get All <a target="_blank"
                                                                       href="<?php echo WPUSER_PRO_URL ?>">Pro
                                                            Feature</a> (All
                                                        Add-ons).</strong> <?php echo WPUSER_COUPON ?></h4>
                                            <?php 
                                            update_option('wpuser_hide_coupon',0);
                                            } else {
                                                update_option('wpuser_hide_coupon',1);
                                                echo '<h4><a target="_blank" href="<?php echo WPUSER_SUPPORT_URL ?>"> <i class="fa fa-support  margin-r5"></i> ';
                                                _e('Support', 'wpuser');
                                                echo '</a></h4>';
                                            } ?>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div><!-- /.row -->

                </div>
            </section>
            <?php include('view-footer.php'); ?>
        </div><!-- /.aj -->
    </div>
</div>


