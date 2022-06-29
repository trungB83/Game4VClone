<?php
$wpuser_admin_setting_tab=array();
$wp_user_options_general = apply_filters('wp_user_option_filter_general', $wp_user_options_general);
$wp_user_options_security = apply_filters('wp_user_option_filter_security', $wp_user_options_security);
$wp_user_options_email = apply_filters('wp_user_option_filter_email', $wp_user_options_email);
$wpuser_admin_setting_tab = apply_filters('wp_user_option_admin_setting_tab', $wpuser_admin_setting_tab);


//print_r($wp_user_options_general);die;
//$setting_tabs=WPUserAdminGlobal::getColumn($wp_user_options,'name');


?>

<div ng-controller="settingController">
    <div class="row">
        <div class="col-md-12">
            <div class="response_message" id="response_message"></div>
            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="/settingController#tab_1"
                                          aria-expanded="true"><?php _e('General', 'wpuser') ?></a></li>
                    <li class=""><a data-toggle="tab" href="/settingController#tab_2"
                                    aria-expanded="false"><?php _e('Page Setup', 'wpuser') ?></a></li>
                    <li class=""><a data-toggle="tab" href="/settingController#tab_setting"
                                    aria-expanded="false"><?php _e('Tab setting', 'wpuser') ?></a></li>
                    <li class=""><a data-toggle="tab" href="/settingController#tab_security"
                                    aria-expanded="false"><?php _e('Security', 'wpuser') ?></a></li>
                    <li class=""><a data-toggle="tab" href="/settingController#tab_3"
                                    aria-expanded="false"><?php _e('Email Notifications', 'wpuser') ?></a></li>
                    <?php 
                    if(!empty($wpuser_admin_setting_tab)){
                     foreach ($wpuser_admin_setting_tab as $tab => $setting_tab) {
                        echo ' <li class="' . $setting_tab['active'] . '"><a class="" href="#' . $tab . '" data-toggle="tab">' . $setting_tab['tab'] . '</a></li>';
                    }
                    }
                    ?>
                    <li class=""><a data-toggle="tab" href="/settingController#tab_help"
                                    aria-expanded="false"><?php _e('Help', 'wpuser') ?></a></li>
                    <li class=""><a data-toggle="tab" href="/settingController#tab_subscribe"
                                    aria-expanded="false"><?php _e('Subscribe', 'wpuser') ?></a></li>
                    <li class="pull-right"><a class="text-muted" href="#"><i class="fa fa-gear"></i></a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab_1" class="tab-pane active">
                        <form action="" name="wpuser_update_setting" id="wpuser_update_setting" method="post">
                            <input name="wpuser_update_setting" type="hidden"
                                   value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>
                            <?php foreach ($wp_user_options_general as $options_general) { ?>
                                <div class="box box-default box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo (!empty($options_general['icon'])) ? '<i class="' . $options_general['icon'] . '"></i>' : '' ?><?php echo $options_general['name'] ?></h3>
                                        <div class="box-tools">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="box-body">
                                            <?php foreach ($options_general['fields'] as $field) { ?>
                                                <div class="form-group row row">
                                                    <div class="col-md-3"><label
                                                            for="<?php echo $field['id'] ?>"><?php echo $field['name'] ?> </label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <?php echo WPUserAdminGlobal::createField($field) ?>
                                                        <?php echo $field['description'] ?>
                                                        <?php if (!empty($field['help_description'])) {
                                                            echo '<br><a href="' . $field['help_link'] . '">' . $field['help_description'] . '</a>';
                                                        } ?>
                                                    </div>

                                                </div>
                                            <?php }
                                            $plugin_action = 'wpuser_setting_' . $options_general['id'];
                                            do_action($plugin_action);
                                            ?>


                                        </div><!-- /.box-body -->
                                    </div><!-- /.box-body -->
                                </div><!-- /. box -->
                            <?php } ?>
                            <button type="button" id="update_setting" class="btn btn-primary btn-flat">
                                <?php _e('Update', 'wpuser') ?>
                            </button>
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                    <div id="tab_2" class="tab-pane">
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title ng-binding"><?php _e('Page', 'wpuser') ?></h3>
                                <div class="box-tools">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <form action="" name="wpuser_update_page_setting" id="wpuser_update_page_setting"
                                      method="post">
                                    <input name="wpuser_update_setting" type="hidden"
                                           value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>

                                    <div class="box-body">
                                        <div class="form-group row row">
                                            <div class="col-md-3"><label
                                                    class="ng-binding"><?php _e('WP User Page', 'wpuser') ?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <?php
                                                $wp_user_page_id = get_option('wp_user_page');
                                                $wp_user_page_permalink = get_permalink($wp_user_page_id);
                                                $wp_user_page_title = get_the_title($wp_user_page_id);
                                                $wp_user_member_page_id = get_option('wp_user_member_page');
                                                $wp_user_member_page_permalink = get_permalink($wp_user_member_page_id);
                                                $wp_user_member_page_title = get_the_title($wp_user_member_page_id);
                                                ?>
                                                <a title="User" id="wp_user_page_permalink" target="_blank"
                                                   href="<?php echo $wp_user_page_permalink ?>">
                                                    <button type="button"
                                                            class="btn btn-block btn-default btn-flat ng-binding"><?php _e('View Page', 'wpuser') ?></button>
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="ng-binding"
                                                      id="wp_user_page_permalink_text"><?php echo $wp_user_page_permalink ?></span>
                                            </div>
                                        </div>

                                        <div class="form-group row row">
                                            <div class="col-md-3"><label
                                                    class="ng-binding" class="wp_user_member_page"
                                                    id="wp_user_member_page"><?php _e('WP User Members Page', 'wpuser') ?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <a title="Members" target="_blank" id="wp_user_member_page_permalink"
                                                   href="<?php echo $wp_user_member_page_permalink ?>">
                                                    <button type="button"
                                                            class="btn btn-block btn-default btn-flat ng-binding"><?php _e('View Page', 'wpuser') ?></button>
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <span ng-bind="wp_user_member_page.permalink"
                                                      class="ng-binding"
                                                      id="wp_user_member_page_text"><?php echo $wp_user_member_page_permalink ?></span>
                                            </div>

                                        </div>


                                    </div><!-- /.box-body -->

                                    <div class="box-footer ng-binding">
                                        <div class="form-group row row">
                                            <div class="col-md-3"><label
                                                    class="ng-binding"><?php _e('Page Title', 'wpuser') ?></label></div>
                                            <div class="col-md-3">
                                                <input type="text" name="wp_user_page_title"
                                                       value="<?php echo $wp_user_page_title ?>"
                                                       class="ng-pristine ng-valid">
                                            </div>
                                        </div>

                                        <div class="form-group row row">
                                            <div class="col-md-3"><label
                                                    class="ng-binding"><?php _e('Member Page Title', 'wpuser') ?></label>
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" name="wp_user_member_page_title"
                                                       value="<?php echo $wp_user_member_page_title ?>"
                                                       class="ng-pristine ng-valid">
                                            </div>
                                        </div>
                                        <label class="btn btn-primary" name="update_page_setting"
                                               id="update_page_setting">    <?php _e('Rebuild Pages', 'wpuser') ?></label>
                                        <br>
                                        <?php _e('OR Use [wp_user] shortcode for display login, registration, forgot password form.', 'wpuser') ?>
                                        <br>
                                        <?php _e('Use [wp_user_member] shortcode for display members/users list.', 'wpuser') ?>

                                    </div>
                                </form>
                            </div><!-- /.box-body -->
                        </div><!-- /. box -->
                    </div>

                    <!-- /.tab-pane -->
                    <div id="tab_setting" class="tab-pane">
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title ng-binding"><?php _e('Profile Page Tab Setting', 'wpuser') ?></h3>
                                <div class="box-tools">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row row">
                                <div class="col-md-12">
                                    <div class="alert alert-info alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×
                                        </button>
                                        <h4>
                                            <i class="icon fa fa-info"></i>  <?php _e('Help', 'wpuser') ?></h4>
                                        <?php _e('Using this setting you can add new tab in user menu (below My Account, Edit Profile) which give users the freedom to have more choice within their own profile
                                             <br>These option would enable you to add Shortcodes from other plugins like feeds, comments, favourites, among others 
                                             <br>You can add Text, HTML, Shortcode, href in tab content section. 
                                             <br>You can display tab for only to selected user role. 
                                             <br>For change tab order drag tab section up/down.', 'wpuser') ?>
                                </div>
                            </div>
                                </div>
                            <div class="box-body">
                                    <div class="box box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title ng-binding"><?php _e('Custom Tabs', 'wpuser') ?></h3>
                                        </div>
                                        <div class="box-body">

                                            <?php wpuserTabSetting::my_profile_tab() ?>

                                        </div>
                                    </div>

                            </div><!-- /.box-body -->
                        </div><!-- /. box -->
                    </div>


                    <!-- /.tab-pane -->
                    <div id="tab_security" class="tab-pane">
                        <form action="" name="wpuser_update_security_setting" id="wpuser_update_security_setting"
                              method="post">
                            <input name="wpuser_update_setting" type="hidden"
                                   value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>
                            <?php foreach ($wp_user_options_security as $options_general) { ?>
                                <div class="box box-default box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $options_general['name'] ?></h3>
                                        <div class="box-tools">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="box-body">
                                            <?php
                                            if (!empty($options_general['help_description'])) {
                                                ?>
                                                <div class="alert alert-info alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×
                                                    </button>
                                                    <h4>
                                                        <i class="icon fa fa-info"></i><?php echo $options_general['name'] ?>
                                                    </h4>
                                                    <?php echo $options_general['help_description']; ?>
                                                </div>
                                                <?php
                                            }
                                            foreach ($options_general['fields'] as $field) { ?>
                                                <div class="form-group row row">
                                                    <div class="col-md-3"><label
                                                            for="<?php echo $field['id'] ?>"><?php echo $field['name'] ?> </label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <?php echo WPUserAdminGlobal::createField($field) ?>
                                                        <br><?php echo $field['description'] ?>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div><!-- /.box-body -->
                                    </div><!-- /.box-body -->
                                </div><!-- /. box -->
                            <?php } ?>
                            <?php do_action('wpuser_setting_security') ?>
                            <button type="button" id="update_security_setting" class="btn btn-primary btn-flat">
                                <?php _e('Update', 'wpuser') ?>
                            </button>
                        </form>
                    </div>

                    <!-- /.tab-pane -->
                    <div id="tab_3" class="tab-pane">
                        <form action="" name="wpuser_update_email_setting" id="wpuser_update_email_setting"
                              method="post">
                            <input name="wpuser_update_setting" type="hidden"
                                   value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>
                            <?php foreach ($wp_user_options_email as $options_general) { ?>
                                <div class="box box-default box-solid">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><?php echo $options_general['name'] ?></h3>
                                        <div class="box-tools">
                                            <button class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="box-body">
                                            <?php
                                            if (!empty($options_general['help_description'])) {
                                                ?>
                                                <div class="alert alert-info alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert"
                                                            aria-hidden="true">×
                                                    </button>
                                                    <h4>
                                                        <i class="icon fa fa-info"></i><?php echo $options_general['name'] ?>
                                                    </h4>
                                                    <?php echo $options_general['help_description']; ?>
                                                </div>
                                                <?php
                                            }
                                            foreach ($options_general['fields'] as $field) { ?>
                                                <div class="form-group row row">
                                                    <div class="col-md-3"><label
                                                            for="<?php echo $field['id'] ?>"><?php echo $field['name'] ?> </label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <?php echo WPUserAdminGlobal::createField($field) ?>
                                                        <br><?php echo $field['description'] ?>
                                                    </div>

                                                </div>
                                            <?php } ?>

                                        </div><!-- /.box-body -->
                                    </div><!-- /.box-body -->
                                </div><!-- /. box -->
                            <?php } ?>
                            <button type="button" id="update_email_setting" class="btn btn-primary btn-flat">
                                <?php _e('Update', 'wpuser') ?>
                            </button>
                        </form>

                    </div>
                    <!-- /.tab-pane -->
                    <?php
                    if(!empty($wpuser_admin_setting_tab)) {
                        foreach ($wpuser_admin_setting_tab as $tab => $setting_tab) {
                            echo '<div class="tab-pane ' . $setting_tab['active'] . '" id="' . $tab . '">                                    ';
                            $WPclass = $setting_tab['class'];
                            $WPfunction = $setting_tab['function'];
                            $WPclass::$WPfunction();
                            echo '</div>';
                        }
                    }?>
                    <!-- /.tab-pane -->
                    <div id="tab_help" class="tab-pane">

                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php _e("Shortcode", 'wpuser') ?></h3>
                                <div class="box-tools">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <div class="box-body">
                                    <?php _e("
                                    <b> [wp_user] </b> shortcode for display login, registration, forgot password form.<br>
                                    You Can use following attributes for custom form<br>
                                    <b>[wp_user id='1234' width='360px' popup='1' active='register' role='subscriber' login_redirect='" . get_site_url() . "' layout='default' skin='blue']</b><br>
                                    <b> id </b> : If Multiple Form Add-on activated then create form and set id='form_id'. 
                                      You can use diffrent registration form for diffrent page.<br>
                                       Ex. [wp_user id='1234']<br>
                                      
                                    <b> width </b> : set custom width to login, registration, forgot password form.<br>
                                     [wp_user width='360px']<br>
                                     
                                    <b> popup </b>:  set  popup='1' shortcode for popuup model login, registration, forgot password form.<br>
                                    Ex. [wp_user popup='1']<br>
                                    
                                    <b> active </b>: For activate default form. By default login.<br>
                                    [wp_user active='register' popup='1'] shortcode for popuup model login, registration, forgot password form. default active registration form<br>
                                    [wp_user active='register'] for display default active registration form.(sign up page)<br>
                                    [wp_user active='forgot'] shortcode for display login, registration, forgot password form. default active forgot form<br>
                                    
                                    <b> role </b>: Set role for new register user via register form. You can set diffrent role for diffrent form. By default subscriber role<br>
                                    Ex. [wp_user role='subscriber']<br>
                                    
                                     <b> login_redirect </b>: Custom login redirection url for each login form.<br>
                                    Ex. [wp_user login_redirect='" . get_site_url() . "']  <br>
                                    
                                    <b>layout</b> : Set diffrent layout to diffrent form
                                     Ex. [wp_user layout='default'] <br>
                                     
                                     <b>skin</b> : Set diffrent skin color to diffrent form
                                     Ex. [wp_user skin='blue']
                                    
                                         <hr><br>                            
                                    <b> [wp_user_member] </b> shortcode for display member list/directory<br>
                                     You can use following attributes for filter/show member list <br>
                                     <b>[wp_user_member role_in='subscriber' role_not_in='author' include='1,2,5,7' exclude='55,44,78,87' approve='1' size='small']</b><br>
                                      <b>role_in </b> : If you want to show only selected member role in list then set this attribute by comma seprated<br>
                                      Ex. [wp_user_member role_in='subscriber,author']<br>
                                       
                                       <b>role_not_in </b> : If you want exclude to show some user roles in member list then set this attribute by comma seprated<br>
                                      Ex. [wp_user_member role_not_in='subscriber,author']<br>
                                      
                                      <b>include </b> : If you want only show selected user ids then set this attribute by comma seprated<br>
                                      Ex. [wp_user_member include='1,2,5,7' ]<br>
                                      
                                       <b>exclude </b> : If you don't want show selected user ids then set this attribute by comma seprated<br>
                                      Ex. [wp_user_member exclude='55,44,78,87' ]<br>
                                      
                                      <b>approve </b> : If you want show only approve user then set approve='1'<br>
                                      Ex. [wp_user_member approve='1' ]<br>
                                      
                                      <b>size </b> : If you want change default display member list template to small one then set size='small'<br>
                                      Ex. [wp_user_member size='small' ]<br>
                                      <hr>
                                      <b> [wp_user_restrict] your restricted content goes here [/wp_user_restrict]</b> 
                                       shortcode for Restrict Content to registered users only. logged in users only access content<br>                                      
                                       To restrict just a section of content within a post or page, you may use above shortcodes<br>
                                       You can also set user role for access content.<br>
                                       You can use role attribute for only access content to selected user role:<br>
                                       Ex. [wp_user_restrict role='author,editor'] your restricted content goes here [/wp_user_restrict]<br>
                                       Ex. [wp_user_restrict role='author'] your restricted content goes here [/wp_user_restrict]<br>
                                       Ex. [wp_user_restrict role='logged_in'] your restricted content goes here [/wp_user_restrict] : logged in users only access content<br>
                                       To restrict an entire post or page, simply select the user role you’d like to restrict the post or page to from the drop down menu added just below the post/page editor.

                                     

                                  
                                     
                                    .<br>", 'wpuser') ?>
                                </div>
                                <!-- /.box-body -->
                            </div><!-- /.box-body -->
                        </div><!-- /. box -->

                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title"> <?php _e("Disable Signup Form", 'wpuser') ?></h3>
                                <div class="box-tools">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body no-padding">
                                <div class="box-body">
                                    <?php _e("Go to Dashboard->WP User ->General <br>
                                            1) Click on 'Disable Signup Form' check box.<br> 
                                            2) Click on 'Update' for Save setting", 'wpuser') ?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box-body -->
                        </div><!-- /. box -->

                    </div>
                    <div id="tab_subscribe" class="tab-pane">

                        <!-- Begin MailChimp Signup Form -->
                        <link href="//cdn-images.mailchimp.com/embedcode/slim-10_7.css" rel="stylesheet"
                              type="text/css">
                        <style type="text/css">
                            #mc_embed_signup {
                                background: #fff;
                                clear: left;
                                font: 14px Helvetica, Arial, sans-serif;
                            }

                            /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                               We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                        </style>
                        <div id="mc_embed_signup">
                            <form
                                action="https://wpseeds.us11.list-manage.com/subscribe/post?u=e82affc7dc8dacb76c07be2b6&amp;id=6b022be7e9"
                                method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                                class="validate" target="_blank" novalidate>
                                <div id="mc_embed_signup_scroll">
                                    <label for="mce-EMAIL">Subscribe to our mailing list for new updates,tips
                                        etc.</label>
                                    <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL"
                                           placeholder="email address" required>
                                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input
                                            type="text" name="b_e82affc7dc8dacb76c07be2b6_6b022be7e9" tabindex="-1"
                                            value=""></div>
                                    <div class="clear"><input type="submit" value="Subscribe" name="subscribe"
                                                              id="mc-embedded-subscribe" class="button"></div>
                                </div>
                            </form>
                        </div>

                        <!--End mc_embed_signup-->
                    </div>


                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div><!-- /.row -->
    </div>
</div><!-- /.aj -->






