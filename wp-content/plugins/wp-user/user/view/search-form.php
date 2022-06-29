<?php
        $form_type = (isset($atts['form_type']) && !empty($atts['form_type'])) ? $atts['form_type'] :'block-2';
        $search_type = (isset($atts['type']) ) ? $atts['type'] : '';
        $form_type = (isset($atts['type']) && 'header' == ($atts['type'])) ? 'block-4' : $form_type ;
        $divCol = ($form_type == 'block-2') ? 6 : ( ($form_type == 'block-4') ? 2 : 12 ) ;
        $form_id = time() . rand(2, 999);
        $inputType =  (isset($atts['input_type']) && !empty($atts['input_type'])) ? $atts['input_type'] : 'normal';

        $html .='<form id="wpuser_filter_member_list_form" action="' . get_permalink(get_option('wp_user_page')) . '" method="get" class="">
                          <div class="box-body">';
        if( 'header' != $search_type ) {
            $html .= '<div class="col-sm-12 col-md-12">
                        <div class="form-group">
                                        <input type="text" class="form-control" name="search_user" id="search_user" placeholder="search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                </div>
                        </div>';
        }

        $html .='<div class="serch-user col-sm-12 col-md-12">
                            <input type="hidden" value="'.wp_create_nonce('wpuser-update-setting').'" name="wpuser_update_setting">';


                            if (isset($atts['role_in']) && !empty($atts['role_in'])) {
                                $html .='<input type="hidden" value="'.$atts["role_in"].'" name="role_in">';
                            }
                            if (isset($atts['role_not_in']) && !empty($atts['role_not_in'])) {
                                $html .='<input type="hidden" value="'.$atts["role_not_in"].'" name="role_not_in">';
                            }
                            if (isset($atts['orderby']) && !empty($atts['orderby'])) {
                                $html .='<input type="hidden" value="'.$atts["orderby"].'" name="orderby">';
                            }
                            if (isset($atts['order']) && !empty($atts['order'])) {
                                $html .='<input type="hidden" value="'.$atts["order"].'" name="order">';
                            }
                            if (isset($atts['include']) && !empty($atts['include'])) {
                                $html .='<input type="hidden" value="'.$atts["include"].'" name="include">';
                            }
                            if (isset($atts['exclude']) && !empty($atts['exclude'])) {
                                $html .='<input type="hidden" value="'.$atts["exclude"].'" name="exclude">';
                            }
                            if (isset($atts['approve']) && !empty($atts['approve'])) {
                                $html .='<input type="hidden" value="'.$atts["approve"].'" name="approve">';
                            }

                            if (isset($atts['profile_form_id']) && !empty($atts['profile_form_id'])) {
                                $html .='<input type="hidden" value="'.$atts["profile_form_id"].'" name="profile_form_id">';
                            }


        if (isset($atts['id']) && !empty($atts['id'])) {
            $html .= '<input type="hidden" value="'.$atts['id'].'" name="form_id">';
            if( 'header' != $search_type ) {
                $html .= '<div class="col-sm-12">
                             <a class="pull-right" data-toggle="collapse" href="#collapseFilter' . $form_id . '" aria-expanded="true" aria-controls="collapseExample">
                                 <span class="fa fa-gear"></span> Advance Filter
                             </a>
                          </div>';
            }
            $collapse_in = ( 'header' != $search_type ) ? '' : 'in';
            $html .= '<div class="collapse '.$collapse_in.'" id="collapseFilter'.$form_id.'" aria-expanded="true">
                      <div id="advanced_filter" class="row advanced_filter">';

            global $userplus;
            $userplus_field_order = get_post_meta($atts['id'], 'userplus_field_order', true);
            $form_fields = get_post_meta($atts['id'], 'fields', true);;
            if ($userplus_field_order) {
                $fields_count = count($userplus_field_order);
                for ($i = 0; $i < $fields_count; $i++) {
                    $key = $userplus_field_order[$i];
                    $array = $form_fields[$key];
                    $html .= profileController::edit_fields($key, $array, $form_type, $form_id,null, 'default','search');
                }
            }
            @$html .= apply_filters('wpuser_advananced_search', $atts);
            if( 'header' == $search_type  ) {
            $html .= '
                    <div class="col-xs-12 col-md-'.$divCol.' col-sm-12 ">
                                <input type="submit" class="btn btn-flat btn-primary btn-'.$inputType.' btn-block" value="'.__('Search','wpuser').'" aria-label="Close"> ';
                $html .= '  </div>';
            }
            $html .= '
                    </div>
                                </div>';
        }

        if(isset($atts['key']) && isset($atts['key']) && !empty($atts['value']) && !empty($atts['value'])){
            $html .='<input name="key" type="hidden" value="' . $atts['key'] . '"/>';
            $no_whitespaces_ids = preg_replace( '/\s*,\s*/', ',', filter_var( $atts['key'], FILTER_SANITIZE_STRING ) );
            $ids_array = explode( ',', $no_whitespaces_ids );

            $no_whitespaces_text = preg_replace( '/\s*,\s*/', ',', filter_var( $atts['value'], FILTER_SANITIZE_STRING ) );
            $text_array = explode( ',', $no_whitespaces_text );

            // We need to make sure that our two arrays are exactly the same lenght before we continue
            if ( count( $ids_array ) == count( $text_array ) ){
                $combined_array = array_combine( $ids_array, $text_array );
                foreach ( $combined_array as $k => $v ){
                    $html .='<input name="' . $k . '" type="hidden" value="' . $v . '"/>';
                }
            }
        }

        $html .= '</div>';
        if( 'header' != $search_type  ) {
            $html .= '
                            <div class="col-sm-12 col-md-'.$divCol.'">
                                        <input type="submit" class="btn btn-flat btn-primary" value="'.__('Search','wpuser').'" aria-label="Close"> ';

            $html .= ' <button type="button" id="wpuser_filter_member_list_clear" class="btn btn-flat btn-default">Clear</button>';
            $html .= '  </div>';
        }

    $html .= '</div>

                    </form>';
