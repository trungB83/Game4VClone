<?php global $wp_user_appearance_button_type ?>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane <?php echo $login_class ?>" id="loginController<?php echo $form_id ?>"
         class="login-box-body">
        <div>
            <div style="display: none;" id="wpuser_errordiv<?php echo $form_id ?>"
                 class="alert alert-dismissible fade in" role="alert"><label
                    id="upuser_error<?php echo $form_id ?>"></label></div>
            <form method="post" id="wpuser_login_form<?php echo $form_id ?>">
                <div class="form-group has-feedback">
                    <input type="text" id="wp_user_email_name<?php echo $form_id ?>"
                           placeholder="<?php _e('Username or Email', 'wpuser') ?>" required class="form-control"
                           name="wp_user_email_name">
                    <?php if ($wp_user_icon_enable) { ?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <?php } ?>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="wp_user_password<?php echo $form_id ?>" required class="form-control"
                           placeholder="<?php _e('Password', 'wpuser') ?>" name="wp_user_password">
                    <?php if ($wp_user_icon_enable) { ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <?php } ?>
                </div>
                <?php do_action('wp_user_hook_login_form') ?>
                <div class="row">

                    <div class="col-xs-12">
                        <input type="button" id="wpuser_login<?php echo $form_id ?>" class="wpuser_button btn <?php echo $wp_user_appearance_button_type?> btn-primary"
                               name="wpuser_login" value="<?php _e('Sign In', 'wpuser') ?>">
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <?php if ($wp_user_forgot_enable) { ?>
                <a href="#forgotController<?php echo $form_id ?>" aria-controls="forgotController<?php echo $form_id ?>"
                   role="tab" data-toggle="tab"><?php _e('Forgot Password', 'wpuser') ?></a><br>
            <?php }
            if ($wp_user_register_enable) { ?>
                <a href="#registerController<?php echo $form_id ?>"
                   aria-controls="registerController<?php echo $form_id ?>" role="tab" data-toggle="tab"
                   class="text-center"><?php _e('Sign Up', 'wpuser') ?></a>
            <?php } ?>
        </div>
    </div>
    <?php if ($wp_user_forgot_enable) { ?>
        <div role="tabpanel" class="tab-pane <?php echo $forgot_class ?>" id="forgotController<?php echo $form_id ?>">
            <div class="">
                <div style="display: none;" id="wpuser_errordiv_forgot<?php echo $form_id ?>"
                     class="alert alert-dismissible fade in" role="alert"><label
                        id="upuser_error_forgot<?php echo $form_id ?>"></label></div>
                <form method="post" id="wpuser_forgot_form<?php echo $form_id ?>">
                    <div class="form-group has-feedback">
                        <input type="text" id="wp_user_email_name_forgot<?php echo $form_id ?>" required
                               placeholder="<?php _e('Email', 'wpuser') ?>" class="form-control" name="wp_user_email">
                        <?php if ($wp_user_icon_enable) { ?>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <?php } ?>
                    </div>
                    <?php do_action('wp_user_hook_forgot_form') ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <input type="button" id="wpuser_forgot<?php echo $form_id ?>" class="wpuser_button btn <?php echo $wp_user_appearance_button_type?> btn-primary"
                                   name="forgot_password" value="<?php _e('Forgot', 'wpuser') ?>">

                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <a aria-controls="loginController<?php echo $form_id ?>" role="tab" data-toggle="tab"
                   href="#loginController<?php echo $form_id ?>"><?php _e('Sign In', 'wpuser') ?></a><br>
            </div>
        </div>
    <?php } ?>
    <?php if ($wp_user_register_enable) { ?>
        <div role="tabpanel" class="tab-pane <?php echo $register_class ?>"
             id="registerController<?php echo $form_id ?>">
            <div style="display: none;" id="wpuser_errordiv_register<?php echo $form_id ?>"
                 class="alert alert-dismissible" role="alert"><label
                    id="wpuser_error_register<?php echo $form_id ?>"></label></div>
            <form method="post" id="google_form<?php echo $form_id ?>">
                <input name="wpuser_update_setting" type="hidden"
                       value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>

                <div class="form-group has-feedback">
                    <input type="text" id="user_login<?php echo $form_id ?>" class="form-control"
                           name="user_login" placeholder="<?php _e('Username', 'wpuser') ?>"
                           required>
                    <?php if ($wp_user_icon_enable) { ?>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <?php } ?>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" id="user_email<?php echo $form_id ?>"
                           placeholder="<?php _e('Email', 'wpuser') ?>" required class="form-control"
                           name="user_email">
                    <?php if ($wp_user_icon_enable) { ?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <?php } ?>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="user_pass<?php echo $form_id ?>"
                           placeholder="<?php _e('Password', 'wpuser') ?>" required class="form-control"
                           name="user_pass">
                    <?php if ($wp_user_icon_enable) { ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <?php } ?>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="wp_user_re_password<?php echo $form_id ?>"
                           placeholder="<?php _e('Retype Password', 'wpuser') ?>" required class="form-control"
                           name="wp_user_re_password">
                    <?php if ($wp_user_icon_enable) { ?>
                        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    <?php } ?>
                </div>
                <?php

                do_action('wp_user_hook_register_form') ?>
                <?php if (get_option('wp_user_tern_and_condition')) { ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div>
                                <input id="wp_user_term_condition_widget" type="checkbox" name="wp_user_term_condition">
                                <?php _e('I agree to the', 'wpuser') ?> <a data-toggle="collapse" data-target="#wpuser_term_<?php echo $form_id ?>"><?php _e('terms', 'wpuser') ?></a>
                                <div id="wpuser_term_<?php echo $form_id ?>" class="collapse"><?php echo stripslashes(get_option('wp_user_show_term_data')) ?></div>
                            </div>
                            <br>
                        </div>
                    </div>

                <?php }

                if (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) { ?>

                    <div class="row">
                        <div class="col-xs-12">
                            <div id="recaptcha<?php echo $form_id ?>" class="g-recaptcha"
                                 data-sitekey="<?php echo get_option('wp_user_security_reCaptcha_secretkey') ?>"></div>
                            <input type="hidden" title="Please verify this" class="required" name="keycode"
                                   id="keycode">
                        </div>
                    </div>
                <?php } ?>

                <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <input type="button" class="wpuser_button btn btn-primary <?php echo $wp_user_appearance_button_type?>"
                               id="wpuser_register<?php echo $form_id ?>" name="wpuser_register"
                               value="<?php _e('Sign Up', 'wpuser') ?>">

                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <a aria-controls="loginController<?php echo $form_id ?>" role="tab" data-toggle="tab"
               href="#loginController<?php echo $form_id ?>" class="text-center"><?php _e('Sign In', 'wpuser') ?></a>
        </div>
    <?php } ?>
</div>