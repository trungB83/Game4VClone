var $ = jQuery.noConflict();

function sendMail(id, name) {
    $("#wpuser_mail_to_userid").val(id);
    $("#wpuser_mail_to_name").html(name);
    $("#wpuser_myModal").modal();
    var modal = $("#wpuser_myModal"),
        dialog = modal.find('.modal-dialog');
    modal.css('display', 'block');
    // Dividing by two centers the modal exactly, but dividing by three
    // or four works better for larger screens.
    // dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
}


$(".wpuser_sendmail").click(function () {
    $("#wpuser_myModal").modal();
    var modal = $("#wpuser_myModal"),
        dialog = modal.find('.modal-dialog');
    modal.css('display', 'block');
    // Dividing by two centers the modal exactly, but dividing by three
    // or four works better for larger screens.
    // dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
});

function viewProfile(id) {
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser_member.wpuser_ajax_url+'?action=wpuser_user_details',
        data: 'id=' + id + '&wpuser_update_setting='+wpuser_member.wpuser_update_setting,
        success: function (response) {
            if (response.status == 0)
                $("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
            else if (response.status == 1) {
                $("#wpuser_member_list").css("display", "none");
                $("#wpuser_member_profile").css("display", "block");
                $(".wpuser_profile_name").html(response.name);
                $("#wpuser_mail_to_name").html(response.name);
                $("#wpuser_profile_title").html(response.labels);
                $(".wpuser_mail_to_userid").val(response.id);
                $("#wpuser_mail_to_userid").val(response.id);
                $('#wpuser_profile_image').attr('src', response.wp_user_profile_img);

                var user_row = '';
                $.each(response.user_info, function (i, val) {
                    if(i=='wpuser_profile_strength') {
                        $('.wpuser_profile_strength').html(val + '%');
                        $('.wpuser_profile_strength').css("width",val+ '%');
                    }else{
                        user_row = user_row + '<tr class="user_info"><td>' + i + '</td><td>' + val + '</td></tr>';
                    }
                });
                $(".wpuser_user_info").html(user_row);
                var user_header = '';
                $.each(response.header_block_info, function (ar, arval) {
                    var header_attr= ' ';
                    var header_onclick =' ';
                    if(arval["url"]!='#'){
                        header_attr='target="_blank" href="' + arval["url"] + '"';
                    }
                    if(arval["id"]=='wpuser_profile_follower' || arval["id"]=='wpuser_profile_following'){
                        header_onclick ='onclick="getFollower(\''+arval["type"]+'\')"';
                    }
                    user_header = user_header + '<div class="navbar-header"><a class="navbar-brand fontfollow"  '+header_attr+' style="margin:0px;"'+header_onclick+' ><i class="' + arval["icon"] + '"> ' + arval["name"] + '(' + arval["count"] + ')</i></a> </div>';
                });
                $(".wpuser_user_header").html(user_header);
                $("#wpuser_member_header").css("background-image", 'url("' + response.wp_user_background_img + '")');
                $("#profile_follow_button").html(response.user_header_follow_button);
                $("#wpuser_profile_badge").html(response.user_badge);
            }
        }
    });
    $('#wpuser_followModal').modal('hide');
}
$("#wpuser_send_mail").click(function () {
     if(wpuser_member.wp_user_security_reCaptcha_enable){
        if (grecaptcha.getResponse() == '') {
            $('#wpuser_errordiv_send_mail').html("Please verify Captcha");
            $('#wpuser_errordiv_send_mail').removeClass().addClass('alert alert-dismissible alert-warning');
            $('#wpuser_errordiv_send_mail').show();
            return false;
        }
     }
    $.ajax({
        url: wpuser_member.wpuser_ajax_url+'?action=wpuser_send_mail_action',
        data: $("#google_form").serialize(),
        error: function (data) {
        },
        success: function (data) {
            var parsed = $.parseJSON(data);
            $('#wpuser_errordiv_send_mail').html(parsed.message);
            $('#wpuser_errordiv_send_mail').removeClass().addClass('alert alert-dismissible alert-' + parsed.status);
            if (parsed.status == 'success') {
                $("#google_form")[0].reset();
            }
            $('#wpuser_errordiv_send_mail').show();
        },
        type: 'POST'
    });
});

function getUserList(page) {
    $("#wp_user_members_list").addClass('loadig-div');
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser_member.wpuser_ajax_url + '?action=wpuser_user_list',
        data: $("#wpuser_filter_member_list_form").serialize()+'&page=' + page + '&wpuser_update_setting='+wpuser_member.wpuser_update_setting,
        beforeSend: function(){
        $("#loader").show();
    },
        success: function (response) {
            member_list(response,page);
        },
        complete:function(data){
            $("#loader").hide();
            $("#wp_user_members_list").removeClass('loadig-div');
        }
    });
}



