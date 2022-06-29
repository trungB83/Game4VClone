var $ = jQuery.noConflict();
$("#wp_user_profile_div_close").click(function(){
    $("#wp_user_profile_div").hide();
});


$(function() {
    var file_frame;

    $(".additional-user-image").on("click", function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          //  file_frame.open();
          //  return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $( this ).data( "uploader_title" ),
            button: {
                text: $( this ).data( "uploader_button_text" ),
            },
            multiple: false
        });

        var current_id=this.id;

        // When an image is selected, run a callback.
        file_frame.on( "select", function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get("selection").first().toJSON();
            //$(".user_meta_image").val(attachment.url);
            $("#img_"+current_id).val(attachment.url);
            $("#view_"+current_id).attr( 'src', attachment.url);
            $("#user_meta_image_attachment_id").val(attachment.id);


            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();
    });

});


$("#wpuser_update_profile_button").click(function () {
    $.ajax({
        url: wpuser.wpuser_ajax_url+'?action=wpuser_update_profile_action',
        data: $("#google_form").serialize(),
        error: function (data) {
        },
        success: function (data) {
            var parsed = $.parseJSON(data);
            $("#wpuser_errordiv_register").html('<div class="wp-user-alert alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button>' + parsed.message + '</div>');
            if (parsed.status == 'success') {
                $('.wpuser_profile_name').html(parsed.user_info.name);
                $('.wpuser_profile_first_name').html(parsed.user_info.first_name);
                $('.wpuser_profile_last_name').html(parsed.user_info.last_name);
                $('.wpuser_profile_description').html(parsed.user_info.description);
                $('.wpuser_profile_email').html(parsed.user_info.email);
                $('.wpuser_profile_user_url').html(parsed.user_info.user_url);
                $('.wpuser_profile_img').attr('src', parsed.user_info.profile_img);
                $('.profile_background_pic').attr('src', parsed.user_info.profile_background_pic);
                $('.wpuser_profile_strength').attr('style', 'width:' + parsed.user_info.wpuser_profile_strength + '%');
                $('.wpuser_profile_strength').html(parsed.user_info.wpuser_profile_strength + '%');
                $.each(parsed.user_info.advanced, function (i, val) {
                    $('.wpuser_profile_' + i).html(val);
                    $('.wpuser_profile_url_' + i).attr('href', val);
                });
            }
            $('#wpuser_errordiv_register').show();
        },
        type: 'POST'
    });
});

$("#wp_user_address_field_submit").click(function () {
        $.ajax({
            type: "POST",
            url: wpuser.wpuser_ajax_url + '?action=wpuser_address',
            data: $('#wp_user_address_field_form').serialize(),
            error: function (data) {
            },
            success: function (data) {
                var parsed = $.parseJSON(data);
                $("#wp_user_address_label").html(parsed.message);
                $("#wp_user_address_div").removeClass().addClass("wp-user-alert alert alert-dismissible alert-" + parsed.status);
                $("#wp_user_address_div").show();
                $("#pass1").val("");
                $("#pass2").val("");
            }
        });
    });

$("#wp_user_address_div_close").click(function(){
    $("#wp_user_address_div").hide();
});

$("#wp_user_profile_contact_submit").click(function () {
    $.ajax({
        type: "post",
        url: wpuser.wpuser_ajax_url+'?action=wpuser_contact',
        data: $("#wp_user_profile_contact_form").serialize(),
        success: function (data) {
            var parsed = $.parseJSON(data);
            $("#wp_user_contact_div").html('<div class="wp-user-alert alert alert-' + parsed.status + ' alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button>' + parsed.message + '</div>');
            if (parsed.status == 'success') {
                $("#wp_user_profile_contact_form")[0].reset();
            }
            $('#wp_user_contact_div').show();
        },
    });
});

function getGroupFilterData() {
    if (!($('#wpuser_filter_category').length && $('#wpuser_filter_area').length)) {
        getGroupFilter();
    }
}

