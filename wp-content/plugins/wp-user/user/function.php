<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class profileController
{
    static $rowCount = 1;

    public static function get_attachment_image_by_url($url)
    {

        // Split the $url into two parts with the wp-content directory as the separator.
        $parse_url = explode(parse_url(WP_CONTENT_URL, PHP_URL_PATH), $url);

        // Get the host of the current site and the host of the $url, ignoring www.
        $this_host = str_ireplace('www.', '', parse_url(home_url(), PHP_URL_HOST));
        $file_host = str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));

        // Return nothing if there aren't any $url parts or if the current host and $url host do not match.
        if (!isset($parse_url[1]) || empty($parse_url[1]) || ($this_host != $file_host)) {
            return;
        }

        // Now we're going to quickly search the DB for any attachment GUID with a partial path match.
        // Example: /uploads/2013/05/test-image.jpg
        global $wpdb;

        $prefix = $wpdb->prefix;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts WHERE guid LIKE %s;", '%s'.$parse_url[1]));

        // Returns null if no attachment is found.
        return isset($attachment[0]) ? $attachment[0] : null;
    }

    public static function view_fields($key, $array, $form_type = 'default', $form_id = 0, $user_id = null)
    {
        global $userplus;
        $readonly = '';
        $image='';
        $res='';
        $fieldClass ='';
        $divCol = ($form_type == 'block-2') ? 6 : ( ($form_type == 'block-4') ? 3 : 12 ) ;
        if(!($form_type == 'block-2' || $form_type == 'block-4')) {
            $fieldClass = ((self::$rowCount % 2) != 0 && $array['type'] != 'image_upload') ? 'wpuser_odd' : 'wpuser_even';
        }
        $form_type = ($form_type == 'block-2' ) ? 'block' : $form_type ;
        $value = (!empty($array['default_value'])) ? $array['default_value'] : '';
        $wp_user_appearance_icon = get_option('wp_user_appearance_icon');
        $default_image = WPUSER_PLUGIN_URL."assets/images/profiledefault.png";

        if( isset( $array['privacy'] ) && !empty( $array['privacy'] )){
          $boolIsprivacyPermission = true;
          $boolIsdenyPermission = true;
          $boolIsprivacyPermission = apply_filters('wpuser_filter_user_privacy_permission', $boolIsdenyPermission, $array['privacy'],$key,$user_id );
          if( false == $boolIsprivacyPermission ){
              return false;
          }
        }

        if (isset($user_id) & $user_id!=null) {
            $value = SELF::wpuser_profile_details($key, $user_id);
             if(empty($value)){
                 return false;
             }

            if ($array['type'] == 'image_upload' && $default_image == $value) {
                return '';
                /*  $default_image = WPUSER_PLUGIN_URL."assets/images/profiledefault.png";
                  $value = "<img src='".$default_image."' class='default'>";
                */
            }

            if ($array['type'] == 'image_upload' && !in_array($array['meta_key'],array('user_meta_image','profile_pic'))) {


                    $image = '<div class="col-sm-12 text-center">
                      <img class="wpuser_viewimage user-img profile_background_pic" id="' . $array['meta_key'] . '" src="' . $value . '"  alt="' . $array['label'] . '">
                    </div>';
                    if (isset($array['label'])) {
                        $image .= '<div class="col-sm-12 text-center">
                    <label class="text-center">
                        ' . $array['label'] . '
                    </label>
                    </div>';
                    }


            }


        }
        $data = '';
        foreach ($array as $data_option => $data_value) {
            if (!is_array($data_value) && $data_option != 'edit_choices') {
                $data .= " data-$data_option='$data_value'";
            }
        }
        $style='';

        $rowClass = ($array['type'] != 'image_upload') ? $divCol : 4 ;
        $field = '<div class="view_profile col-sm-12 col-md-'.$rowClass.' '.$fieldClass.'">';
        //$field .= ' <div class="' . $form_type . ' form-group has-feedback">';
        if ( ($form_type == 'float' || $form_type == 'block' || $form_type == 'rounded' || $array['type'] == 'textarea') && $array['type'] != 'image_upload') {
            $style= "style='margin-bottom: 10px;'";
            $classLabel = ($form_type == 'float') ? 'col-md-4 col-sm-12' : '';
            $classLabel = ($form_type == 'rounded') ? 'col-md-4 col-sm-12' : $classLabel;
            $field .= '<label class="col-md-6 col-sm-12 ' . $classLabel . '" for="' . $array['meta_key'] . $form_id . '">';
                $field .= '<span style="margin-right: 10px;" class="skin">';
                $icon = !empty( $array['icon']) ?  $array['icon'] : 'fa-circle-o';
                $field .= '<span class="fa ' . $icon . '"></span> ';
                $field .= '</span>';

            $field .= isset($array['label']) ? $array['label'] :'';

            $field .= ': </label> ';
        }
        $fieldClass= '';
        $rowClass = ($array['type'] != 'image_upload') ? 8 : 12 ;
        if ($form_type == 'float') {
            $field .= '<span class="col-md-'.$rowClass.' col-sm-12">';
           // $fieldClass= 'col-xs-8 col-sm-12';
        } else if ($form_type == 'rounded') {
           // $fieldClass= 'col-xs-8 col-sm-12';
            $field .= '<span class="col-md-'.$rowClass.' col-sm-12">';
        }else{
            $field .= '<span class="col-md-6 col-sm-12">';
        }

        switch ($array['type']) {

            case 'text':
            case 'textarea':
            case 'url':

                $field .= "<span id='" . $array['meta_key'] . "' class='" .$fieldClass." ". $array['meta_key'] . "'>" . $value . "</span>";
                break;


            case 'image_upload':

                if(isset($user_id)) {
                    $field .= $image;
                }

                break;

            case 'radio':
                if (isset($array['edit_choices'])) {
                    $array['edit_choices'] = explode("\r\n", $array['edit_choices']);
                    foreach ($array['edit_choices'] as $k => $v) {
                        $v = stripslashes($v);
                        if (checked($v, $value, 0)) {
                            $field .= "<span class='" .$fieldClass." ". $array['meta_key'] . "'>".$v."</span>";
                        }
                    }
                }
                break;

            case 'checkbox':
                if (isset($array['edit_choices'])) {
                    if (!empty($array['edit_choices']))
                        $array['edit_choices'] = explode("\r\n", $array['edit_choices']);
                    foreach ($array['edit_choices'] as $v) {
                        $v = stripslashes($v);
                        if ((is_array($value) && in_array($v, $value)) || $v == $value) {
                            $field .= "<span class='" .$fieldClass." ". $array['meta_key'] . "'>".$v."</span>";
                        }
                    }
                }
                break;

            case 'select':
                if(empty($readonly)) {
                    if (isset($array['edit_choices'])) {
                        $options = explode("\r\n", $array['edit_choices']);
                        $options_count = count($options);
                        for ($i = 0; $i < $options_count; $i++) {
                            if ((!empty($options[$i]) || $options[$i] != null) && selected($options[$i], $value, 0)) {
                                $field .= "<span class='" .$fieldClass." ". $array['meta_key'] . "'>$options[$i] </span>";
                            }
                        }
                    }
                }
                break;

            case 'multiselect':
                break;
           case 'date':
              if( false == empty($value)){
                 $dateTime = date_create( $value );
                 $value = date_format( $dateTime,"d-m-Y");
               }
            default :
                $field .= "<span id='" . $array['meta_key'] . "' class='" .$fieldClass." ". $array['meta_key'] . "'>" . $value . "</span>";


        }
        if(isset($array['description']) && !empty($array['description'])){
            $field .= '<p>'.$array['description'].'</p>';
        }

        if ($form_type == 'float' || $form_type == 'rounded') {
            $field .= "</span>";
        }

        $field .= "</div>";
        //  $field .= "</div>";
       // $field .= "<div class='userplus-clear'></div>";
        ++self::$rowCount;
        return $field;
    }

    public static function edit_fields($key, $array, $form_type = 'default', $form_id = 0, $user_id = null, $form = 'default', $type = 'default')
    {
        global $userplus;
        $readonly = '';
        $image='';
        $res='';
        $value = (!empty($array['default_value'])) ? $array['default_value'] : '';
        $wp_user_appearance_icon = get_option('wp_user_appearance_icon');

        if($array['meta_key'] == 'user_login' && null != $user_id){
            return '';
        }

        if ($array['type'] == 'image_upload') {
            /*  $default_image = WPUSER_PLUGIN_URL."assets/images/profiledefault.png";
              $value = "<img src='".$default_image."' class='default'>";
            */
        }
        if (isset($user_id) & $user_id!=null) {
            $value = SELF::wpuser_profile_details($key, $user_id);


            if ($array['type'] == 'image_upload' && !in_array($array['meta_key'],array('user_meta_image','profile_pic'))) {
                if (empty($value)) {
                    $value = WPUSER_PLUGIN_URL . "assets/images/profiledefault.png";
                }
                $image ='<div class="col-sm-12">
                      <img class="img-responsive user-img profile_background_pic" id="view_'.$array['meta_key'].'" src="'. $value . '"  alt="Image">
                    </div>';
            }
            if (isset($array['user_edit']) && $array['user_edit']=='1' && is_user_logged_in()) {
              //  $readonly = 'readonly';
            }
        }
        $is_required = ( isset($array['is_required']) && $array['is_required'] == 1 && 'search' != $type ) ? 'required' : '';
        $data = '';
        foreach ($array as $data_option => $data_value) {
            if (!is_array($data_value) && $data_option != 'edit_choices') {
                $data .= " data-$data_option='$data_value'";
            }
        }
        $elementId = $array['meta_key'].$form_id;
        $style='';
        $divCol = ($form_type == 'block-2' && $form != 'login') ? 6 : ( ($form_type == 'block-4') ? 2 : 12 ) ;
        $form_type = ($form_type == 'block-2' ) ? 'block' : $form_type ;
        $form_type = ($form_type == 'block-4' ) ? 'default' : $form_type ;
        $field = '<div class="col-xs-12 col-md-'.$divCol.' col-sm-12">';
        $field .= ' <div id="div_' . $elementId . '" class="' . $form_type . ' form-group has-feedback">';
        if ($form_type == 'float' || $form_type == 'block' || $form_type == 'rounded') {
            $style= "style='margin-bottom: 10px;'";
            $classLabel = ($form_type == 'float') ? 'col-xs-2' : '';
            $classLabel = ($form_type == 'rounded') ? 'col-xs-4' : $classLabel;
            $field .= '<label class="' . $classLabel . '" for="' . $elementId . '">';
            if ($form_type == 'rounded' && !$wp_user_appearance_icon && !empty($array['icon'])) {
                $field .= '<span style="margin-right: 10px;" class="skin_rounded">';
                $field .= '<span class="fa ' . $array['icon'] . '"></span> ';
                $field .= '</span>';
            }
            $field .= isset($array['label']) ? $array['label'] :'';
            if (isset($array['is_required']) && $array['is_required'] == 1) {
                $field .= "<span class='userplus-required'>*</span>";
            }

            $field .= '</label>';
        }
        if ($form_type == 'float') {
            $field .= '<div class="col-xs-10">';
        } else if ($form_type == 'rounded') {
            $field .= '<div class="col-xs-8">';
        }

        switch ($array['type']) {

            case 'text':
                $field .= "<input $style type='text' $is_required class='form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'number':
                $field .= "<input $style type='number' step='any' $is_required class='form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'range':
                $intMaxLength = (isset($array['max_length']) && !empty($array['max_length'])) ? (int) $array['max_length'] : 100 ;
                $intMinLength = (isset($array['min_length']) && !empty($array['min_length'])) ? (int) $array['min_length'] : 0 ;
                $intSteps = (int) $intMaxLength / 10 ;
                $field .= ' <input
                                type="text"
                                name="'. $array['meta_key'] .'"
                                class=\'form-control\'
                                data-provide="slider"
                                data-slider-min="'.$intMinLength.'"
                                data-slider-max="'.$intMaxLength.'"
                                data-slider-step="'.$intSteps.'"
                            >';
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'email':
                $field .= "<input $style type='email' $is_required class='form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'date':
                $field .= "<input $style type='date' $is_required class='form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'time':
                $field .= "<input $style type='time' $is_required class='wpuser_timepicker form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'tel':
                $field .= "<input $style type='tel' $is_required class='wpuser_phone form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'textarea':
                $field .= "<textarea  type='text' class='form-control' name='" . $array['meta_key'] . "' placeholder='" . $array['placeholder'] . "' id='" . $elementId . "' $data $readonly />$value</textarea>";
                break;

            case 'password':
                $field .= "<input $style type='password' $is_required class='form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' placeholder='" . $array['placeholder'] . "' autocomplete='off'/ $data>";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                if (isset($array['add_confirm_password_field']) && $array['add_confirm_password_field'] && is_user_logged_in()) {
                    $field .= "<div class='userplus-clear'></div>";
                    $field .= "<label for='confirm_pass'>" . __('Confirm Password', 'wpuser') . "</label>";
                    $field .= "<input type='password' class='form-control' name='confirm_pass' id='confirm_pass' autocomplete='off' data-is_required=1 />";
                }
                break;
            case 'image_upload':
                /* $output = get_user_meta( $user_id, $key, true );
                 if(!is_array($output) && strpos(str_replace(' ','',$output),'">')===0)
                 {
                     $output = substr_replace(trim($output), "", 0,2);
                 }
                 $allowed_types = implode(',',$array['allowed_types']);
                 $field .= "<div class='userplus-image' data-remove_text='".__('Remove','wpuser')."'>".$value."</div>";
                 $field .= "<div class='userplus-image-upload' data-filetype='picture' data-allowed_extensions=".$allowed_types.">".$array['upload_button_text']."</div>";
                 $field .= "<input data-required='".$array['is_required']."' type='hidden' name='".$array['meta_key']."' id='".$array['meta_key']."' value='".$output."' />";
               */
                if(isset($user_id) && 0 != $user_id) {
                    $field .= $image . '
                     <div class="input-group input-group-sm user_meta_image">
                        <input type="text" name="' . $array['meta_key'] . '" id="img_' . $elementId . '" value="' . $value . '" class="form-control" />
                    <span style="vertical-align: top;" class="input-group-btn">
                      <button type="button" id="' . $elementId . '" class="additional-user-image btn btn-info btn-flat" value="Upload Image"/>Upload Image</button>
                    </span>
                   </div>
                   ';
                }else{
                    $image = ( in_array( $array['meta_key'], ['user_meta_image', 'profile_pic'] ) ) ? 'wpuser' : 'image';
                    $field .= '
                           <div class="col-sm-12">
                           <div id="loader" style="display: none;position: fixed;top: 50%; left: 45%;z-index: 99999;">
                                 <img src="'.WPUSER_PLUGIN_ASSETS_URL .'/images/icon_loading.gif">
                            </div>
                           <span id="img_delete_' . $elementId . '" onclick="delete_img(\'' . $elementId . '\', \''.$image.'\')" style="display: none" class="pull-right fa fa-trash text-red"></span>
                          <img class="profile-user-img img-responsive" style="width: 150px;height: 150px" height="150" width="150" id="img_view' . $elementId . '" src="'.WPUSER_PLUGIN_URL . 'assets/images/'.$image.'.png" alt="Photo">
                        <br>
                        </div>
                        <div class="input-group input-group-sm">
                        <input type="file" id="upload_img' . $elementId . '" value="' . $value . '" class="upload_input form-control" />
                        <input type="hidden" name="' . $array['meta_key'] .'" id="upload' . $elementId . '" value="' . $value . '" />
                    <span style="vertical-align: top;" class="input-group-btn">
                      <button type="button" id="upload_img_btn' . $elementId . '" class="upload_img btn btn-info btn-flat" value="Upload"/>Upload</button>
                    </span>
                   </div>';

                    $field .="<script>jQuery('#upload_img_btn" . $elementId . "').on('click', function() {
                      jQuery('#loader').show();
    var file_data = jQuery('#upload_img". $elementId ."').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
   // alert(form_data);
     // alert(file);
    jQuery.ajax({
        url: '".admin_url('admin-ajax.php')."?action=wpuser_upload_action', // point to server-side PHP script
        dataType: 'text',  // what to expect back from the PHP script, if anything
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
             var parsed = $.parseJSON(data);
            jQuery('#img_view" . $elementId . "').attr('src', parsed.url);
            jQuery('#upload" . $elementId . "').val(parsed.url);
            jQuery('#img_delete_" . $elementId . "').show();
            jQuery('#loader').hide();
        }
     });
});</script>";
                }
                break;


            case 'url':
                $field .= "<input type='text' $is_required class='form-control' name='" . $array['meta_key'] . "' id='" . $elementId . "' value='" . $value . "' placeholder='" . $array['placeholder'] . "' $data $readonly />";
                if (!$wp_user_appearance_icon && !empty($array['icon']) && $form_type != 'rounded') {
                    $field .= '<span class="fa ' . $array['icon'] . ' form-control-feedback"></span>';
                }
                break;

            case 'radio':
                if (isset($array['edit_choices'])) {
                    $array['edit_choices'] = explode("\r\n", $array['edit_choices']);
                    $field .= "<div class='userplus-radio-container' data-required='" . $array['is_required'] . "'>";
                    foreach ($array['edit_choices'] as $k => $v) {

                        $v = stripslashes($v);
                        $field .= "<label class='wpuser-radio'><span";
                        if (checked($v, $value, 0)) {
                            $res = 'checked';
                        }
                        $field .= '></span><span class="container_radio"><input class="'.$res.'" '.$is_required.' type="radio" value="' . $v . '" name="' . $array['meta_key'] . '" ';
                        $field .= checked($v, $value, 0);
                        $field .= " /><span class=\"checkmark\"></span></span>  </label>".ucwords($v);
                    }
                    $field .= "</div>";
                }
                break;

            case 'checkbox':

                if (isset($array['edit_choices'])) {
                    $field .= "<div class='userplus-checkbox-container' data-required='" . $array['is_required'] . "'>";
                    if (!empty($array['edit_choices']))
                        $array['edit_choices'] = explode("\r\n", $array['edit_choices']);
                    foreach ($array['edit_choices'] as $v) {
                        $v = stripslashes($v);
                        $field .= "<label class='userplus-checkbox'><span";
                        if ((is_array($value) && in_array($v, $value)) || $v == $value) {
                            $res = 'checked';
                        }
                        $field .= '></span><input class="'.$res.'" '.$is_required.' type="checkbox" value="' . $v . '" name="' . $array['meta_key'] . '[]" ';
                        $field .= " />".ucwords($v)."</label>";
                    }
                    $field .= "</div>";
                }
                break;

            case 'select':
                if(empty($readonly)) {
                    $field .= "<select name='" . $array['meta_key'] . "' id='" . $elementId . "' class='form-control chosen-select' data-placeholder='" . $array['placeholder'] . "' $data >";
                    $default_value = ( !empty($array['default_value'])) ? $array['default_value'] : 'null';
                    $default_label = ( !empty($array['default_value'])) ? $array['default_value'] : isset($array['label']) ? $array['label'] :'Select';
                    $field .= "<option value='$default_value'>" .$default_label . "</option>";
                    if (isset($array['edit_choices'])) {
                        $options = explode("\r\n", $array['edit_choices']);
                        $options_count = count($options);
                        for ($i = 0; $i < $options_count; $i++) {
                            if (!empty($options[$i]) || $options[$i] != null) {
                                $field .= "<option value='" . $options[$i] . "'" . selected($options[$i], $value, 0) . ">" . ucwords($options[$i]) . "</option>";
                            }
                        }
                    }
                    $field .= "</select>";
                }
                break;

            case 'multiselect':
                break;

        }
        $field .= '<span for="'. $elementId .' " id="error' . $elementId . '" class="wpuser_error" style="display: none"></span>';
        if(isset($array['description']) && !empty($array['description'])){
            $field .= '<p>'.$array['description'].'</p>';
        }
        if(isset($array['help_text']) && !empty($array['help_text'])){
            $field .= '<p class="text-muted text-left">'.$array['help_text'].'</p>';
        }

        if ($form_type == 'float' || $form_type == 'rounded') {
            $field .= "</div>";
        }

        $field .= "</div>";
        $field .= "</div>";

        //$field .= "<div class='userplus-clear'></div>";
        return $field;
    }

    public static function wpuser_profile_details( $field, $user_id ) {
        global $userplus;
        $user = get_userdata( $user_id );
        $output = '';
        if ($user != false) {
            switch($field){
                default:
                    $output = get_user_meta( $user_id, $field, true );
                    if(!is_array($output) && strpos(str_replace(' ','',$output),'">')===0)
                    {
                        $output = substr_replace(trim($output), "", 0,2);
                    }
                    break;
                case 'id':
                    $output = $user_id;
                    break;
                case 'display_name':
                    $output = $user->display_name;

                    break;
                case 'user_url':
                    $output = $user->user_url;
                    break;
                case 'user_email':
                    $output = $user->user_email;
                    break;
                case 'user_login':
                    $output = $user->user_login;
                    break;
                case 'role':
                    $user_roles = $user->roles;
                    $user_role = array_shift($user_roles);
                    $output = $user_role;
                    break;
            }
        }
        return $output;
    }

    public static function getNotification($recipient_id=0,$is_unread='',$type_of_notification=array(),$sender_id=0,$limit=5){
        global $wpdb;
        if (get_option('wp_user_disable_user_notification')!='1') {
            $condition = ' 1=1 ';
            $condition .= (!empty($recipient_id)) ? " AND recipient_id = $recipient_id" : ' ';
            $condition .= (!empty($is_unread)) ? " AND is_unread = $recipient_id" : ' ';
            $condition .= (!empty($sender_id)) ? " AND recipient_id = $sender_id" : ' ';
            $condition .= (!empty($type_of_notification)) ? " follower_id IN ('" . implode('\',\'', $type_of_notification) . "')" : ' ';
            $querystr = "
                                SELECT
                                 id,
                                 type_of_notification,
                                 title_html,
                                 body_html,
                                 is_unread,
                                 href,
                                 created_time
                                FROM
                                 " . $wpdb->prefix . "wpuser_notification
                                WHERE
                                 " . $condition . "
                                 ORDER by created_time DESC";
            //error_log($querystr);
            return $wpdb->get_results($querystr, ARRAY_A);
        }
        return array();
    }

    public static function validation( $array, $form = 0 ){
        $fieldresponse['status'] = true ;
        if ( ( isset ( $array['is_required'] ) && $array['is_required'] == 1 )
            && ( !isset( $_POST[$array['meta_key']] ) || empty( $_POST[$array['meta_key']] ) ) ){
            $fieldresponse = array(
                'status' => false,
                'field' => $array['meta_key'] . $form,
                'message' => __($array['label'] . ' field is required', 'wpuser')
            );
            return $fieldresponse;
        }

        if( !(isset( $_POST[$array['meta_key']] ) && !empty( $_POST[$array['meta_key']] ) ) ) {
            return $fieldresponse;
        }

        switch ( $array['type'] ) {

            case 'text':
                return $fieldresponse;
                break;

            case 'number':
                if ( false == is_numeric( $_POST[$array['meta_key']] ) ){
                    $fieldresponse = array(
                        'status' => false,
                        'field' => $array['meta_key'] . $form,
                        'message' => __($array['label'] . ' field should be number', 'wpuser')
                    );
                }
                return $fieldresponse;
                break;

            case 'range':
                return $fieldresponse;
                break;

            case 'email':
                if ( false == filter_var( $_POST[$array['meta_key']], FILTER_VALIDATE_EMAIL ) ) {
                    $fieldresponse = array(
                        'status' => false,
                        'field' => $array['meta_key'] . $form,
                        'message' => __($array['label'] . ' field invalid email format', 'wpuser')
                    );
                }
                return $fieldresponse;
                break;

            case 'date':
                /*if ( false == SELF::isDate( $_POST[$array['meta_key']] ) ) {
                    $fieldresponse = array(
                        'status' => false,
                        'field' => $array['meta_key'] . $form,
                        'message' => __($array['label'] . ' field invalid date format. Date format Should be dd/mm/yyyy or dd-mm-yyyy', 'wpuser')
                    );
                }*/
                return $fieldresponse;
                break;

            case 'time':
                if( false == preg_match( '/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/', $_POST[$array['meta_key']] ) ) {
                    $fieldresponse = array(
                        'status' => false,
                        'field' => $array['meta_key'] . $form,
                        'message' => __($array['label'] . ' field invalid time format. Time format Should be HH:MM', 'wpuser')
                    );
                }
                return $fieldresponse;
                break;

            case 'tel':
                if( false == preg_match( '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i', $_POST[$array['meta_key']] ) ) {
                    $fieldresponse = array(
                        'status' => false,
                        'field' => $array['meta_key'] . $form,
                        'message' => __($array['label'] . ' field invalid time format.'.$array['label'].' format Should be XXXXXXXXXX', 'wpuser')
                    );
                }
                return $fieldresponse;
                break;

            case 'textarea':
                return $fieldresponse;
                break;

            case 'url':
                return $fieldresponse;
                break;
        }
        return $fieldresponse;
    }

    function isDate( $string ) {
        $matches = array();
        $pattern = '/^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/';
        if (!preg_match($pattern, $string, $matches)) return false;
        if (!checkdate($matches[2], $matches[1], $matches[3])) return false;
        return true;
    }

    public static function countViews($user_id)
    {

        global $wpdb;
        $querystr = "
                                SELECT
                                 count(DISTINCT view_by)
                                FROM
                                 " . $wpdb->prefix . "wpuser_views
                                WHERE
                                view_by <> 0
                                AND user_id = ".$user_id."
                                ";
        $count = $wpdb->get_var($querystr);
        if ($count) {
            return $count;
        }
        return 0;

    }

}