function getOrderUserList(orderby, order) {
    $("#wp_user_members_list").addClass('loadig-div');
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser_member.wpuser_ajax_url + '?action=wpuser_user_list',
        data: $("#wpuser_filter_member_list_form").serialize()+'&orderby=' + orderby + '&order=' + order + '&wpuser_update_setting='+wpuser_member.wpuser_update_setting,
        beforeSend: function(){
            $("#loader").show();
        },
        success: function (response) {
            member_list(response,1);
        },
        complete:function(data){
            $("#loader").hide();
            $("#wp_user_members_list").removeClass('loadig-div');
        }
    });
}




$("#wpuser_filter_member_list_clear").click(function () {
    $('#wpuser_filter_member_list_form').trigger("reset");
    $('.filter_title').removeClass('text-green');
    getUserList(1);
});

$("#wpuser_filter_member_list").click(function () {
    $("#wp_user_members_list").addClass('loadig-div');
    $.ajax({
        url: wpuser_member.wpuser_ajax_url + '?action=wpuser_user_list',
        dataType: "json",
        data: $("#wpuser_filter_member_list_form").serialize(),
        error: function (response) {
        },
        beforeSend: function(){
            $("#loader").show();
            $('#filterSidenav').hide();
        },
        success: function (response) {
           $("#members_pagination").html('');
            $('.filter_title').addClass('text-green');
            member_list(response, 1);
        },
        complete:function(data){
            $("#loader").hide();
            $("#wp_user_members_list").removeClass('loadig-div');
        },
        type: 'POST'
    });
});

