<?php if(!get_option('wpuser_hide_coupon') && WPUSER_TYPE=='FREE') { ?>
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><strong>Get All <a target="_blank"
                           href="<?php echo WPUSER_PRO_URL ?>">Pro
                Feature</a> (All
            Add-ons).</strong> <?php echo WPUSER_COUPON ?></h4>
</div>
<?php } ?>