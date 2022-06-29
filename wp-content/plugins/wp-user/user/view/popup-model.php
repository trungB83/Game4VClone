<?php
$html .='<div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel">'.
                                __('Send Message to', 'wpuser').'
                                <span id="wpuser_mail_to_name"></span>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="google_form">
                                    <div style="display: none;" id="wpuser_errordiv_send_mail"
                                         class="alert alert-dismissible" role="alert"><label
                                            id="wpuser_errordiv_send_mail"></label></div>
                                             <input name="wpuser_update_setting" type="hidden"
                                           value="'.wp_create_nonce('wpuser-update-setting').'"/>
                                           <input type="hidden" class="form-control" name="id" class="wpuser_mail_to_userid" value=""
                                           id="wpuser_mail_to_userid">
                                            <div class="form-group">
                                        <label>'.__('From', 'wpuser').'</label>
                                        <input type="text" class="form-control" name="from"
                                               placeholder="'. __('Email', 'wpuser').'">
                                    </div>
                                    <div class="form-group">
                                        <label>'.__('Subject', 'wpuser').'</label>
                                        <input type="text" class="form-control" name="subject"
                                               placeholder="'.__('Subject', 'wpuser').'">
                                    </div>
                                    <div class="form-group">
                                        <label>'.__('Message', 'wpuser').'</label>
                                    <textarea class="form-control" rows="3"
                                              name="message" placeholder="'. __('Message', 'wpuser').'"></textarea>
                            </div>';

        if (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) {
            $html .=' <div class="row">
                                    <div class="col-xs-12">
                                        <div id="recaptcha" class="g-recaptcha"
                                             data-sitekey="'.get_option('wp_user_security_reCaptcha_secretkey').'"></div>
                                        <input type="hidden" title="Please verify this" class="required" name="keycode"
                                               id="keycode">
                                    </div>
                          </div>';
        }

        $html .='</form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn '.$wp_user_appearance_button_type .' btn-default"
                                data-dismiss="modal">
                                    '.__('Close', 'wpuser').'
</button>
                        <button type="button" id="wpuser_send_mail"
                                class="wpuser_button btn '.$wp_user_appearance_button_type.' btn-primary wpuser-custom-button">
