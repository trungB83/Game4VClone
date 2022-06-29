jQuery(document).ready(function () {

    jQuery("#update_setting").click(function (e) {
        post_id = jQuery(this).attr("data-post_id")
        nonce = jQuery(this).attr("data-nonce")

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: wpuser_link.wpuser_ajax_url + '?action=wpuser_update_setting',
            data: jQuery("#wpuser_update_setting").serialize(),
            success: function (response) {
                if (response.status == 0)
                    jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
                else if (response.status == 1)
                    jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');
            }
        })

    })

    jQuery("#update_security_setting").click(function (e) {
        post_id = jQuery(this).attr("data-post_id")
        nonce = jQuery(this).attr("data-nonce")

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: wpuser_link.wpuser_ajax_url + '?action=wpuser_update_setting',
            data: jQuery("#wpuser_update_security_setting").serialize(),
            success: function (response) {
                if (response.status == 0)
                    jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
                else if (response.status == 1)
                    jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');
            }
        })

    })

    jQuery("#update_email_setting").click(function (e) {
        post_id = jQuery(this).attr("data-post_id")
        nonce = jQuery(this).attr("data-nonce")

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: wpuser_link.wpuser_ajax_url + '?action=wpuser_update_setting',
            data: jQuery("#wpuser_update_email_setting").serialize(),
            success: function (response) {
                if (response.status == 0)
                    jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
                else if (response.status == 1)
                    jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');
            }
        })

    })

    jQuery("#wpuser_bulk_action").click(function (e) {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: wpuser_link.wpuser_ajax_url + '?action=wpuser_bulk_process',
            data: jQuery("#wpuser_bulk_action_form").serialize(),
            success: function (response) {
                if (response.status == 0)
                    jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
                else if (response.status == 1) {
                    jQuery.each(response.userlist, function (i, val) {
                        if (response.bulk_action == 'Approve') {
                            jQuery("#status_" + val).html('<i style="color:green" class="status fa fa-fw fa-check-circle-o">Approved</i>');
                            jQuery("#user_action_" + val).html('<a><span class="user_action" id="user_action_' + val + '"><span style="color:red">Deny </span></span></a>');
                            jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');

                        }
                        else if (response.bulk_action == 'Deny') {
                            jQuery("#status_" + val).html('<i style="color:red" class="status fa fa-fw  fa-minus-circle">Denied</i>');
                            jQuery("#user_action_" + val).html('<a><span class="user_action" id="user_action_' + val + '"><span style="color:green">Approve </span></span></a>');
                            jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');

                        }
                        else if (response.bulk_action == 'Export') {
                            var data = response.data;
                            JSONToCSVConvertor(data, 'wpuser_list', true);
                            jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');
                            return false;
                        }
                    });
                }
            }
        })

    })


    jQuery("#wpuser_clear_log_action").click(function (e) {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: wpuser_link.wpuser_ajax_url + '?action=wpuser_clear_log',
            data: 'wpuser_update_setting=' + wpuser_link.wpuser_update_setting,
            success: function (response) {
                if (response.status == 0)
                    jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
                else if (response.status == 1)
                    jQuery("#login_log").html('No Login Logs Found');
                jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');
            }
        })

    })


    jQuery("#update_page_setting").click(function (e) {
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: wpuser_link.wpuser_ajax_url + '?action=wpuser_update_page_setting',
            data: jQuery("#wpuser_update_page_setting").serialize(),
            success: function (response) {
                if (response.status == 0)
                    jQuery("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> Error!</h4>' + response.message + '</div>');
                else if (response.status == 1) {
                    jQuery("#response_message").html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-check"></i> Success!</h4>' + response.message + '</div>');
                    jQuery("#wp_user_page_permalink_text").html(response.wp_user_page.permalink);
                    jQuery("#wp_user_page_permalink").attr('href',response.wp_user_page.permalink);
                    jQuery("#wp_user_member_page_text").html(response.wp_user_member_page.permalink);
                    jQuery("#wp_user_member_page_permalink").attr('href',response.wp_user_member_page.permalink);
                }
            }
        })

    })


    function JSONToCSVConvertor(JSONData, fileName, ShowLabel) {
        var arrData = typeof JSONData != 'object' ? JSON.parse(JSONData) : JSONData;
        var CSV = '';
        if (ShowLabel) {
            var row = "";
            for (var index in arrData[0]) {
                row += index + ',';
            }
            row = row.slice(0, -1);
            CSV += row + '\r\n';
        }
        for (var i = 0; i < arrData.length; i++) {
            var row = "";
            for (var index in arrData[i]) {
                var arrValue = arrData[i][index] == null ? "" : '' + arrData[i][index] + '';
                row += arrValue + ',';
            }
            row.slice(0, row.length - 1);
            CSV += row + '\r\n';
        }
        if (CSV == '') {
            growl.error("Invalid data");
            return;
        }
        if (fileName) {
            var fileName = fileName;
        } else {
            var fileName = 'wpuser';
        }
        //var fileName = "Result";
        if (msieversion()) {
            var IEwindow = window.open();
            IEwindow.document.write('sep=,\r\n' + CSV);
            IEwindow.document.close();
            IEwindow.document.execCommand('SaveAs', true, fileName + ".csv");
            IEwindow.close();
        } else {
            var uri = 'data:application/csv;charset=utf-8,' + escape(CSV);
            var link = document.createElement("a");
            link.href = uri;
            link.style = "visibility:hidden";
            link.download = fileName + ".csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    function msieversion() {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie != -1 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer, return version number 
        {
            return true;
        } else { // If another browser, 
            return false;
        }
        return false;
    }


})

