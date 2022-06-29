<?php
add_action('add_meta_boxes', 'wpuser_add_meta_boxes');
function wpuser_add_meta_boxes()
{
    $metabox = wpuser_meta_fields();

    $post_types = get_post_types(array('public' => true, 'show_ui' => true), 'objects');
    foreach ($post_types as $page) {

        $exclude = apply_filters('wpuser_metabox_excluded_post_types', array(
            'forum',
            'topic',
            'reply',
            'product',
            'attachment'
        ));

        if (!in_array($page->name, $exclude)) {
            add_meta_box($metabox['id'], $metabox['title'], 'wpuser_metabox_setting', $page->name, $metabox['context'], $metabox['priority']);
        }
    }
}

function wpuser_meta_fields()
{
    $wp_roles = wp_roles();
    $options[''] = 'select';
    $options['logged_in'] = 'Logged in';
    foreach ($wp_roles->role_names as $role_names) {
        $options[str_replace(' ', '_', strtolower($role_names))] = $role_names;
    }
    $fields = array(
        'id' => 'wpuser_metabox',
        'title' => __('Restrict this content', 'wpuser'),
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(
            array(
                'name' => __('Role', 'wpuser'),
                'id' => 'wpuser_user_role',
                'type' => 'select',
                'desc' => __('Choose the user role that can see this page / post', 'wpuser'),
                'options' => $options
            )
        )
    );
    return apply_filters('wpuser_metabox_fields', $fields);
}

function wpuser_metabox_setting()
{
    global $post;
    $metabox = wpuser_meta_fields();

    echo '<input type="hidden" name="wpuser_update_setting" value="' . wp_create_nonce('wpuser-update-setting') . '" />';
    echo '<table class="form-table">';
    foreach ($metabox['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>';
        echo '<th style="width:20%"><label for="' . esc_attr($field['id']) . '">' . $field['name'] . '</label></th>';
        echo '<td>';
        switch ($field['type']) {
            case 'select':
                echo '<select name="' . esc_attr($field['id']) . '" id="' . esc_attr($field['id']) . '">';
                foreach ($field['options'] as $option => $label) {
                    echo '<option' . selected($meta, $option, false) . ' value="' . esc_attr($option) . '">' . $label . '</option>';
                }
                echo '</select>';
                break;
        }
        echo '<td>' . $field['desc'] . '</td><td>';
        echo '</tr>';
    }
    echo '</table>';
}

add_action('save_post', 'wpuser_save_post');
function wpuser_save_post($post_id)
{

    if (empty($_POST['wpuser_update_setting'])) {
        return;
    }

    // verify nonce
    if (!wp_verify_nonce($_POST['wpuser_update_setting'], 'wpuser-update-setting')) {
        return;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // check permissions
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

        if (!current_user_can('edit_page', $post_id)) {
            return;
        }

    } elseif (!current_user_can('edit_post', $post_id)) {

        return;

    }

    $metabox = wpuser_meta_fields();

    foreach ($metabox['fields'] as $field) {

        $old = get_post_meta($post_id, $field['id'], true);
        $new = isset($_POST[$field['id']]) ? sanitize_text_field($_POST[$field['id']]) : '';
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);

        }
    }
}