function getGroupFilter() {
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser.wpuser_ajax_url + '?action=wpuser_getGroupFilterData',
        data: 'type=all',
        success: function (response) {
            if (response.status == 'warning')
                $("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
            else if (response.status == 'success') {
                var filter_html='';
                if (!(response.category.length === 0)) {
                    filter_html +='<div class="form-group col-md-6">';
                    filter_html +='<label>Category</label>';
                    filter_html +='<select id="wpuser_filter_category" name="wpuser_filter_category" class="form-control wpuser_filter_category" multiple="">';
                    $.each(response.category, function (i, val) {
                        filter_html += '<option value="'+val+'">'+val+'</option>';
                    });
                    filter_html +='</select>';
                    filter_html +='</div>';
                    $("#advanced_filter").append(filter_html);
                }
                var filter_html='';
                if (!(response.area.length === 0)) {
                    filter_html +='<div class="form-group col-md-6">';
                    filter_html +='<label>Area</label>';
                    filter_html +='<select id="wpuser_filter_area" name="wpuser_filter_area" class="form-control wpuser_filter_area" multiple="">';
                    $.each(response.area, function (i, val) {
                        filter_html += '<option value="'+val+'">'+val+'</option>';
                    });
                    filter_html +='</select>';
                    filter_html +='</div>';
                    $("#advanced_filter").append(filter_html);
                }
            }
        }
    });
}
function showFilterResult(str) {
    $("#filterlivesearch").html('');
    if (str.length == 0) {
        $("#filterlivesearch").html('');
        document.getElementById("filterlivesearch").style.border = "0px";
        return;
    }else if (str.length >= 4) {
        $("#filterlivesearch").html('');
        $.ajax({
            type: "post",
            dataType: "json",
            url: wpuser.wpuser_ajax_url + '?action=wpuser_getGroupTitleSearch',
            data: 'type=livesearch&wpuser_filter_search=' + str,
            success: function (response) {
                if (response.status == 'success') {
                    if (!(response.list.length === 0)) {
                       // $("#filterlivesearch").append('<ul class="list-group">');
                        $.each(response.list, function (i, val) {
                            $("#filterlivesearch").append('<a class="list-group-item" onclick="setFilterTitle(\''+this.title+'\')">'+val.title+'</a>');
                        });
                       // $("#filterlivesearch").append('</ul>');
                        document.getElementById("filterlivesearch").style.border="1px solid #A5ACB2";
                    }else{
                        $("#filterlivesearch").html('');
                    }
                }
            }
        });
    }
}
function setFilterTitle(htmlString) {
    $('#wpuser_filter_search').val( htmlString );
    $("#filterlivesearch").html('');
}

$("#wpuser_filter_form").focusout(function(){
  //  $("#filterlivesearch").html('');
});

$("#resetFilter").click(function(){
    getGrouprList(1);
});


