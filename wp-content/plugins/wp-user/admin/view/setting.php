<div class="bootstrap-wrapper hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php include('view-header.php'); ?>
        <div class="content-wrapper">
            <?php include('alert-pro.php'); ?>
            <section class="content-header">
                <h1>
                    WP User
                    <small>Setting</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Setting</li>
                </ol>
            </section>
            <section class="content">
                <?php do_action('wp_user_setting_before');
                include('option.php');
                include('view-setting.php');
                do_action('wp_user_setting_after'); ?>
            </section>
            <?php include('view-footer.php'); ?>
            <?php do_action('wp_user_setting_footer'); ?>
            <?php //include('view-sidebar.php'); ?>
        </div>
    </div>
</div>

