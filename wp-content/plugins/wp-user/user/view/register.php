<div role="tabpanel" class="tab-pane <?php echo $register_class ?>"
     id="registerController<?php echo $form_id ?>">
    <div class="box">
        <div class="wpuser_form_header box-header with-border">
            <h3 class="box-title"><?php _e('Sign Up', 'wpuser') ?></h3>
        </div>
        <div class="box-body">
            <div style="display: none;" id="wpuser_errordiv_register<?php echo $form_id ?>"
                 class="alert alert-dismissible" role="alert"><label
                    id="wpuser_error_register<?php echo $form_id ?>"></label></div>
            <form method="post" onsubmit="return false" id="google_form<?php echo $form_id ?>">
                <input name="wpuser_update_setting" type="hidden"
                       value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                        if (isset($atts['role']) && !empty($atts['role'])) {
                            echo '<input name="role" type="hidden"
                       value="' . $atts['role'] . '">';
                        }
                        if (isset($atts['email_login_redirect']) && !empty($atts['email_login_redirect'])) {
                            echo '<input name="email_login_redirect" type="hidden"
                       value="' . $atts['email_login_redirect'] . '">';
                        }
                        do_action('wp_user_hook_register_form_header');
                        if (isset($atts['id']) && !empty($atts['id'])) {
                            echo '<input name="wpuser_form_id" type="hidden"
                       value="' . $atts['id'] . '">';
                            global $userplus;
                            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
                            $form_fields = get_post_meta($atts['id'], 'fields', true);;
                            if ($userplus_field_order) {
                                $fields_count = count($userplus_field_order);
                                for ($i = 0; $i < $fields_count; $i++) {
                                    $key = $userplus_field_order[$i];
                                    $array = $form_fields[$key];
                                    echo profileController::edit_fields($key, $array, $wp_user_appearance_skin, $form_id, null);
                                }
                            }

                        } else if(isset($atts['multi_steps']) && !empty($atts['multi_steps'])) {
                            $forms = explode(',', $atts['multi_steps']);
                            $booleanStep = (isset($atts['step']) && 'horizontal' == $atts['step'] ) ? 1 : 0;
                            if( $booleanStep ) {
                            ?>
                            <div class="wpuser-steps steps">
                                <?php
                                $intFormSteps = count($forms);
                                if ( $intFormSteps > 1 ) {
                                    for ($i = 1; $i <= $intFormSteps; $i++) { ?>
                                        <div class="wpuser_steps">
                                            <a class="wpuser_step_disable" href="#step_<?php echo $forms[$i-1] ?>"
                                               aria-controls="profile" role="tab" data-toggle="tab">
                                                <span id="step_count_<?php echo $forms[$i-1] ?>" class="badge bg-gray"><?php echo $i ?></span>
                                            </a>
                                        </div>
                                    <?php }
                                }
                                ?>
                            </div>
                            <style>
                                .steps {
                                    width: 100% !important;
                                    display: inline-flex !important;
                                }

                                .wpuser_steps {
                                    width: <?php echo 100/$intFormSteps ?>% !important;
                                    text-align: center;

                                }
                            </style>
                            <div class="tab-content">
                            <?php
                       }
                            $i = 1;
                            foreach ($forms as $form){
                                if( $booleanStep && $intFormSteps > 1 ) {
                                    $isActiveClass =  (1 == $i) ? "active": '';
                                    echo '<div role="tabpanel" class="tab-pane '.$isActiveClass.'" id="step_' . $form . '">';
                                }
                                echo do_shortcode('[wp_user_form id=' . $form . ' type="register"]');

                                if( $booleanStep && $intFormSteps > 1 ) {
                                    if( 1 <= $i && $intFormSteps != $i ){
                                       echo ' <button type="button" data-next="' . $forms[$i] . '" data-current="' . $form . '" data-toggle="tab" class="step_btn_next pull-right btn btn-primary">Next</button>';
                                    }

                                    if( 1 != $i ){
                                        echo ' <button type="button" id="step_btn_' . $form . '" data-prev="' . $forms[$i-2] . '" data-current="' . $form . '" class="step_btn_prev pull-right btn btn-default">Back</button>';
                                    }

                                    echo '</div>';
                                    $i++;
                                }
                            }

                            if( $booleanStep ) {
                                echo '</div>';
                            }
                            echo '<input name="wpuser_form_ids" type="hidden"
                       value="' . $atts['multi_steps'] . '">';
                        }else {
                            foreach ($wp_user_options_signup_form as $array) {
                                echo profileController::edit_fields($array['meta_key'], $array, $wp_user_appearance_skin, $form_id);
                            }
                        }
                        do_action('wp_user_hook_register_form');

                        if (get_option('wp_user_tern_and_condition')) { ?>
                            <div class="col-xs-12">
                                <div class="float form-group has-feedback">
                                    <label class="float form-group has-feedback container_checkbox">
                                        <input id="wp_user_term_condition" type="checkbox" name="wp_user_term_condition">
                                        <span class="checkmark"></span>
                                    </label>
                                    <?php _e('I agree to the', 'wpuser') ?> <a data-toggle="collapse" data-target="#wpuser_term"><?php _e('terms', 'wpuser') ?></a>
                                    <div id="wpuser_term" class="collapse"><?php echo stripslashes(get_option('wp_user_show_term_data')) ?></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php if (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) { ?>

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
                    <br>
                    <!-- /.col -->
                    <div class="col-xs-12">
                        <input type="submit" class="wpuser_button btn <?php echo $wp_user_appearance_button_type ?> btn-primary"
                               id="wpuser_register<?php echo $form_id ?>" name="wpuser_register"
                               value="<?php _e('Sign Up', 'wpuser') ?>">

                    </div>
                    <div class="col-xs-12">
                        <?php do_action('wp_user_hook_register_form_footer') ?>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <div class="navtabs box-footer">
            <a aria-controls="loginController<?php echo $form_id ?>" role="tab" data-toggle="tab"
               href="#loginController<?php echo $form_id ?>" class="text-center"><?php _e('Sign In', 'wpuser') ?></a>
        </div>
    </div>
</div>