function member_list( response, page ) {
    if (typeof(Storage) !== "undefined") {
        localStorage.setItem("page", page);
    }


    var template = wpuser_member.template ;
    var col_header = '';
    if (response.status == 'warning')
        $("#wp_user_response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
    else if (response.status == 'success') {
        $("#wp_user_members_list").html('');
        if (response.list.length === 0) {
            $("#wp_user_members_list").html('<div class="callout callout-default"><h4>No Data Found</h4></div>');
        } else {
            $.each(response.list, function (i, val) {
                if (val.user_icon.length === 0) {
                    var user_icon='';
                }else{
                    var alt_class = 'odd';
                    if( (i % 2)==0 ){
                        alt_class = 'even';
                    }

                    var user_icon='';
                    $.each(val.user_icon, function (key, value) {
                        var is_click='';
                        if(value.is_click == 1) {
                            is_click = 'onclick="sendMail('+val.user_id+',\'' + val.user_name + '\')"';
                        }
                        var newLine="";
                        if(value.id == 'address') {
                            newLine='<br>';
                        }
                        if(value.url != 0) {
                            var titleText = '';
                            user_icon = user_icon + newLine + ' <a class="badge bg-' + value.class + '" data-toggle="tooltip"  data-original-title="' + value.count + ' ' + value.name + ' " target="_blank" href="' + value.url + '"><span class="profile-icon"><i class="' + value.icon + '" title="' + value.count + ' ' + value.name + ' "></i>'+ titleText +' </span></a> ';
                        }else{
                            if(value.id == 'gender') {
                                user_icon = user_icon + newLine + ' <span class="badge bg-' + value.class + '" data-toggle="tooltip"  data-original-title="' + value.name + ' " class="profile-icon"><i ' + is_click + ' class="' + value.icon + '" title="'  + value.name + ' "></i> </span> ';
                            }else if(value.id == 'diet') {
                                var iconColorClass = 'text-green';
                                if( value.icon == 'Veg' ){
                                    iconColorClass = 'text-green';
                                }else  if( value.icon == 'Non-Veg' ){
                                    iconColorClass = 'text-red';
                                }
                                user_icon = user_icon + newLine + ' <span class="badge bg-' + value.class + '" data-toggle="tooltip"  data-original-title="' + value.name + ' " class="profile-icon '+ iconColorClass +'"><i ' + is_click + ' class="' + value.icon + '" title="'  + value.name + ' "></i> </span> ';
                            }else{
                                user_icon = user_icon + newLine + ' <span class="badge bg-' + value.class + '" data-toggle="tooltip"  data-original-title="' + value.name + ' " class="profile-icon"><i ' + is_click + ' class="' + value.icon + '" title="' + value.name + ' "></i> ' + value.count + ' </span> ';
                            }
                        }

                    });
                }
                var follow_button = '';
                if (val.user_follow.is_follow_setting === 1) {
                    if (val.user_follow.is_follow == 'unfollow') {
                        var button = 'unfollow';
                        var buttonClass = 'btn-default';
                    } else {
                        var button = 'follow';
                        var buttonClass = 'btn-primary';
                    }

                    var follow_button = '<div class="panel-footer user-footer '+alt_class+'">'
                       + '<span id="popup_wpuser_follow_' + val.user_id + '" class="wpuser_follow wpuser_follow_' + val.user_id + '"><button type="button" class="wpuser_button wpuser_follow_button btn btn-flat  ' + buttonClass + '" onclick="followUser(' + val.user_id + ',\'' + button + '\',\'small\',\'0\')">' + button + '</button></span>'
                       + '</div>';
                }

                var user_profile = wpuser_member.wpuser_view_profile_url+'&user_id='+val.user_id;
                var user_name = val.user_name;
                var count = 20;
                var strim_user_name = user_name.slice(0, count) + (user_name.length > count ? "..." : "");
                if( template == 'rounded' ) {
                    $("#wp_user_members_list").append(' <div class="wpuser-view col-md-4 col-sm-6 user-item" id="user_' + val.user_id + '">'

                        + '<div class="text-center">'
                        + '<div class="">'
                        + '<div class="heading">'
                        + '<div class="">'
                        + '<div class="user-header">'
                        + '<a href="' + user_profile + '"><img height="150" width="150" src="' + val.wp_user_profile_img + '" alt="people" class="img-circle height-150"></a>'
                        + '</div>'
                        + '<div class="">'
                        + '<div class="info usename"><a data-toggle="tooltip" data-placement="top" title="' + val.user_name + '" href="' + user_profile + '">' + strim_user_name + '</a></div>'
                        + '<small>' + val.title + '</small>'
                        + '<div class="profile-icons">'
                        + user_icon
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        // +'<div class="panel-body">'

                        // +'</div>'
                        + '<div class="">'
                        // + '<a href="#" class="btn btn-default btn-sm">Follow  <i class="fa fa-share"></i></a>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                    );

                }else{

                  if( wpuser_member.view == 'list' ){                    
                      var view_col = 2;
                      var list_view_col = 12;
                      var header_style ='style="display:none"';
                  }else{
                    var list_view_col = 6;
                    var view_col = 12;
                    var header_style ="";
                  }

                    var html_user_body = '';
                    if (val.user_body.length != 0) {
                        $.each(val.user_body, function (key, value) {
                            if (value.meta_key.length != 0) {
                                if( i == 0 ){
                                    col_header = col_header + '<strong class="col-md-2">' + value.label + '</strong>';
                                }
                                html_user_body =  html_user_body
                                    + '<div  class="wpuser-view-col col-sm-12 col-md-'+view_col+'">'
                                        +'<spam><spam '+header_style+' class="wpuser_label">' + value.label + ' : </spam>' + value.value +'</spam>'
                                    + '</div>';
                            }

                        });
                    }

                    $("#wp_user_members_list").append(' <div class="wpuser-view col-md-' + list_view_col +' col-sm-12 item" id="user_' + val.user_id + '">'

                        + '<div class="">'
                        + '<div class="panel panel-default">'
                        + '<div class="panel-body user-heading '+alt_class+'">'
                        + '<div class="media">'
                        + '<div class="pull-left wpuser-profile">'
                        + '<img id="user_image_' + val.user_id + '" onclick="profileImage(' + val.user_id + ',\'' + val.user_name + '\')" height="100" width="100" src="' + val.wp_user_profile_img + '" alt="people" class="wpuser_profile_image wpuser-thumb media-object img-circle">'
                        + '</div>'
                        + '<div class="media-body row">'
                        + '<div class="wpuser-view-col col-sm-12 col-md-'+view_col+'">'
                        + '<div class="info usename">'
                        + '<a data-toggle="tooltip" data-placement="top" title="' + val.user_name + '" href="' + user_profile + '">' + strim_user_name + '</a>'
                        + '</div>'
                        + '<small>' + val.title + '</small>'
                        + '<div class="profile-icons">'
                        + user_icon
                        + '</div>'
                        + '</div>'
                        + html_user_body
                        + '</div>'
                        + '</div>'
                        + '</div>'

                        + follow_button
                        + '</div>'
                        + '</div>'
                        + '</div>'
                    );
                }


            });

            var user_count = (page * response.pagination.per_page);
            var from_user_count = (user_count - response.pagination.per_page) + 1;
            if( user_count > response.pagination.total_count ){
                var user_count = response.pagination.total_count;
            }
            $("#members_pages").html(
                'Page '+ page + ' of ' +response.pagination.total_pages +'<br>'+
                from_user_count+ '-'+ user_count + ' of ' +response.pagination.total_count
            );

            if (response.pagination.total_pages > 1) {
                var pages = ' ';
                var active = ' ';
                var page_next = ' ';
                var page_prev = ' ';
                i = 1
                var start_page = 1;
                if(response.pagination.page > 5){
                    start_page = ( page - 2);
                }
                var j= 1;
                for (i = start_page; i <= response.pagination.total_pages; i++) {
                    if (i == response.pagination.page) {
                        active = ' active ';
                    } else {
                        active = ' ';
                    }


                    if (response.pagination.page != 1) {
                        page_prev = '<li class="page-item"><a onclick="getUserList(' + (parseInt(response.pagination.page) - 1) + ')" class="page-link" tabindex="-1">Previous</a></li>';
                    } else {
                        page_prev = '<li  class="page-item"><a disabled="disabled" class="disabled page-link" tabindex="-1">Previous</a></li>';
                    }
                    page_prev = page_prev + '<li class="page-item"><a onclick="getUserList(1)" class="page-link" tabindex="-1"> << </a></li>';
                    page_next = '<li class="page-item"><a onclick="getUserList(' + (parseInt(response.pagination.total_pages)) + ')" class="page-link"> >> </a></li>' ;

                    if (response.pagination.page != response.pagination.total_pages) {
                        page_next = page_next + '<li class="page-item"><a onclick="getUserList(' + (parseInt(response.pagination.page) + 1) + ')" class="page-link">Next</a></li>';
                    } else {
                        page_next = page_next + '<li class="page-item"><a disabled="disabled" class="disabled page-link">Next</a></li>';
                    }
                    if( j > 5){
                        pages = pages + '<li class="page-item ' + active + '"><a onclick="getUserList(' + i + ')" class="page-link" >....</a></li>';
                        break;
                    }
                    j++;
                    pages = pages + '<li class="page-item ' + active + '"><a onclick="getUserList(' + i + ')" class="page-link" >' + i + '</a></li>';
                }
                $("#members_pagination").html(
                    page_prev
                    + pages
                    + page_next
                );
            }

        }
    }

    $("#wp_user_members_header").html(
        '<div class="panel panel-default">'
        + '<div class="panel-body user-heading">'
        + '<div class="media">'
        + '<div class="pull-left">'
        + '</div>'
        + '<div style="padding-left: 60px;" class="media-body row">'
        + '<div class="col-md-2">'
        + '<div class="info usename">'
        + '<strong>Name</strong>'
        + '</div>'
        + '</div>'
        + col_header
        + '</div>'
        + '</div>'
        + '</div>'
        + '</div>'
    );

    if (typeof(Storage) !== "undefined") {
        if (typeof(localStorage.grid) !== "undefined") {
            var grid = localStorage.grid;
        }
    }
    if( typeof(grid) !== "undefined" && grid == 1){
        $( '.wpuser-view').removeClass("col-md-6").removeClass("col-md-4").addClass("col-md-12");
        $( '.wpuser-view-col').removeClass("col-md-12").addClass("col-md-2");
        $( '.wpuser_label' ).hide();
        $( '.wpuser_profile_image').removeClass("wpuser_viewImage").addClass('img-circle');
        $( '#wp_user_members_header' ).show();
        $( '.wpuser_user_list').removeClass( 'grid' ).addClass( 'list');
    } else if(grid == 2){
        $( '.wpuser-view').removeClass("col-md-12").removeClass("col-md-4").addClass("col-md-6");
        $( '.wpuser-view-col').removeClass("col-md-2").addClass("col-md-12");
        $( '.wpuser_label' ).show();
        $( '.wpuser_profile_image').removeClass("img-circle").addClass('wpuser_viewImage');
        $( '#wp_user_members_header' ).hide();
        $( '.wpuser_user_list').removeClass( 'list' ).addClass( 'grid');
    } else if(grid == 3){
        $( '.wpuser-view').removeClass("col-md-12").removeClass("col-md-6").addClass("col-md-4");
        $( '.wpuser-view-col').removeClass("col-md-2").addClass("col-md-12");
        $( '.wpuser_label' ).show();
        $( '.wpuser_profile_image').removeClass("wpuser_viewImage").addClass('img-circle');
        $( '#wp_user_members_header' ).hide();
        $( '.wpuser_user_list').removeClass( 'list' ).addClass( 'grid');
    }

}

$(".wpuser_profile_image").click(function () {
   // var wpuser_profile_name = $("#wpuser_profile_name").html();

});

function profileImage(id, name) {
    var wpuser_profile_image = $('#user_image_'+id).attr('src');
    $("#wpuser_image_name").html( name );
    $("#wpuser_image_url").attr( 'src', wpuser_profile_image );
    $("#wpuser_view_image").modal();
    var modal = $("#wpuser_view_image"),
        dialog = modal.find('.modal-dialog');
    modal.css('display', 'block');
}



$(function(){
    var page = 1 ;
    if (typeof(Storage) !== "undefined") {
        if (typeof(localStorage.page) !== "undefined") {
            var page = localStorage.page;
        }
    }
    getUserList(page);
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $('.dropdown-toggle').dropdown();
});
