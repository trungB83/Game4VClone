<?php

// Creating the widget
class wpuserwidget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
        // Base ID of your widget
            'wpuserwidget',

            // Widget name will appear in UI
            __('WP-User', 'wp-user'),

            // Widget description
            array('description' => __('Login, Register, Forgot Password Form', 'wp-user'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = (is_user_logged_in()) ? apply_filters('widget_title', $instance['title_logged_in_user']) : apply_filters('widget_title', $instance['title']);
        $form_id = time() . rand(2, 999);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        global $wpdb;
        include_once('includes/assets.php');
        echo '<style>' . get_option('wp_user_appearance_custom_css') . '</style>';
        echo '<div class="bootstrap-wrapper wp_user support_bs">';
        if (is_user_logged_in()) {
            $wp_user_avatar_enable = isset($instance['wp_user_avatar_enable']) ? $instance['wp_user_avatar_enable'] : 0;
            global $current_user, $wp_roles;
            $attachment_url = esc_url(get_the_author_meta('user_meta_image', get_current_user_id()));
            $attachment_id = profileController::get_attachment_image_by_url($attachment_url);
            // retrieve the thumbnail size of our image
            $image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
            // return the image thumbnail
            if (!empty($image_thumb[0])) {
                $wp_user_profile_img = $image_thumb[0];
            } else if (!empty($attachment_url)) {
                $wp_user_profile_img = $attachment_url;
            } else {
                $args = get_avatar_data(get_current_user_id(), $args);
                if (!empty($args['url']))
                    $wp_user_profile_img = $args['url'];
                else
                    $wp_user_profile_img = WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
            }
            echo ' <div class="">
                             <div class="">
                             <div class="row">';
            if ($wp_user_avatar_enable) {
                echo '<div class="col-md-3">
                             <img class="wpuser_profile_img img-responsive img-circle" style="width:50px" src="' . $wp_user_profile_img . '" alt="User Avatar">                                             
                             </div>';
            }
            echo '<div class="col-md-9"> 
                            <h3 class="profile-username"> <a href="' . get_permalink(get_option('wp_user_page')) . '" title="My Account">' . $current_user->user_login . '</a></h3>';
            echo '<a href="' . wp_logout_url(get_permalink()) . '" class="" title="Logout">';
            _e('Logout', 'wpuser');
            echo '</a>';
            echo '</div>                 
                             </div>
                         </div>
                         <div class="">';
            do_action('wp_user_hook_widget');
            echo '   </div>
                        </div>';
        } else {
            $wp_user_icon_enable = isset($instance['wp_user_icon_enable']) ? $instance['wp_user_icon_enable'] : 0;
            $wp_user_register_enable = isset($instance['wp_user_register_enable']) ? $instance['wp_user_register_enable'] : 0;
            $wp_user_forgot_enable = isset($instance['wp_user_forgot_enable']) ? $instance['wp_user_forgot_enable'] : 0;
            $login_class = 'active';
            $login_redirect = "";
            $register_class = '';
            $forgot_class = '';
            include_once("view/appearance.php");
            include("view/widgetView.php");
            include('includes/script.php');
        }

        echo '</div>';
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance)
    {
        $title = (isset($instance['title'])) ? $instance['title'] : $title = __('Login ', 'wpuser');
        $title_logged_in_user = (isset($instance['title_logged_in_user'])) ? $instance['title_logged_in_user'] : $title = __('My Account ', 'wpuser');
        $wp_user_register_enable = (isset($instance['wp_user_register_enable'])) ? $instance['wp_user_register_enable'] : 'on';
        $wp_user_forgot_enable = (isset($instance['wp_user_forgot_enable'])) ? $instance['wp_user_forgot_enable'] : 'on';
        $wp_user_avatar_enable = (isset($instance['wp_user_avatar_enable'])) ? $instance['wp_user_avatar_enable'] : 'on';
        $wp_user_icon_enable = (isset($instance['wp_user_icon_enable'])) ? $instance['wp_user_icon_enable'] : 'on';

        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Logged-out title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label
                for="<?php echo $this->get_field_id('title_logged_in_user'); ?>"><?php _e('Logged-in title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title_logged_in_user'); ?>"
                   name="<?php echo $this->get_field_name('title_logged_in_user'); ?>" type="text"
                   value="<?php echo esc_attr($title_logged_in_user); ?>"/>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($wp_user_register_enable, 'on'); ?>
                   id="<?php echo $this->get_field_id('wp_user_register_enable'); ?>"
                   name="<?php echo $this->get_field_name('wp_user_register_enable'); ?>"/>
            <label
                for="<?php echo $this->get_field_id('wp_user_register_enable'); ?>"><?php _e('Show Register tab'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($wp_user_forgot_enable, 'on'); ?>
                   id="<?php echo $this->get_field_id('wp_user_forgot_enable'); ?>"
                   name="<?php echo $this->get_field_name('wp_user_forgot_enable'); ?>"/>
            <label
                for="<?php echo $this->get_field_id('wp_user_forgot_enable'); ?>"><?php _e('Show forgot password tab'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($wp_user_icon_enable, 'on'); ?>
                   id="<?php echo $this->get_field_id('wp_user_icon_enable'); ?>"
                   name="<?php echo $this->get_field_name('wp_user_icon_enable'); ?>"/>
            <label
                for="<?php echo $this->get_field_id('wp_user_icon_enable'); ?>"><?php _e('Show field icons'); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($wp_user_avatar_enable, 'on'); ?>
                   id="<?php echo $this->get_field_id('wp_user_avatar_enable'); ?>"
                   name="<?php echo $this->get_field_name('wp_user_avatar_enable'); ?>"/>
            <label
                for="<?php echo $this->get_field_id('wp_user_avatar_enable'); ?>"><?php _e('Show logged-in user avatar'); ?></label>
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['title_logged_in_user'] = (!empty($new_instance['title_logged_in_user'])) ? strip_tags($new_instance['title_logged_in_user']) : '';
        $instance['wp_user_register_enable'] = (isset($new_instance['wp_user_register_enable'])) ? strip_tags($new_instance['wp_user_register_enable']) : 0;
        $instance['wp_user_forgot_enable'] = (isset($new_instance['wp_user_forgot_enable'])) ? strip_tags($new_instance['wp_user_forgot_enable']) : 0;
        $instance['wp_user_avatar_enable'] = (isset($new_instance['wp_user_avatar_enable'])) ? strip_tags($new_instance['wp_user_avatar_enable']) : 0;
        $instance['wp_user_icon_enable'] = (isset($new_instance['wp_user_icon_enable'])) ? strip_tags($new_instance['wp_user_icon_enable']) : 0;

        return $instance;
    }
} // Class wpbdpwidget ends here


// Register and load the widget
function wpuserwidget_form()
{
    register_widget('wpuserwidget');
}

add_action('widgets_init', 'wpuserwidget_form');