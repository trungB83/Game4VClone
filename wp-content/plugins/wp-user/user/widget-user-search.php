<?php

// Creating the widget
class wpuserSearchwidget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
        // Base ID of your widget
            'wpuserSearchwidget',

            // Widget name will appear in UI
            __('WP-User : Search Users', 'wp-user'),

            // Widget description
            array('description' => __('Search Users Filter', 'wp-user'),)
        );
    }

    // Creating widget front-end
    // This is where the action happens
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        $wp_user_form_id = $instance['wp_user_form_id'];
        $form_id = time() . rand(2, 999);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $attr = ( !empty($wp_user_form_id) ? 'id='.$wp_user_form_id : '' );

        echo do_shortcode('[wp_user_search type="widget" form_type="block" '.$attr.']');
        echo $args['after_widget'];
    }

    // Widget Backend 
    public function form($instance)
    {
        $title = (isset($instance['title'])) ? $instance['title'] : $title = __('Search ', 'wpuser');
        $wp_user_form_id = (isset($instance['wp_user_form_id'])) ? $instance['wp_user_form_id'] : '';

        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id('wp_user_form_id'); ?>"
                   name="<?php echo $this->get_field_name('wp_user_form_id'); ?>" placeholder="Enter Form id" type="text"
                   value="<?php echo esc_attr($wp_user_form_id); ?>"/>
            <label
                for="<?php echo $this->get_field_id('wp_user_form_id'); ?>"><?php _e('Enter Form Id for custom search field'); ?></label>
        </p>
        <?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['wp_user_form_id'] = (!empty($new_instance['wp_user_form_id'])) ? strip_tags($new_instance['wp_user_form_id']) : '';
        return $instance;
    }
} // Class wpbdpwidget ends here


// Register and load the widget
function wpuserSearchwidget_form()
{
    register_widget('wpuserSearchwidget');
}

add_action('widgets_init', 'wpuserSearchwidget_form');