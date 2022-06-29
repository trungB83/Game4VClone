<header class="main-header">
    <!-- Logo -->
    <a class="logo">
        
       <span>
<span style="margin-top:5px;font-size:35px" class="dashicons dashicons-admin-users"></span>&nbsp;&nbsp;&nbsp;<?php _e('Wp User', 'upuser') ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Tasks: style can be found in dropdown.less -->
                <?php do_action('wp_user_setting_header'); ?>
                <?php include('notification.php') ?>
                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">6</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">5 Plugins and 1 Theme</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a target="_blank" href="http://wpallbackup.com/">WP ALL
                                        Backup
                                        <h3>
                                            <small class="pull-right">Buy</small>
                                        </h3>
                                        <div>
                                            Backup and restore your entire site.
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                                <li><!-- Task item -->
                                    <a target="_blank"
                                       href="<?php echo WPUSER_PRO_URL ?>"> WP User Pro version.
                                        <h3>
                                            <small class="pull-right">Buy</small>
                                        </h3>
                                    </a>
                                </li><!-- end task item -->
                                <li><!-- Task item -->
                                    <a target="_blank" href="https://wordpress.org/plugins/wp-database-backup/">WP
                                        Database Backup
                                        <h3>
                                            <small class="pull-right">Free</small>
                                        </h3>
                                        <div>
                                            Database Backup and restore.
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                                <li><!-- Task item -->
                                    <a target="_blank" href="http://www.wpseeds.com/product/popuppro/">Popuppro
                                        <h3>
                                            <small class="pull-right">Buy</small>
                                        </h3>
                                        <div>
                                            Create easy popup.
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                                <li><!-- Task item -->
                                    <a target="_blank" href="http://www.wpseeds.com/product/wp_subscription/">WP
                                        Subscription
                                        <h3>
                                            <small class="pull-right">Buy</small>
                                        </h3>
                                        <div>
                                            Supports MailChimp, Aweber and Campaign Monitor
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                                <li><!-- Task item -->
                                    <a target="_blank" href="http://www.wpseeds.com/product/wpunderconstruction/">WP
                                        Under Construction
                                        <h3>
                                            <small class="pull-right">Buy</small>
                                        </h3>
                                        <div>
                                            Under Construction, Coming Soon WordPress Theme
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a target='blank' href="http://www.wpseeds.com/shop/">View all Products</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
<span>
<span class="dashicons dashicons-admin-users"></span><?php _e(' Wp User', 'upuser') ?></span>
                        
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <a target="_blank" href="http://wpuserplus.com/"><img
                                    src="<?php echo WPUSER_PLUGIN_URL ?>assets/images/wpseedslogo.png"
                                    class="img-circle" alt="User Image"></a>
                            <p>
                                <a style="color:white" target="_blank" href="http://walkeprashant.in/">Prashant
                                    Walke</a>
                                <small>WordPress Developer</small>
                            </p>
                        </li>
                        <li class="user-body">
                            <a target="_blank"
                               href="<?php echo WPUSER_PRO_URL ?>">Get WP User Pro
                                Feature</a>
                        </li>
                        <li class="user-footer">
                            <a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/wp-user">Rate WP
                                User plugin <i class=" fa fa-star"></i><i class=" fa fa-star"></i><i
                                    class=" fa fa-star"></i><i class=" fa fa-star"></i><i class=" fa fa-star"></i> </a>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
