<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
if (!class_exists('WPUserAdminGlobal')) :

    class WPUserAdminGlobal
    {

        public function __construct()
        {

        }

        public static function createField($array)
        {
            $field = '';
            $data = '';
            $type = $array['type'];
            $key = $array['id'];
            $options = isset($array['options']) ? $array['options'] : array();
            $allowed_types = array();
            $readonly = '';
            $placeholder = '';
            $is_required = '';
            $value = get_option($key);
            if (in_array($key, array('wp_user_email_user_forgot_content', '
                            wp_user_email_admin_register_content', 'wp_user_email_user_register_content', 'wp_user_show_term_data'))) {
                $value = stripslashes($value);
            }


            switch ($type) {

                case 'text':

                    $field .= "<input type='text' class='form-control ng-pristine ng-valid' name='" . $key . "' id='" . $key . "' value='" . $value . "' placeholder='" . $placeholder . "' $data $readonly />";
                    break;

                case 'textarea':
                    $field .= "<textarea  name='" . $key . "' cols='80' rows='4' id='" . $key . "' $data $readonly />$value</textarea><br>";
                    break;
                case 'password':
                    $field .= "<input type='password' class='form-control' name='" . $key . "' id='" . $key . "' placeholder='" . $placeholder . "' autocomplete='off'/ $data>";

                    break;
                case 'image_upload':
                    $allowed_types = implode(',', $allowed_types);
                    $field .= "<input data-required='" . $is_required . "' type='hidden' name='" . $key . "' id='" . $key . "' value='" . $value . "' />";
                    break;

                case 'url':
                    break;

                case 'radio':
                    if (isset($array['edit_choices'])) {
                        $array['edit_choices'] = explode("\r\n", $array['edit_choices']);
                        $field .= "<div class='userplus-radio-container' data-required='" . $is_required . "'>";
                        foreach ($array['edit_choices'] as $k => $v) {

                            $v = stripslashes($v);
                            $field .= "<label class='userplus-radio'><span";
                            if (checked($v, $value, 0)) {
                                $res .= ' class="checked"';
                            }
                            $field .= '></span><input type="radio" value="' . $v . '" name="' . $key . '" ';
                            $field .= checked($v, $value, 0);
                            $field .= " />$v</label>";
                        }
                        $field .= "</div>";
                    }
                    break;

                case 'checkbox':
                    $checked = ($value == 1) ? 'checked="checked"' : '';
                    $field .= '<input type="hidden"  id="' . $key . '"  name="' . $key . '" value="0">
                                       <input type="checkbox"  ' . $checked . ' id="' . $key . '"  name="' . $key . '" value="1" />';
                    break;

                case 'select':

                    $field .= "<select name='" . $key . "' id='" . $key . "'  style=\"font-family: 'FontAwesome', Helvetica;\" class='chosen-select' data-placeholder='" . $placeholder . "' $data >";
                    if (isset($options) && !empty($options)) {
                        foreach ($options as $option => $selectVal) {
                            $field .= "<option value='" . $selectVal . "'" . selected($selectVal, $value, 0) . ">" . ucfirst($option) . "</option>";
                        }
                    }
                    $field .= "</select>";
                    if(isset($array['options_desc']) && !empty($array['options_desc'])) {
                        $value_desc=get_option($key.'_desc');
                        $field .= "&nbsp;&nbsp;<input type='text' class='' name='" . $key . "_desc' id='" . $key . "_desc' value='" . $value_desc . "' placeholder='Description'  />";
                    }



                    break;


            }
            return $field;
        }

        public static function getColumn($array, $name, $keepKeys = true)
        {
            $result = [];
            if ($keepKeys) {
                foreach ($array as $k => $element) {
                    $result[$k] = WPUserAdminGlobal::getValue($element, $name);
                }
            } else {
                foreach ($array as $element) {
                    $result[] = WPUserAdminGlobal::getValue($element, $name);
                }
            }

            return $result;
        }

        public static function getValue($array, $key, $default = null)
        {
            if ($key instanceof \Closure) {
                return $key($array, $default);
            }

            if (is_array($key)) {
                $lastKey = array_pop($key);
                foreach ($key as $keyPart) {
                    $array = WPUserAdminGlobal::getValue($array, $keyPart);
                }
                $key = $lastKey;
            }

            if (is_array($array) && (isset($array[$key]) || array_key_exists($key, $array))) {
                return $array[$key];
            }

            if (($pos = strrpos($key, '.')) !== false) {
                $array = WPUserAdminGlobal::getValue($array, substr($key, 0, $pos), $default);
                $key = substr($key, $pos + 1);
            }

            if (is_object($array)) {
                // this is expected to fail if the property does not exist, or __get() is not implemented
                // it is not reliably possible to check whether a property is accessible beforehand
                return $array->$key;
            } elseif (is_array($array)) {
                return (isset($array[$key]) || array_key_exists($key, $array)) ? $array[$key] : $default;
            } else {
                return $default;
            }
        }

        static function is_plugin_installed($pluginTitle)
        {
            // get all the plugins
            $installedPlugins = get_plugins();

            foreach ($installedPlugins as $installedPlugin => $data) {
                // check for the plugin title
                if ($data['Title'] == $pluginTitle) {

                    // return the plugin folder/file
                    return $installedPlugin;
                }
            }

            return false;
        }

        static function generatePluginActivationLinkUrl($plugin,$action = 'activate' )
        {
            if ( strpos( $plugin, '/' ) ) {
                $plugin = str_replace( '\/', '%2F', $plugin );
            }
            $url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
            $_REQUEST['plugin'] = $plugin;
            $url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
            return $url;
        }


    }

endif;

