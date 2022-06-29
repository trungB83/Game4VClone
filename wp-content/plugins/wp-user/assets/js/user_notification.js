function getNotification() {
    // $("#wpuser_mail_to_userid").val(id);
    $("#myNotificationBody").html('');
    var wpuser_update_setting= wpuser.wpuser_update_setting
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser.wpuser_ajax_url+'?action=wpuser_get_notification',
        data: 'wpuser_update_setting=' + wpuser_update_setting,
        success: function (response) {
            if (response.status == 'warning')
                $("#myNotificationBody").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
            else if (response.status == 'success') {
                $('#myNotification').show();
                $('#myProfileSection').hide();
                if (response.notifications.length === 0) {
                    $("#myNotificationBody").html('No data Found');
                } else {
                    $.each(response.notifications, function (i, val) {
                        if(val.is_unread==1){
                            var notification_call="alert-info";
                        }else{
                            var notification_call="";
                        }
                        if(val.type_of_notification=='follow'){
                            var notification_icon="fa fa-users";
                        }else  if(val.type_of_notification=='order'){
                            var notification_icon="fa fa-shopping-cart";
                        }else  if(val.type_of_notification=='support'){
                            var notification_icon="fa fa-support";
                        }else  if(val.type_of_notification=='rate'){
                            var notification_icon="fa fa-star";
                        }else  if(val.type_of_notification=='comment'){
                            var notification_icon="fa fa-comment";
                        }else  if(val.type_of_notification=='post'){
                            var notification_icon="fa fa-thumb-tack";
                        }else{
                            var notification_icon="fa fa-check";
                        }
                        var body_html='';
                        if(val.body_html!=null){
                            var body_html= '<br>'+val.body_html
                        }
                        if(val.href==null || val.href.length === 0 || val.href=='#'){
                            var notification_href=" ";
                        }else{
                            var notification_href=" href='"+ val.href +"' target='_blank' ";
                        }
                        $("#myNotificationBody").append('<div id="notification_' + val.id + '" class="notification_' + val.id + ' col-md-12 '+notification_call+' alert-dismissible" onclick="readNotification(' + val.id + ')">'
                            +'<button type="button" class="close" data-toggle="tooltip" data-original-title="Delete Notification" onclick="removeNotification(' + val.id + ')" data-dismiss="alert" aria-hidden="true">×</button>'
                            +'<a '+ notification_href +'><h4><i class="icon '+notification_icon+'"></i>' + val.title_html + '</h4></a>'
                            +body_html
                            +'<br><i class="fa fa-clock-o"></i> '
                            +relative_time(val.created_time)
                            +'</div>');
                    });
                }

            }
        }
    });
}
function removeNotification(id) {
    var wpuser_update_setting= wpuser.wpuser_update_setting
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser.wpuser_ajax_url+'?action=wpuser_delete_notification',
        data: 'id='+id+'&wpuser_update_setting=' + wpuser_update_setting,
        success: function (response) {
            if (response.status == 'success') {
                $('.notification_'+id).hide();
                var notification_count=$('#notification_count').html();
                notification_count=notification_count-1;
                $('#notification_count').val(notification_count);
                $('.notification_count').html(notification_count);
                if(id==0){
                    $('#myNotification').hide();
                    $('#notification_dropdown').hide();
                    $('#myProfileSection').show();
                }

            }
        }
    });
}

function readNotification(id) {
    var wpuser_update_setting= wpuser.wpuser_update_setting
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser.wpuser_ajax_url+'?action=wpuser_read_notification',
        data: 'id='+id+'&wpuser_update_setting=' + wpuser_update_setting,
        success: function (response) {
            if (response.status == 'success') {
                $('.notification_'+id).removeClass('alert-info');
            }
        }
    });
}
function closeNotification() {
    $('#myNotification').hide();
    $('#myProfileSection').show();
}
function relative_time(date_str) {
    if (!date_str) {return;}
    date_str = $.trim(date_str);
    date_str = date_str.replace(/\.\d\d\d+/,""); // remove the milliseconds
    date_str = date_str.replace(/-/,"/").replace(/-/,"/"); //substitute - with /
    date_str = date_str.replace(/T/," ").replace(/Z/," UTC"); //remove T and substitute Z with UTC
    date_str = date_str.replace(/([\+\-]\d\d)\:?(\d\d)/," $1$2"); // +08:00 -> +0800
    var parsed_date = new Date(date_str);
    var relative_to = (arguments.length > 1) ? arguments[1] : new Date(); //defines relative to what ..default is now
    var delta = parseInt((relative_to.getTime()-parsed_date)/1000);
    delta=(delta<2)?2:delta;
    var r = '';
    if (delta < 60) {
        r = delta + ' seconds ago';
    } else if(delta < 120) {
        r = 'a minute ago';
    } else if(delta < (45*60)) {
        r = (parseInt(delta / 60, 10)).toString() + ' minutes ago';
    } else if(delta < (2*60*60)) {
        r = 'an hour ago';
    } else if(delta < (24*60*60)) {
        r = '' + (parseInt(delta / 3600, 10)).toString() + ' hours ago';
    } else if(delta < (48*60*60)) {
        r = 'a day ago';
    } else {
        r = (parseInt(delta / 86400, 10)).toString() + ' days ago';
    }
    return 'about ' + r;
};