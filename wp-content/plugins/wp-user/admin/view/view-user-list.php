<div class="bootstrap-wrapper hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include('view-header.php'); ?>
        <div class="content-wrapper">
            <?php include('alert-pro.php'); ?>
            <section class="content-header">
                <h1>
                    <?php _e('WP User', 'wpuser'); ?>
                    <small>Users</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> <?php _e('Home', 'wpuser'); ?></a></li>
                    <li class="active"><?php _e('Users', 'wpuser'); ?></li>
                </ol>
            </section>
            <section class="content">
                <?php do_action('wp_user_list_setting_before');


                ?>
                <div ng-controller="settingController">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="response_message" id="response_message"></div>
                            <div class="nav-tabs-custom">

                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="/settingController#tab_1"
                                                          aria-expanded="true"><?php _e('Users', 'wpuser') ?></a></li>
                                    <li class=""><a data-toggle="tab" href="/settingController#tab_login_log"
                                                    aria-expanded="false"><?php _e('Login Log', 'wpuser') ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div id="tab_1" class="tab-pane active">
                                        <!-- jPList start -->
                                        <script>
                                            $.noConflict();

                                            jQuery('document').ready(function () {

                                                jQuery('#demo').jplist({
                                                    itemsBox: '.list'
                                                    , itemPath: '.list-item'
                                                    , panelPath: '.jplist-panel'
                                                });

                                                jQuery('#login_log').jplist({
                                                    itemsBox: '.list-login'
                                                    , itemPath: '.list-item-login'
                                                    , panelPath: '.jplist-panel-login'
                                                });

                                                jQuery(document).ready(function () {
                                                    jQuery("#parent").click(function () {
                                                        jQuery(".child").prop("checked", this.checked);
                                                    });

                                                    jQuery('.child').click(function () {
                                                        if (jQuery('.child:checked').length == jQuery('.child').length) {
                                                            jQuery('#parent').prop('checked', true);
                                                        } else {
                                                            jQuery('#parent').prop('checked', false);
                                                        }
                                                    });
                                                });


                                                jQuery("#bulk_action").change(function () {
                                                    var selectVal = jQuery(this).val();
                                                    if (selectVal == 'Export') {
                                                        jQuery('.export_setting').show();
                                                    } else {
                                                        jQuery('.export_setting').hide();
                                                    }
                                                });

                                            });

                                            function changeStatus(id, status) {
                                                jQuery.ajax({
                                                    type: "post",
                                                    dataType: "json",
                                                    url: wpuser_link.wpuser_ajax_url + '?action=wpuser_bulk_process',
                                                    data: 'userlist[0]=' + id + '&bulk_action=' + status + '&wpuser_update_setting=' + wpuser_link.wpuser_update_setting,
                                                    success: function (response) {
                                                        if (response.status == 0)
                                                            jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> <?php _e('Error', 'wpuser'); ?>!</h4>' + response.message + '</div>');
                                                        else if (response.status == 1) {
                                                            jQuery.each(response.userlist, function (i, val) {
                                                                if (response.bulk_action == 'Approve') {
                                                                    jQuery("#status_" + val).html('<i style="color:green" class="status fa fa-fw fa-check-circle-o"><?php _e('Approved', 'wpuser'); ?></i>');
                                                                    jQuery("#user_action_" + val).html('<a><span class="user_action" id="user_action_' + val + '"><span onclick="changeStatus(' + val + ',\'Deny\')" style="color:red"><?php _e('Deny', 'wpuser'); ?> </span></span></a>');

                                                                }
                                                                else if (response.bulk_action == 'Deny') {
                                                                    jQuery("#status_" + val).html('<i style="color:red" class="status fa fa-fw  fa-minus-circle"><?php _e('Denied', 'wpuser'); ?></i>');
                                                                    jQuery("#user_action_" + val).html('<a><span class="user_action" id="user_action_' + val + '"><span onclick="changeStatus(' + val + ',\'Approve\')" style="color:green"><?php _e('Approve', 'wpuser'); ?>  </span></span></a>');

                                                                }
                                                            });
                                                        }
                                                    }
                                                })
                                            }

                                        </script>
                                        <?php
                                        $blogusers = get_users();
                                        // Array of WP_User objects.
                                        ?>

                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <!-- main content -->
                                                <form action="" name="wpuser_bulk_action_form"
                                                      id="wpuser_bulk_action_form"
                                                      method="post">
                                                    <input name="wpuser_update_setting" type="hidden"
                                                           value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>

                                                    <div class="page" id="demo">
                                                        <!-- jplist top panel -->
                                                        <div class="jplist-panel">
                                                            <div class="center-block1">
                                                                <div class="form-group export_setting">
                                                                    <div class="col-md-3">
                                                                        <label
                                                                            for="wpuser_export_users_include_fields"><?php _e('Only Include these fields in export', 'wpuser') ?> </label>
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <input type="text" name="include_fields"
                                                                               id="include_fields" class="regular-text">
                                                                        <p><?php _e('A comma seperated list of fields to include only in the export. e.g.  ID,user_login,user_nicename,display_name,user_email', 'wpuser') ?></p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div
                                                                        class="col-md-8"></div>
                                                                    <div class="col-md-4 pull-right">
                                                                        <div class="input-group">
                                                                            <input
                                                                                class="form-control"
                                                                                data-path="*"
                                                                                type="text"
                                                                                value=""
                                                                                placeholder="<?php _e('Search', 'wpuser') ?>"
                                                                                data-control-type="textbox"
                                                                                data-control-name="title-filter"
                                                                                data-control-action="filter"
                                                                            />
                                                                                <span class="input-group-addon"><i
                                                                                        class="fa fa-search"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- search any text in the element -->

                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <!-- filter dropdown -->
                                                                        <div
                                                                            class="pagination dropdown filter-dd pull-right"
                                                                            data-control-type="boot-filter-drop-down"
                                                                            data-control-name="category-filter"
                                                                            data-control-action="filter">

                                                                            <button
                                                                                class="btn btn-default dropdown-toggle"
                                                                                type="button"
                                                                                data-toggle="dropdown"
                                                                                id="filter-dropdown-menu"
                                                                                aria-expanded="true">
                                                                                <span
                                                                                    data-type="selected-text"><?php _e('Filter by Type', 'wpuser'); ?></span>
                                                                                <span class="caret"></span>
                                                                            </button>


                                                                            <ul class="dropdown-menu" role="menu"
                                                                                aria-labelledby="filter-dropdown-menu">
                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=""
                                                                                       data-default="true"><?php _e('All', 'wpuser'); ?></a>
                                                                                </li>

                                                                                <?php
                                                                                $wp_roles = wp_roles();
                                                                                foreach ($wp_roles->role_names as $role_names) {
                                                                                    echo '<li role="presentation">
                                                                            <a role="menuitem" tabindex="-1"
                                                                               href="#" data-path=".' . $role_names . '"
                                                                               data-default="true">' . $role_names . '</a>
                                                                        </li>';
                                                                                }
                                                                                ?>


                                                                            </ul>
                                                                        </div>
                                                                        <div
                                                                            class="pagination">
                                                                            <select id="bulk_action" name="bulk_action">
                                                                                <option
                                                                                    value=""><?php _e('Bulk Actions', 'wpuser'); ?></option>
                                                                                <option
                                                                                    value="Approve"><?php _e('Approve', 'wpuser'); ?></option>
                                                                                <option
                                                                                    value="Deny"><?php _e('Deny', 'wpuser'); ?></option>
                                                                                <option
                                                                                    value="Export"><?php _e('Export CSV', 'wpuser'); ?>
                                                                                </option>
                                                                            </select>
                                                                            <button
                                                                                class="btn btn-default"
                                                                                type="button"
                                                                                id="wpuser_bulk_action"
                                                                                aria-expanded="true"><?php _e('Apply', 'wpuser'); ?>
                                                                            </button>
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-2">

                                                                        <!-- sort dropdown -->
                                                                        <div
                                                                            class="pagination dropdown sort-dd"
                                                                            data-control-type="boot-sort-drop-down"
                                                                            data-control-name="bootstrap-sort-dropdown-demo"
                                                                            data-control-action="sort"
                                                                            data-datetime-format="{year}/{month}/{day}">

                                                                            <button
                                                                                class="btn btn-default dropdown-toggle"
                                                                                type="button"
                                                                                id="sort-dropdown-menu"
                                                                                data-toggle="dropdown"
                                                                                aria-expanded="true">
                                                                                <span
                                                                                    data-type="selected-text"><?php _e('Sort by', 'wpuser'); ?></span>
                                                                                <span class="caret"></span>
                                                                            </button>

                                                                            <ul class="dropdown-menu" role="menu"
                                                                                aria-labelledby="sort-dropdown-menu">

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".username"
                                                                                       data-order="asc"
                                                                                       data-type="text"
                                                                                    ><?php _e('Username A-Z', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".username"
                                                                                       data-order="desc"
                                                                                       data-type="text"><?php _e('Username Z-A', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation"
                                                                                    class="divider"></li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".name"
                                                                                       data-order="asc"
                                                                                       data-type="text"><?php _e('Name A-Z', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".name"
                                                                                       data-order="desc"
                                                                                       data-type="text"><?php _e('Name Z-A', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation"
                                                                                    class="divider"></li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".role"
                                                                                       data-order="asc"
                                                                                       data-type="text"><?php _e('Role Asc', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".role"
                                                                                       data-order="desc"
                                                                                       data-type="text"><?php _e('Role Desc', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation"
                                                                                    class="divider"></li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".status"
                                                                                       data-order="asc"
                                                                                       data-type="text"><?php _e('Status Asc', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                                <li role="presentation">
                                                                                    <a role="menuitem" tabindex="-1"
                                                                                       href="#" data-path=".status"
                                                                                       data-order="desc"
                                                                                       data-type="text"
                                                                                       data-default="true"><?php _e('Status Desc', 'wpuser'); ?>
                                                                                    </a>
                                                                                </li>

                                                                            </ul>

                                                                        </div>


                                                                    </div>


                                                                    <div class="col-md-7">
                                                                        <div class="col-md-5">

                                                                            <!-- items per page dropdown -->
                                                                            <div
                                                                                class="pagination dropdown pull-left jplist-items-per-page"
                                                                                data-control-type="boot-items-per-page-dropdown"
                                                                                data-control-name="paging"
                                                                                data-control-action="paging">

                                                                                <button
                                                                                    class="btn btn-default dropdown-toggle"
                                                                                    type="button"
                                                                                    data-toggle="dropdown"
                                                                                    id="dropdown-menu-1"
                                                                                    aria-expanded="true">
                                                                                    <span
                                                                                        data-type="selected-text"><?php _e('Items per Page', 'wpuser'); ?></span>
                                                                                    <span class="caret"></span>
                                                                                </button>

                                                                                <ul class="dropdown-menu"
                                                                                    role="menu"
                                                                                    aria-labelledby="dropdown-menu-1">

                                                                                    <li role="presentation">
                                                                                        <a role="menuitem"
                                                                                           tabindex="-1"
                                                                                           href="#"
                                                                                           data-number="10"><?php _e('10 per page', 'wpuser'); ?>
                                                                                        </a>
                                                                                    </li>

                                                                                    <li role="presentation">
                                                                                        <a role="menuitem"
                                                                                           tabindex="-1"
                                                                                           href="#" data-number="20"
                                                                                           data-default="true"><?php _e('20 per page', 'wpuser'); ?>
                                                                                        </a>
                                                                                    </li>

                                                                                    <li role="presentation">
                                                                                        <a role="menuitem"
                                                                                           tabindex="-1"
                                                                                           href="#"
                                                                                           data-number="50"><?php _e('50 per page', 'wpuser'); ?>
                                                                                        </a>
                                                                                    </li>

                                                                                    <li role="presentation"
                                                                                        class="divider"></li>

                                                                                    <li role="presentation">
                                                                                        <a role="menuitem"
                                                                                           tabindex="-1"
                                                                                           href="#"
                                                                                           data-number="all"><?php _e('ViewAll', 'wpuser'); ?>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>

                                                                            <!-- pagination info label -->
                                                                            <div
                                                                                class="pagination pull-right jplist-pagination-info"
                                                                                data-type="<strong>Page {current} of {pages}</strong><br/> <small>{start} - {end} of {all}</small>"
                                                                                data-control-type="pagination-info"
                                                                                data-control-name="paging"
                                                                                data-control-action="paging"></div>

                                                                        </div>
                                                                        <!-- bootstrap pagination control -->
                                                                        <ul
                                                                            class="pagination col-md-7 pull-right jplist-pagination"
                                                                            data-control-type="boot-pagination"
                                                                            data-control-name="paging"
                                                                            data-control-action="paging"
                                                                            data-range="3"
                                                                            data-mode="google-like">
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                        <div class="row list">
                                                            <div class="col-md-12">
                                                                <div class="col-md-3">
                                                                    <h4><input type="checkbox"
                                                                               id="parent"> <?php _e('Username', 'wpuser') ?>
                                                                    </h4>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <h4><?php _e('Name', 'wpuser') ?></h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h4><?php _e('Email', 'wpuser') ?></h4>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <h4><?php _e('Role', 'wpuser') ?></h4>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <h4><?php _e('Status', 'wpuser') ?></h4>
                                                                </div>
                                                                <hr>
                                                            </div>

                                                            <?php
                                                            $count = 0;
                                                            echo '<div class="row">';
                                                            foreach ($blogusers as $user) {
                                                                $class = ($count & 1) ? 'list-odd' : 'list-even';
                                                                echo '<div class="col-md-12 list-item ' . $class . '" id="user_' . $user->ID . '">';
                                                                echo '<div class="col-md-3">';
                                                                echo '<input type="checkbox" class="child userlist" id="Checkbox' . $user->ID . '" name="userlist[]" value="' . $user->ID . '">';
                                                                echo '<span class="username ">' . esc_html($user->user_login) . '</span><br>';
                                                                echo '<span class="list-item-action">';
                                                                if(!in_array('administrator',$user->roles)) {
                                                                    if (get_user_meta($user->ID, 'wp-approve-user', true) == 5) {
                                                                        echo '<a><span class="user_action" id="user_action_' . $user->ID . '"><span onclick="changeStatus(\'' . esc_html($user->ID) . '\',\'Approve\')" style="color:green">Approve </span></span></a>';
                                                                    } else if (get_user_meta($user->ID, 'wp-approve-user', true) == 1) {
                                                                        echo '<a><span class="user_action" id="user_action_' . $user->ID . '"><span onclick="changeStatus(\'' . esc_html($user->ID) . '\',\'Deny\')" style="color:red">Deny </span></span></a>';

                                                                    } else { //if (get_user_meta($user->ID, 'wp-approve-user', true) == 2)
                                                                        echo '<a><span class="user_action" id="user_action_' . $user->ID . '"><span onclick="changeStatus(\'' . esc_html($user->ID) . '\',\'Approve\')" style="color:green">Approve </span>| <span onclick="changeStatus(\'' . esc_html($user->ID) . '\',\'Deny\')" style="color:red">Deny </span></span></a>';
                                                                    }
                                                                }
                                                                $wp_user_page = get_option('wp_user_page');
                                                                $genre_url = !empty($wp_user_page) ? add_query_arg(array('user_id'=>$user->ID), get_permalink($wp_user_page)) : '#';
                                                                echo '</span></div>';
                                                                echo '<div class="col-md-2" ><span class="name "><a href="'.$genre_url.'" target="_blank">' . esc_html($user->display_name) . '</a></span></div>';
                                                                echo '<div class="col-md-3"><span class="email ">' . esc_html($user->user_email) . '</span></div>';
                                                                echo '<div class="col-md-3 "><span class="role ' . ucfirst(implode(' ', $user->roles)) . '">' . ucfirst(implode(' ', $user->roles)) . '</span></div>';
                                                                echo '<div class="col-md-1"><span class="" id="status_' . $user->ID . '">';
                                                                if (get_user_meta($user->ID, 'wp-approve-user', true) == 5) {
                                                                    echo '<i style="color:red" class="status fa fa-fw  fa-minus-circle">';
                                                                    _e('Denied', 'wpuser');
                                                                    echo '</i>';
                                                                } else if (get_user_meta($user->ID, 'wp-approve-user', true) == 1) {
                                                                    echo '<i style="color:green" class="status fa fa-fw fa-check-circle-o">';
                                                                    _e('Approved', 'wpuser');
                                                                    echo '</i>';
                                                                } else if (get_user_meta($user->ID, 'wp-approve-user', true) == 2) {
                                                                    echo '<i style="color:orange" class="status fa fa-fw fa-circle-o">';
                                                                    _e('Pending', 'wpuser');
                                                                    echo '</i>';
                                                                }
                                                                echo '</span></div>';
                                                                echo '</div>';
                                                                $count++;
                                                            }


                                                            ?>
                                                        </div>
                                                    </div>

                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div id="tab_login_log" class="tab-pane">
                                    <p><?php _e('Track records of user login with set of multiple information like ip,time,user name,browser information etc', 'wpuser'); ?>
                                        .</p>
                                    <?php
                                    global $wpdb;
                                    $data = array();
                                    $q = "SELECT u.id as user_id,l.user,u.user_email,l.status,l.message,l.user_agent,l.ip,l.created_date FROM {$wpdb->prefix}wpuser_login_log l LEFT JOIN $wpdb->users u ON (l.user=u.user_login OR l.user=u.user_email ) ORDER BY l.created_date DESC";
                                    $data = $wpdb->get_results($q);
                                    if (!empty($data)) {
                                        ?>
                                        <div class="page" id="login_log">
                                            <!-- jplist top panel -->
                                            <div class="jplist-panel-login">
                                                <div class="center-block1">
                                                    <div class="form-group export_setting">
                                                        <div class="col-md-3">
                                                            <label
                                                                for="wpuser_export_users_include_fields"><?php _e('Only Include these fields in export', 'wpuser') ?> </label>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div
                                                            class="col-md-8">
                                                            <button class="btn btn-default" type="button"
                                                                    id="wpuser_clear_log_action" aria-expanded="true">
                                                                Clear Log
                                                            </button>
                                                        </div>
                                                        <div class="col-md-4 pull-right">
                                                            <div class="input-group">
                                                                <input
                                                                    class="form-control"
                                                                    data-path="*"
                                                                    type="text"
                                                                    value=""
                                                                    placeholder="<?php _e('Search', 'wpuser') ?>"
                                                                    data-control-type="textbox"
                                                                    data-control-name="title-filter"
                                                                    data-control-action="filter"
                                                                />
                                                                                <span class="input-group-addon"><i
                                                                                        class="fa fa-search"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- search any text in the element -->

                                                    <div class="row">
                                                        <div class="col-md-3">
                                                        </div>
                                                        <div class="col-md-2">

                                                            <!-- sort dropdown -->
                                                            <div
                                                                class="pagination dropdown sort-dd"
                                                                data-control-type="boot-sort-drop-down"
                                                                data-control-name="bootstrap-sort-dropdown-demo"
                                                                data-control-action="sort"
                                                                data-datetime-format="{year}/{month}/{day}">

                                                                <button
                                                                    class="btn btn-default dropdown-toggle"
                                                                    type="button"
                                                                    id="sort-dropdown-menu"
                                                                    data-toggle="dropdown"
                                                                    aria-expanded="true">
                                                                    <span
                                                                        data-type="selected-text"><?php _e('Sort by', 'wpuser') ?></span>
                                                                    <span class="caret"></span>
                                                                </button>

                                                                <ul class="dropdown-menu" role="menu"
                                                                    aria-labelledby="sort-dropdown-menu">


                                                                    <li role="presentation">
                                                                        <a role="menuitem" tabindex="-1"
                                                                           href="#" data-path=".created_date"
                                                                           data-order="asc"
                                                                           data-type="text"><?php _e('Time Asc', 'wpuser') ?>
                                                                        </a>
                                                                    </li>

                                                                    <li role="presentation">
                                                                        <a role="menuitem" tabindex="-1"
                                                                           href="#" data-path=".created_date"
                                                                           data-order="desc"
                                                                           data-type="text"
                                                                           data-default="true"><?php _e('Time Desc', 'wpuser') ?>
                                                                        </a>
                                                                    </li>

                                                                    <li role="presentation"
                                                                        class="divider"></li>

                                                                    <li role="presentation">
                                                                        <a role="menuitem" tabindex="-1"
                                                                           href="#" data-path=".log_user_id"
                                                                           data-order="asc"
                                                                           data-type="text"
                                                                        ><?php _e('User ID A-Z', 'wpuser') ?>
                                                                        </a>
                                                                    </li>

                                                                    <li role="presentation">
                                                                        <a role="menuitem" tabindex="-1"
                                                                           href="#" data-path=".log_user_id"
                                                                           data-order="desc"
                                                                           data-type="text"><?php _e('User ID Z-A', 'wpuser') ?>
                                                                        </a>
                                                                    </li>

                                                                    <li role="presentation"
                                                                        class="divider"></li>

                                                                    <li role="presentation">
                                                                        <a role="menuitem" tabindex="-1"
                                                                           href="#" data-path=".user"
                                                                           data-order="asc"
                                                                           data-type="text"><?php _e('User A-Z', 'wpuser') ?></a>
                                                                    </li>

                                                                    <li role="presentation">
                                                                        <a role="menuitem" tabindex="-1"
                                                                           href="#" data-path=".user"
                                                                           data-order="desc"
                                                                           data-type="text"><?php _e('User Z-A', 'wpuser') ?></a>
                                                                    </li>

                                                                </ul>

                                                            </div>


                                                        </div>


                                                        <div class="col-md-7">
                                                            <div class="col-md-5">

                                                                <!-- items per page dropdown -->
                                                                <div
                                                                    class="pagination dropdown pull-left jplist-items-per-page"
                                                                    data-control-type="boot-items-per-page-dropdown"
                                                                    data-control-name="paging"
                                                                    data-control-action="paging">

                                                                    <button
                                                                        class="btn btn-default dropdown-toggle"
                                                                        type="button"
                                                                        data-toggle="dropdown"
                                                                        id="dropdown-menu-1"
                                                                        aria-expanded="true">
                                                                        <span
                                                                            data-type="selected-text"><?php _e('Items per Page', 'wpuser') ?></span>
                                                                        <span class="caret"></span>
                                                                    </button>

                                                                    <ul class="dropdown-menu"
                                                                        role="menu"
                                                                        aria-labelledby="dropdown-menu-1">

                                                                        <li role="presentation">
                                                                            <a role="menuitem"
                                                                               tabindex="-1"
                                                                               href="#"
                                                                               data-number="10"><?php _e('10 per page', 'wpuser') ?>
                                                                            </a>
                                                                        </li>

                                                                        <li role="presentation">
                                                                            <a role="menuitem"
                                                                               tabindex="-1"
                                                                               href="#" data-number="20"
                                                                               data-default="true"><?php _e('20 per page', 'wpuser') ?>
                                                                            </a>
                                                                        </li>

                                                                        <li role="presentation">
                                                                            <a role="menuitem"
                                                                               tabindex="-1"
                                                                               href="#"
                                                                               data-number="50"><?php _e('50 per page', 'wpuser') ?>
                                                                            </a>
                                                                        </li>

                                                                        <li role="presentation"
                                                                            class="divider"></li>

                                                                        <li role="presentation">
                                                                            <a role="menuitem"
                                                                               tabindex="-1"
                                                                               href="#"
                                                                               data-number="all"><?php _e('View All', 'wpuser') ?>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>

                                                                <!-- pagination info label -->
                                                                <div
                                                                    class="pagination pull-right jplist-pagination-info"
                                                                    data-type="<strong>Page {current} of {pages}</strong><br/> <small>{start} - {end} of {all}</small>"
                                                                    data-control-type="pagination-info"
                                                                    data-control-name="paging"
                                                                    data-control-action="paging"></div>

                                                            </div>
                                                            <!-- bootstrap pagination control -->
                                                            <ul
                                                                class="pagination col-md-7 pull-right jplist-pagination"
                                                                data-control-type="boot-pagination"
                                                                data-control-name="paging"
                                                                data-control-action="paging"
                                                                data-range="3"
                                                                data-mode="google-like">
                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>


                                            <div class="row list-login">
                                                <div class="col-md-12">
                                                    <div class="col-md-1">
                                                        <h4><?php _e('ID', 'wpuser') ?></h4>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <h4><?php _e('User', 'wpuser') ?></h4>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h4><?php _e('Email', 'wpuser') ?></h4>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h4><?php _e('Description', 'wpuser') ?></h4>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <h4><?php _e('IP', 'wpuser') ?></h4>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <h4><?php _e('Time', 'wpuser') ?></h4>
                                                    </div>
                                                    <hr>
                                                </div>
                                                <?php

                                                $count = 0;
                                                echo '<div class="row">';

                                                foreach ($data as $login_data) {
                                                    $class = ($count & 1) ? 'list-odd-log' : 'list-even-log';
                                                    echo '<div class="col-md-12 list-item-login ' . $class . '" id="user_' . $login_data->user_id . '">';
                                                    echo '<div class="col-md-1" ><span class="log_user_id ">' . esc_html($login_data->user_id) . '</span></div>';
                                                    echo '<div class="col-md-2"><span class="user ">' . esc_html($login_data->user) . '</span></div>';
                                                    echo '<div class="col-md-3 "><span class="user_email ' . ucfirst(($login_data->user_email)) . '">' . ucfirst(($login_data->user_email)) . '</span></div>';
                                                    echo '<div class="col-md-3" ><span class="log_message ">';
                                                    if ($login_data->status == 'Successfull') {
                                                        echo '<span style="color:green" class="glyphicon glyphicon-info-sign"> </span> ';
                                                    }
                                                    if ($login_data->status == 'Failed') {
                                                        echo '<span style="color:red" class="glyphicon glyphicon-info-sign"> </span> ';
                                                    }
                                                    echo esc_html($login_data->message) . '<br>
                                                                            ' . esc_html($login_data->user_agent) . '</span></div>';
                                                    echo '<div class="col-md-1" ><span class="ip ">' . esc_html($login_data->ip) . '</span></div>';
                                                    echo '<div class="col-md-2" ><span class="created_date ">' . esc_html($login_data->created_date) . '</span></div>';
                                                    echo '</div>';
                                                    $count++;
                                                }


                                                ?>
                                            </div>
                                        </div>

                                        <?php
                                    } else {
                                        _e('No Login Logs Found', 'wpuser');
                                    }
                                    ?>

                                </div>

                            </div>
                            <!-- /.tab-content -->
                        </div>
                    </div><!-- /.row -->
                </div>
        </div><!-- /.aj -->

        <?php

        do_action('wp_user_list_setting_after'); ?>
        </section>
        <?php include('view-footer.php'); ?>
        <?php do_action('wp_user_list_setting_footer'); ?>
        <?php //include('view-sidebar.php'); ?>
    </div>
</div>
</div>

<style>
    .list-even, .list-odd {
        height: 50px !important;
    }

    .list-item-action {
        display: none;
        margin-left: 10%;
        cursor: pointer;
    }

    .list-item .username {
        margin-left: 5px;
    }

    .list-item:hover .list-item-action {
        display: block;
    }

    .export_setting {
        display: none;
    }
</style>