function getGrouprList(page) {
    // $("#wpuser_mail_to_userid").val(id);
    var wpuser_filter_search= $("#wpuser_filter_search").val();
    var wpuser_filter_category =null;
    if($('#wpuser_filter_category').length){
         wpuser_filter_category = $('#wpuser_filter_category').val();
    }
    var wpuser_filter_area =null;
    if($('#wpuser_filter_category').length){
        wpuser_filter_area = $('#wpuser_filter_area').val();
    }

    if(wpuser_filter_search.length >= 1 || wpuser_filter_area!=null || wpuser_filter_category !=null){
        $('#wpuser_filter').removeClass('text-muted').addClass('text-green');
    }else{
        $('#wpuser_filter').removeClass('text-green').addClass('text-muted');
    }

    if($('#wpuser_filter_by_user').length){
        wpuser_filter_by_user = $('#wpuser_filter_by_user').val();
    }else{
        wpuser_filter_by_user=0;
    }

    if($('#wpuser_my_profile_group').length){
        wpuser_my_profile_group = $('#wpuser_my_profile_group').val();
    }else{
        wpuser_my_profile_group=0;
    }

    $("#find_groups").html('');
    $("#group_pagination").html('');
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser.wpuser_ajax_url + '?action=wpuser_getGrouprList',
        data: 'page=' + page + '&wpuser_filter_search=' + wpuser_filter_search+ '&wpuser_filter_category=' + wpuser_filter_category+ '&wpuser_filter_area=' + wpuser_filter_area+ '&wpuser_filter_by_user=' + wpuser_filter_by_user,
        success: function (response) {
            if (response.status == 'warning')
                $("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
            else if (response.status == 'success') {
                $("#groupTitle").html('Groups - <span class="group_count">' +response.pagination.total_count+'</span>');
                if (response.list.length === 0) {
                    $("#find_groups").html('No Groups Found');
                } else {
                    $.each(response.list, function (i, val) {
                        group_button='';
                        if(wpuser.isUserLogged==1) {
                            if (val.is_admin == 1) {
                                if(wpuser_filter_by_user!=0 && wpuser_my_profile_group!=0) {
                                    var group_button = '<span class="group_join_' + val.id + '" id="group_join_f_' + val.id + '"><button type="button" class="btn btn-warning" onclick="group_action(' + val.id + ',\'delete\')"> Delete Group</button><a class="pull-right" title="Edit" onclick="group_action(' + val.id + ',\'edit\')"><i class="fa fa-fw fa-gear"></i></a></span>';
                                }
                            } else if (val.is_member == 1 && val.is_admin != 1) {
                                var group_button = '<span class="group_join_' + val.id + '" id="group_join_f_' + val.id + '"><button type="button" class="btn btn-default" onclick="group_action(' + val.id + ',\'leave\')"> Leave Group</button></a></span>';
                            } else {
                                var group_button = '<span class="group_join_' + val.id + '" id="group_join_f_' + val.id + '"><button type="button" class="btn btn-primary" onclick="group_action(' + val.id + ',\'join\')"> Join</button></a></span>';
                            }
                        }
                        if(val.title==1){
                            var title='<small class="text-muted">' + val.title + '</small>';
                        }else{
                            var title='';
                        }
                        $("#find_groups").append('<div id="group_f_' + val.id + '" class="group_' + val.id + ' col-lg-6 col-xs-6">'
                            + '<div class="small-box bg-gray">'
                            + '<div class="inner"><label><a class="pull-right" href="#" title="View ' + val.id + '" onclick="group_action(' + val.id + ',\'view\')">' + val.title + '</a></label><p id="group_count"><label class="member_count' + val.id + '" id="member_count' + val.id + '">' + val.member_count + '</label> members</p></div>'
                        + '<div class="icon">'
                        + '<i class="' + val.icon + '"></i>'
                        + '</div>'
                        + '<p class="small-box-footer">'+group_button+'</p>'
                        + '</div>'
                        + '</div>');

                    });
                    if (response.pagination.total_pages > 1) {
                        var pages = ' ';
                        var active = ' ';
                        var page_next = ' ';
                        var page_prev = ' ';
                        for (i = 1; i <= response.pagination.total_pages; i++) {
                            if (i == response.pagination.page) {
                                active = ' active ';
                            } else {
                                active = ' ';
                            }

                            if (response.pagination.page != 1) {
                                page_prev = '<li class="page-item"><a onclick="getGrouprList('+ (parseInt(response.pagination.page) - 1) + ')" class="page-link" tabindex="-1">Previous</a></li>';
                            }else{
                                page_prev = '<li  class="page-item"><a disabled="disabled" class="disabled page-link" tabindex="-1">Previous</a></li>';
                            }
                            if (response.pagination.page != response.pagination.total_pages) {
                                page_next = '<li class="page-item"><a onclick="getGrouprList('+ (parseInt(response.pagination.page) + 1) + ')" class="page-link">Next</a></li>';
                            }else{
                                page_next = '<li class="page-item"><a disabled="disabled" class="disabled page-link">Next</a></li>';
                            }

                            pages = pages + '<li class="page-item ' + active + '"><a onclick="getGrouprList(' + i + ')" class="page-link" >' + i + '</a></li>';
                        }
                        $("#group_pagination").append(
                            page_prev
                            + pages
                            + page_next
                        );
                    }
                }
            }
        }
    });
}



