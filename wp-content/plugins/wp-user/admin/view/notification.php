<?php
//Get Pending User notification
$notifications=array();
$meta_key = 'wp-approve-user';
$meta_value = 2;

$args = array(
    'role' => '',
    'role__in' => array(),
    'role__not_in' => array(),
    'meta_key' => $meta_key,
    'meta_value' => $meta_value,
    'meta_compare' => '',
    'meta_query' => array(),
    'date_query' => array(),
    'include' => array(),
    'exclude' => array(),
    'offset' => '',
    'search' => '',
    'number' => '',
    'count_total' => false,
    'fields' => 'id',
);

$blogusers = get_users($args);
$user_count = count($blogusers);
if ($user_count > 0) {
    $notification = $user_count . __(' User Waiting for Approval', 'wpuser');
    $notifications[] = array(
        'notification' => $notification,
        'icon' => 'fa fa-users text-yellow'
    );
}

//Get Denied User notification
$meta_key = 'wp-approve-user';
$meta_value = 5;

$args = array(
    'role' => '',
    'role__in' => array(),
    'role__not_in' => array(),
    'meta_key' => $meta_key,
    'meta_value' => $meta_value,
    'meta_compare' => '',
    'meta_query' => array(),
    'date_query' => array(),
    'include' => array(),
    'exclude' => array(),
    'offset' => '',
    'search' => '',
    'number' => '',
    'count_total' => false,
    'fields' => 'id',
);

$blogusers = get_users($args);
$user_count = count($blogusers);
if ($user_count > 0) {
    $notification = $user_count . __(' User Denied', 'wpuser');
    $notifications[] = array(
        'notification' => $notification,
        'icon' => 'fa fa-users text-red'
    );
}

//Get Approved User notification
$meta_key = 'wp-approve-user';
$meta_value = 1;

$args = array(
    'role' => '',
    'role__in' => array(),
    'role__not_in' => array(),
    'meta_key' => $meta_key,
    'meta_value' => $meta_value,
    'meta_compare' => '',
    'meta_query' => array(),
    'date_query' => array(),
    'include' => array(),
    'exclude' => array(),
    'offset' => '',
    'search' => '',
    'number' => '',
    'count_total' => false,
    'fields' => 'id',
);

$blogusers = get_users($args);
$user_count = count($blogusers);
if ($user_count > 0) {
    $notification = $user_count . __(' User Approved', 'wpuser');
    $notifications[] = array(
        'notification' => $notification,
        'icon' => 'fa fa-users text-green'
    );
}
// New register user count
global $wpdb;

$query = "SELECT COUNT(ID) as count FROM {$wpdb->prefix}users WHERE DATE(user_registered) = CURDATE()";

$result = $wpdb->get_var($query);
if ($result > 0) {
    $notification = $result . __(' new members joined today', 'wpuser');
    $notifications[] = array(
        'notification' => $notification,
        'icon' => 'fa fa-users text-aqua'
    );
}

//Users Failed Login Attempt
$query = "SELECT COUNT(count) as count from(SELECT (COUNT(ID)) as count FROM {$wpdb->prefix}wpuser_login_log WHERE DATE(created_date) = CURDATE() AND message LIKE 'Access denied%' GROUP BY user) as temp";

$result = $wpdb->get_var($query);
if ($result > 0) {
    $notification = $result . __(' Users Failed Login Attempt', 'wpuser');
    $notifications[] = array(
        'notification' => $notification,
        'icon' => 'fa fa-warning text-yellow'
    );
}

$notification_count = count($notifications);
if ($notification_count > 0) {
    ?>
    <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell"></i>
            <span class="label label-warning"><?php echo $notification_count ?></span>
        </a>
        <ul class="dropdown-menu">
            <li class="header"><?php _e('You have ' . $notification_count . ' notifications', 'wpuser') ?></li>
            <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                    <?php foreach ($notifications as $notification) {
                        echo ' 
                    <li>
                    <a href="#">
                        <i class="' . $notification['icon'] . '"></i> ' . $notification['notification'] . '
                    </a>
                    </li>';
                    } ?>
                </ul>
            </li>
            <!--<li class="footer"><a href="#">View all</a></li>-->
        </ul>
    </li>
<?php } ?>