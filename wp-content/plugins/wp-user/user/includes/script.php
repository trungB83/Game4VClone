<?php
if(!is_user_logged_in()) {
    $wp_user_security_reCaptcha_enable = (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) ? 1 : 0;
 ?>
    <script>
    var wpuser = {wpuser_ajax_url:'<?php echo admin_url('admin-ajax.php')?>',wp_user_security_reCaptcha_enable:<?php echo $wp_user_security_reCaptcha_enable?>,login_redirect:'<?php echo $login_redirect?>'};
    var $ = jQuery.noConflict();

    $(".navtabs a").click(function(){
         $(this).tab('show');
     });

    $(".step_btn_prev").click(function () {
        var prevTab = $(this).attr('data-prev');
        var currentTab = $(this).attr( 'data-current' );
        $('#step_count_' + prevTab).trigger('click');
        $('#step_count_' + prevTab).removeClass().addClass( 'badge bg-blue' );
        $('#step_count_' + currentTab).removeClass().addClass( 'badge bg-gray' );
    });

    $(".step_btn_next").click(function () {
        if($("#google_form<?php echo $form_id ?>").valid()){
            var nextTab = $(this).attr( 'data-next' );
            $('#step_count_' + nextTab).trigger( 'click' );
            var currentTab = $(this).attr( 'data-current' );
            $('#step_count_' + currentTab).removeClass().addClass( 'badge bg-green' );
            $('#step_count_' + currentTab).parent().removeClass( 'wpuser_step_disable' );
            $('#step_count_' + nextTab).removeClass().addClass( 'badge bg-blue' );
            $('#step_count_' + nextTab).parent().removeClass( 'wpuser_step_disable' );
        }
        //$('#step_'+prevTab).tab('show');
    });

    $("#wpuser_register<?php echo $form_id ?>").click(function () {
        //if( false == $("#google_form<?php echo $form_id ?>")[0].checkValidity() ) {
        //    $("input:focus:invalid").css("border-color","red");
        //    return true;
       // }
        if ( $("#google_form<?php echo $form_id ?>").valid() ) {


            if (wpuser.wp_user_security_reCaptcha_enable == 1) {
                if (grecaptcha.getResponse() == '') {
                    $('#wpuser_error_register<?php echo $form_id ?>').html("Please verify Captcha");
                    $('#wpuser_errordiv_register<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-warning');
                    $('#wpuser_errordiv_register<?php echo $form_id ?>').show();
                    return false;
                }
            }
            $.ajax({
                url: wpuser.wpuser_ajax_url + '?action=wpuser_register_action',
                data: $("#google_form<?php echo $form_id ?>").serialize(),
                error: function (data) {
                },
                success: function (data) {
                    var parsed = $.parseJSON(data);
                    $('#wpuser_error_register<?php echo $form_id ?>').html('');
                    $(".form-control").removeClass("wpuser_invalid");
                    $(".wpuser_error").removeClass("wpuser_view_error");
                    $('.wpuser_error').hide();
                    $('#wpuser_error_register<?php echo $form_id ?>').html(parsed.message);
                    $('#wpuser_errordiv_register<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-' + parsed.status);
                    if (parsed.status == 'success') {
                        $("#google_form<?php echo $form_id ?>")[0].reset();
                    }
                    if (parsed.status == 'warning' && typeof( parsed.error ) !== "undefined" && ( parsed.error.length != 0 )) {
                        $.each(parsed.error, function (key, value) {
                            if (( typeof( value.message ) !== "undefined" )) {
                                $('#error' + key).html(value.message);
                                $('#error' + key).addClass('wpuser_view_error');
                                $('#' + key).addClass('wpuser_invalid');
                            }
                        });
                        $('.wpuser_view_error').show();

                        if( typeof( parsed.error_in_forms ) !== "undefined" && ( parsed.error_in_forms.length != 0 ) ){
                            $.each(parsed.error_in_forms, function (key, value) {
                                $('#step_count_' + key).removeClass().addClass('badge bg-red');
                            });
                        }
                    }
                    if (parsed.message == 'Registration completed') {
                        window.location.reload(true);
                    }
                    $('#wpuser_errordiv_register<?php echo $form_id ?>').show();
                    $("#loader_action").hide();
                    $('html, body').animate({
                        scrollTop: $('#wpuser_errordiv_register<?php echo $form_id ?>').offset().top
                    }, 2000);
                },
                type: 'POST'
            });
        }
});

    $("#wpuser_login<?php echo $form_id ?>").click(function () {
      $("#loader_action").show();
        $.ajax({
        url: wpuser.wpuser_ajax_url+'?action=wpuser_login_action',
        data: $( "#wpuser_login_form<?php echo $form_id ?>" ).serialize(),
        error: function (data) {
        },
        success: function (data) {
            var parsed = $.parseJSON(data);
            $('#upuser_error<?php echo $form_id ?>').html(parsed.message);
            $('#wpuser_errordiv<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-' + parsed.status);
            $('#wpuser_errordiv<?php echo $form_id ?>').show();
            if ( parsed.status == 'success' ) {
                <?php if(get_option('wp_user_enable_two_step_auth')== 1){ ?>
                    if ( parsed.step == '2' ) {
                      $("#div_wp_user_password<?php echo $form_id ?>").hide();
                      $("#wpuser_otp_<?php echo $form_id ?>").show();
                    } else {
                  <?php }  ?>

                $("#wpuser_login_form<?php echo $form_id ?>")[0].reset();
                var redirectURL = '<?php echo (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) ? urldecode($_GET['redirect_to']) : '';?>'
                if ( !( redirectURL.length === 0 ) ) {
                    window.location.href = redirectURL;
                }
                else if ( wpuser.register_redirect.length != 0 ) {
                    window.location.href = wpuser.register_redirect;
                }
                else if ( wpuser.login_redirect == null || ( typeof( wpuser.login_redirect ) !== "undefined" && wpuser.login_redirect.length === 0 ) ) {
                    location.reload();
                }
                else {
                    window.location.href = wpuser.login_redirect;
                }
                  <?php if(get_option('wp_user_enable_two_step_auth')== 1){ ?>
                  }
                  <?php } ?>
              }
                $("#loader_action").hide();
        },
        type: 'POST'
    });
});

<?php if(get_option('wp_user_disable_login_otp')!= 1 || get_option('wp_user_enable_two_step_auth') == 1 ){ ?>

  $("#wpuser_login_resend_otp<?php echo $form_id ?>").click(function () {
    console.log('wpuser_login_resend_otp');
      $( "#wpuser_login_otp<?php echo $form_id ?>" ).trigger( "click" );
  });

  $("#wpuser_otp_password_div<?php echo $form_id ?>").click(function () {
     $( "#wpuser_otp_div<?php echo $form_id ?>" ).show();
     $("#wpuser_otp_<?php echo $form_id ?>").hide();
     $("#div_wp_user_password<?php echo $form_id ?>").show();
     $("#wpuser_otp_password_div<?php echo $form_id ?>").hide();
  });

$("#wpuser_login_otp<?php echo $form_id ?>").click(function () {
  console.log('wpuser_login_resend_otp111111111');
  $("#loader_action").show();
    $.ajax({
    url: wpuser.wpuser_ajax_url+'?action=wpuser_login_otp_action',
    data: $( "#wpuser_login_form<?php echo $form_id ?>" ).serialize(),
    error: function (data) {
    },
    success: function (data) {
        var parsed = $.parseJSON(data);
        $('#upuser_error<?php echo $form_id ?>').html(parsed.message);
        $('#wpuser_errordiv<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-' + parsed.status);
        $('#wpuser_errordiv<?php echo $form_id ?>').show();
        if ( parsed.status == 'success' ) {
            $("#div_wp_user_password<?php echo $form_id ?>").hide();
            $("#wpuser_otp_div<?php echo $form_id ?>").hide();
            $("#wpuser_otp_password_div<?php echo $form_id ?>").show();
            $("#wpuser_otp_<?php echo $form_id ?>").show();
        }
        $("#loader_action").hide();
    },
    type: 'POST'
});
});
<?php } ?>

    $("#wpuser_forgot<?php echo $form_id ?>").click(function () {
      $("#loader_action").show();
        $.ajax({
        url: wpuser.wpuser_ajax_url+'?action=wpuser_forgot_action',
        data: $("#wpuser_forgot_form<?php echo $form_id ?>").serialize(),
        error: function (data) {
        },
        success: function (data) {
            var parsed = $.parseJSON(data);
            $('#upuser_error_forgot<?php echo $form_id ?>').html(parsed.message);
            $('#wpuser_errordiv_forgot<?php echo $form_id ?>').removeClass().addClass('alert alert-dismissible alert-' + parsed.status);
            if (parsed.status == 'success') {
                $("#wpuser_forgot_form<?php echo $form_id ?>")[0].reset();
            }
            $('#wpuser_errordiv_forgot<?php echo $form_id ?>').show();
            $("#loader_action").hide();
        },
        type: 'POST'
    });
});

    $("#wp_login_btn<?php echo $form_id ?>").click(function () {
        $('#wp_login<?php echo $form_id ?>').modal();
        var modal = $("#wp_login<?php echo $form_id ?>"),
        dialog = modal.find('.modal-dialog');
    modal.css('display', 'block');
    // Dividing by two centers the modal exactly, but dividing by three
    // or four works better for larger screens.
    dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
});

    $("#wp_user_profile_div_close").click(function () {
        $("#wp_user_profile_div").hide();
    });
    $(function () {
        var file_frame;

        $(".additional-user-image").on("click", function (event) {

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data("uploader_title"),
            button: {
                text: $(this).data("uploader_button_text"),
            },
            multiple: false
        });

        var current_id = this.id;

        // When an image is selected, run a callback.
        file_frame.on("select", function () {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get("selection").first().toJSON();
            //$(".user_meta_image").val(attachment.url);
            $("#img_" + current_id).val(attachment.url);
            $("#user_meta_image_attachment_id").val(attachment.id);


            // Do something with attachment.id and/or attachment.url here
        });

        // Finally, open the modal
        file_frame.open();
    });

    });
    </script>
<?php
}else{ ?>
    <script>
    jQuery(document).ready(function() {
        jQuery('.wpuser_timepicker').timepicker(
            { 'timeFormat': 'H:i','step':'1'}
        );

       // var wpuser_timepicker = jQuery('.wpuser_timepicker' ).width();
      //  jQuery( '.ui-timepicker-wrapper' ).width( wpuser_timepicker );
	});

    </script>
<?php } ?>