'.__('Send', 'wpuser') .'
</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
            <div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_view_image" tabindex="-1" role="dialog"
                 aria-labelledby="viewModalLabel">
              <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="text-red close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalImage">
                      <span id="wpuser_image_name">Profile</span>
                    </h4>
                  </div>
                  <div class="modal-body">
                    <img style="width:100%" id=\'wpuser_image_url\' class="profile-user-img img-responsive" src="">
                  </div>
                </div>
              </div>
            </div>
        <!--END Model -->

        <!-- Filter Modal -->
        <div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_view_filter" tabindex="-1" role="dialog"
             aria-labelledby="viewModalLabel">
          <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="filterClose closebtn text-red pull-right close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="filter_title">
                     <label class="filter_title"><i class="fa fa-filter"></i> Filter</label>

                </h4>
              </div>
        <div class="modal-body">';
        $strSearch = ( isset($_GET["search_user"] ) && 'null' != $_GET['search_user'] ) ? $_GET["search_user"] : "" ;
        $html .='<form id="wpuser_filter_member_list_form" class="form-horizontal">
                          <div class="box-body">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="search_user" value="'. $strSearch.'" id="search_user" placeholder="search">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                            <input type="hidden" value="'.wp_create_nonce('wpuser-update-setting').'" name="wpuser_update_setting">';
                            if((isset($atts['key']) && isset($atts['key']) && !empty($atts['value']) && !empty($atts['value'])) ||
                                (isset($_GET['key']) && isset($_GET['key']) && !empty($_GET['value']) && !empty($_GET['value']) )){
                                $arrKey = (isset($_GET['key']) && !empty($_GET['key'])) ? $_GET['key'] : $atts['key'];
                                $arrValue = (isset($_GET['value']) && !empty($_GET['value'])) ? $_GET['value'] : $atts['value'];
                                $html .='<input name="key" type="hidden" value="' . $arrKey . '"/>';
                                $no_whitespaces_ids = preg_replace( '/\s*,\s*/', ',', filter_var( $arrKey, FILTER_SANITIZE_STRING ) );
                                $ids_array = explode( ',', $no_whitespaces_ids );

                                $no_whitespaces_text = preg_replace( '/\s*,\s*/', ',', filter_var( $arrValue, FILTER_SANITIZE_STRING ) );
                                $text_array = explode( ',', $no_whitespaces_text );

                                // We need to make sure that our two arrays are exactly the same lenght before we continue
                                if ( count( $ids_array ) == count( $text_array ) ){
                                    $combined_array = array_combine( $ids_array, $text_array );
                                    foreach ( $combined_array as $k => $v ){
                                        $html .='<input name="' . $k . '" type="hidden" value="' . $v . '"/>';
                                    }
                                }
                            }

                            if (isset($atts['role_in']) && !empty($atts['role_in'])) {
                                $html .='<input type="hidden" value="'.$atts["role_in"].'" name="role_in">';
                            }
                            if (isset($atts['role_not_in']) && !empty($atts['role_not_in'])) {
                                $html .='<input type="hidden" value="'.$atts["role_not_in"].'" name="role_not_in">';
                            }
                            if (isset($atts['include']) && !empty($atts['include'])) {
                                $html .='<input type="hidden" value="'.$atts["include"].'" name="include">';
                            }
                            if (isset($atts['exclude']) && !empty($atts['exclude'])) {
                                $html .='<input type="hidden" value="'.$atts["exclude"].'" name="exclude">';
                            }
                            if (isset($atts['order']) && !empty($atts['order'])) {
                                $html .='<input type="hidden" value="'.$atts["order"].'" name="order">';
                            }
                            if (isset($atts['include']) && !empty($atts['include'])) {
                                $html .='<input type="hidden" value="'.$atts["include"].'" name="include">';
                            }
                            if (isset($atts['approve']) && !empty($atts['approve'])) {
                                $html .='<input type="hidden" value="'.$atts["approve"].'" name="approve">';
                            }

                            if (isset($atts['profile_form_id']) && !empty($atts['profile_form_id'])) {
                                $html .='<input type="hidden" value="'.$atts["profile_form_id"].'" name="profile_form_id">';
                            }


        if (isset($atts['id']) && !empty($atts['id'])) {
            $html .= '<input type="hidden" value="'.$atts['id'].'" name="form_id">
            <div class="row">
                                                            <a class="pull-right" data-toggle="collapse" href="#collapseFilter" aria-expanded="true" aria-controls="collapseExample">
                                                                      <span class="fa fa-gear"></span> Advance Filter
                                    </a>
                                    </div>
                                                            <div class="collapse" id="collapseFilter" aria-expanded="true">
                                                            <div id="advanced_filter" class="advanced_filter">';

            global $userplus;
            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
            $form_fields = get_post_meta($atts['id'], 'fields', true);
            if ($userplus_field_order) {
                $fields_count = count($userplus_field_order);
                for ($i = 0; $i < $fields_count; $i++) {
                    $key = $userplus_field_order[$i];
                    $array = $form_fields[$key];
                    $html .= profileController::edit_fields($key, $array, 'block-2', $form_id,null);
                }
            }
            $html .= '</div>
                                </div>';
        }

            if (isset($_GET['form_id']) && !empty($_GET['form_id'])) {
                $html .= '<input type="hidden" value="'.$_GET['form_id'].'" name="search_form_id">';
                $userplus_field_order = get_post_meta($_GET['form_id'], 'userplus_field_order', true);
                $form_fields = get_post_meta($_GET['form_id'], 'fields', true);;
                if ($userplus_field_order) {
                    $fields_count = count($userplus_field_order);
                    for ($i = 0; $i < $fields_count; $i++) {
                        $key = $userplus_field_order[$i];
                        if (isset($_GET[$key]) && !empty($_GET[$key])) {
                            $html .= '<input type="hidden" value="' . $_GET[$key] . '" name="' . $key . '">';
                        }
                    }
                }
            }

        $html .= '</div>
                    </div>
                        <div class="">
                                <button type="button" id="wpuser_filter_member_list" class="btn btn-flat btn-primary" data-dismiss="modal" aria-label="Close">  Filter</button>
                                <button type="button" id="wpuser_filter_member_list_clear" class="btn btn-flat btn-default">Clear</button>
                                <button type="button"  href="javascript:void(0)"  onclick="filterSidenavClose()" class="btn btn-flat btn-default pull-right filterClose Close" data-dismiss="modal" aria-label="Close"> Close </button>
                         </div>
                    </form>';

        $html .= '</div>
            </div>
        </div>
    </div>
<!--END Model -->';