function getMemberListByGroupID(id, page) {
    // $("#wpuser_mail_to_userid").val(id);
    $("#group_members_list").html('');
    $("#group_members_pagination").html('');
    $.ajax({
        type: "post",
        dataType: "json",
        url: wpuser.wpuser_ajax_url + '?action=wpuser_getMemberByGroupID',
        data: 'id=' + id + '&page=' + page ,
        success: function (response) {
            if (response.status == 'warning')
                $("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2000" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
            else if (response.status == 'success') {
                $("#gropupLabel").html('Members -'+response.pagination.total_count);
                if (response.list.length === 0) {
                    $("#follower_list").html('No Members Found');
                } else {
                    $.each(response.list, function (i, val) {
                        if(val.is_admin==1){
                            var is_admin='<small class="text-muted">Group admin</small>';
                        }else{
                            var is_admin='';
                        }
                        if(val.title==1){
                            var title='<small class="text-muted">' + val.title + '</small>';
                        }else{
                            var title='';
                        }
                        $("#group_members_list").append(' <div class="col-md-6 group-list-item list-even" id="follow_user_' + val.id + '">'
                            + '<div class="box box-primary wpuser-custom-box">'
                            + '<div class="box-body box-profile" style="padding:0px !important">'
                            + '<div style="margin: 10px;" class="media-left pos-rel col-md-3">'
                            + '<a> <img class="wpuser-thumb img-circle img-xs" src="' + val.profile_image + '" width="40px" alt="Profile Picture"></a>'
                            + '</div>'
                            + ' <div class="media-body">'
                            + '<div class="pull-left"><a target="_blank" href="' + val.profile_url + '"><h5 class="member_list_display_name mar-no">' + val.name + '</h5></a>'
                            + title
                            + is_admin
                            + '</div>'
                            + '<div class="pull-right" style="margin-top: 10px; margin-right: 10px;">'
                            + '</div>'
                            + '</div>'
                            + '</div>'
                            + '</div></div>');

                    });
                    if (response.pagination.total_pages > 1) {
                        var pages = ' ';
                        var active = ' ';
                        var page_next = ' ';
                        var page_prev = ' ';
                        for (i = 1; i <= response.pagination.total_pages; i++) {
                            if (i == response.pagination.page) {
                                active = ' active ';
                            } else {
                                active = ' ';
                            }

                            if (response.pagination.page != 1) {
                                page_prev = '<li class="page-item"><a onclick="getMemberListByGroupID(' + id + ',' + (parseInt(response.pagination.page) - 1) + ')" class="page-link" tabindex="-1">Previous</a></li>';
                            }
                            if (response.pagination.page != response.pagination.total_pages) {
                                page_next = '<li class="page-item"><a onclick="getMemberListByGroupID(' + id + ',' + (parseInt(response.pagination.page) + 1) + ')" class="page-link">Next</a></li>';
                            }

                            pages = pages + '<li class="page-item ' + active + '"><a onclick="getMemberListByGroupID(' + id + ',' + i + ')" class="page-link" >' + i + '</a></li>';
                        }
                        $("#group_members_pagination").append(
                            page_prev
                            + pages
                            + page_next
                        );
                    }
                }
            }
        }
    });
}

$(".wp-user-alert").fadeTo(1000, 500).slideUp(500, function(){
    $(".wp-user-alert").alert('close');
});

$(".wpuser_sendmail").click(function () {
    $("#wpuser_myModal").modal();
    var modal = $("#wpuser_myModal"),
        dialog = modal.find('.modal-dialog');
    modal.css('display', 'block');
    var wpuser_profile_name=$("#wpuser_profile_name").html();
    $("#wpuser_mail_to_name").html(wpuser_profile_name);
    // Dividing by two centers the modal exactly, but dividing by three
    // or four works better for larger screens.
    // dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
});

$(".wpuser_viewimage").click(function () {
    var wpuser_profile_name = $("#wpuser_profile_name").html();
    var wpuser_profile_image = $(this).attr('src');
    var wpuser_profile_alt = $(this).attr('alt');    
    if( wpuser_profile_alt.length != 0 ){
        wpuser_profile_name = wpuser_profile_name +' ('+ wpuser_profile_alt+ ')';
    }
    $("#wpuser_image_name").html( wpuser_profile_name );
    $("#wpuser_image_url").attr( 'src', wpuser_profile_image );
    $("#wpuser_view_image").modal();
    var modal = $("#wpuser_view_image"),
        dialog = modal.find('.modal-dialog');
    modal.css('display', 'block');
});

$("#wpuser_send_mail").click(function () {
    if(wpuser.wp_user_security_reCaptcha_enable){
        if (grecaptcha.getResponse() == '') {
            $('#wpuser_errordiv_send_mail').html("Please verify Captcha");
            $('#wpuser_errordiv_send_mail').removeClass().addClass('alert alert-dismissible alert-warning');
            $('#wpuser_errordiv_send_mail').show();
            return false;
        }
    }
    $.ajax({
        url: wpuser.wpuser_ajax_url+'?action=wpuser_send_mail_action',
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

$.extend({
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return $.getUrlVars()[name];
    }
});

