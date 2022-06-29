<?php
if (!class_exists('WPUserLayout')) :

    class WPUserLayoutApperence
    {

        public function __construct()
        {
            add_action("wpuser_setting_appearance", array($this, 'wpuser_setting_appearance'));
            add_action("wpuser_appearance_skin", array($this, 'wpuser_appearance_skin'));
        }

        public function wpuser_setting_appearance()
        {
            $wpuser_skin = array(
                array(
                    'skin' => 'blue',
                    'color' => 'blue',
                ),
                array(
                    'skin' => 'purple',
                    'color' => 'purple',
                ),
                array(
                    'skin' => 'green',
                    'color' => 'green',
                ),
                array(
                    'skin' => 'red',
                    'color' => 'red',
                ),
                array(
                    'skin' => 'yellow',
                    'color' => 'yellow',
                ),
                array(
                    'skin' => 'black',
                    'color' => 'black',
                ),
                array(
                    'skin' => 'blue-light',
                    'color' => 'blue',
                ),
                array(
                    'skin' => 'purple-light',
                    'color' => 'purple',
                ),
                array(
                    'skin' => 'green-light',
                    'color' => 'green',
                ),
                array(
                    'skin' => 'red-light',
                    'color' => 'red',
                ),
                array(
                    'skin' => 'yellow-light',
                    'color' => 'yellow',
                ),
                array(
                    'skin' => 'black-light',
                    'color' => 'black',
                ),
            );

            $wp_user_appearance_skin_color = get_option('wp_user_appearance_skin_color');
            $wp_user_appearance = unserialize(get_option('wp_user_appearance'));
            ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3"><?php _e('Link Color', 'wpuser') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                   value="<?php echo (isset($wp_user_appearance['link']['color']) && !empty($wp_user_appearance['link']['color'])) ? $wp_user_appearance['link']['color'] : '' ?>"
                                   name="wp_user_appearance[link][color]">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3"><?php _e('Box Border Top Color', 'wpuser') ?></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                   value="<?php echo (isset($wp_user_appearance['box']['border_color']) && !empty($wp_user_appearance['box']['border_color'])) ? $wp_user_appearance['box']['border_color'] : '' ?>"
                                   name="wp_user_appearance[box][border_color]">
                        </div>
                    </div>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php _e('Skins', 'wpuser') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="list-unstyled clearfix row">
                        <?php
                        foreach ($wpuser_skin as $skin) {
                            $appearance_skin = (isset($wp_user_appearance_skin_color) && $wp_user_appearance_skin_color == $skin['skin']) ? "checked" : '';
                            echo ' <li class="col-md-2 skin_color" style="padding: 5px;>
                    <a href="#" data-skin="skin-' . $skin['skin'] . '" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover"><div>
                    <span class="bg-' . $skin['color'] . '" style="display:block; width: 20%; float: left; height: 7px; background: #222d32"></span>
                    <span class="bg-' . $skin['color'] . '" style="display:block; width: 80%; float: left; height: 7px;"></span>
                    </div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #fff">
                    <input style="margin:0px;"type="radio" value="' . $skin['skin'] . '" ' . $appearance_skin . ' name="wp_user_appearance_skin_color">
                    </span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7">' . ucfirst($skin['skin']) . '</span></div></a>
                    </li>';
                        }
                        ?>

                    </ul>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php _e('Buttons', 'wpuser') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-3"><?php _e('Type', 'wpuser') ?></label>
                                <div class="col-md-9">
                                    <input style="margin:0px;" type="radio"
                                           value="btn-normal" <?php echo @checked('btn-normal', $wp_user_appearance['button']['type'], false) ?>
                                           name="wp_user_appearance[button][type]">
                                    <button type="button" class="btn btn-default"><?php _e('Noramal', 'wpuser') ?></button>
                                    <input style="margin:0px;" type="radio"
                                           value="btn-flat" <?php echo @checked('btn-flat', $wp_user_appearance['button']['type'], false) ?>
                                           name="wp_user_appearance[button][type]">
                                    <button type="button"
                                            class="btn btn-flat btn-default"><?php _e('Flat', 'wpuser') ?></button>
                                    <input style="margin:0px;" type="radio"
                                           value="btn-lg" <?php echo @checked('btn-lg', $wp_user_appearance['button']['type'], false) ?>
                                           name="wp_user_appearance[button][type]">
                                    <button type="button" class="btn btn-lg btn-default"><?php _e('Long', 'wpuser') ?></button>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-md-3"><?php _e('Background Color', 'wpuser') ?>:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                           value="<?php echo (isset($wp_user_appearance['button']['background_color']) && !empty($wp_user_appearance['button']['background_color'])) ? $wp_user_appearance['button']['background_color'] : '' ?>"
                                           name="wp_user_appearance[button][background_color]">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3"><?php _e('Text Color', 'wpuser') ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                           value="<?php echo (isset($wp_user_appearance['button']['text_color']) && !empty($wp_user_appearance['button']['text_color'])) ? $wp_user_appearance['button']['text_color'] : '' ?>"
                                           name="wp_user_appearance[button][text_color]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php _e('Form Header', 'wpuser') ?></h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-3"><?php _e('Text Color', 'wpuser') ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                           value="<?php echo (isset($wp_user_appearance['form_header']['text_color']) && !empty($wp_user_appearance['form_header']['text_color'])) ? $wp_user_appearance['form_header']['text_color'] : '' ?>"
                                           name="wp_user_appearance[form_header][text_color]">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-md-3"><?php _e('Background Color', 'wpuser') ?></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element"
                                           value="<?php echo (isset($wp_user_appearance['form_header']['background_color']) && !empty($wp_user_appearance['form_header']['background_color'])) ? $wp_user_appearance['form_header']['background_color'] : '' ?>"
                                           name="wp_user_appearance[form_header][background_color]">
                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-md-3"><?php _e('Background Image', 'wpuser') ?></label>
                                <div class="col-md-9">
                                    <div class="input-group input-group-sm user_meta_image">
                                        <input type="text" name="wp_user_appearance[form_header][background_image]"
                                               id="user_meta_image_attachment_id"
                                               value="<?php echo (isset($wp_user_appearance['form_header']['background_image']) && !empty($wp_user_appearance['form_header']['background_image'])) ? $wp_user_appearance['form_header']['background_image'] : '' ?>"
                                               class="form-control"/>
                    <span style="vertical-align: top;" class="input-group-btn">
                      <button type="button" id="wp_user_appearance_form_header_background_img"
                              class="additional-user-image btn btn-info btn-flat" value="Upload Image"/>
                        <?php _e('Upload Image', 'wpuser') ?></button>
                    </span>
                                    </div>
                                </div>

                            </div>


                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>

            <script>
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
                            $("#user_meta_image_attachment_id").val(attachment.url);
                            // $("#user_meta_image_attachment_id").val(attachment.id);


                            // Do something with attachment.id and/or attachment.url here
                        });

                        // Finally, open the modal
                        file_frame.open();
                    });

                });
                //Colorpicker
                $('.my-colorpicker1').colorpicker();
                //color picker with addon
                // $('.my-colorpicker2').colorpicker();
            </script>
            <?php
        }

        public function wpuser_appearance_skin()
        {
            $appearance_style = '';
            global $wp_user_appearance_button_type;
            $wp_user_appearance = unserialize(get_option('wp_user_appearance'));
            $wp_user_appearance_button_type = (isset($wp_user_appearance['button']['type']) && !empty($wp_user_appearance['button']['type'])) ? $wp_user_appearance['button']['type'] : 'btn-flat';
            $appearance_style .= (isset($wp_user_appearance['button']['background_color']) && !empty($wp_user_appearance['button']['background_color'])) ? '.bootstrap-wrapper .wpuser_button {background-color :' . $wp_user_appearance['button']['background_color'] . ' !important;}' : '';
            $appearance_style .= (isset($wp_user_appearance['button']['text_color']) && !empty($wp_user_appearance['button']['text_color'])) ? '.bootstrap-wrapper .wpuser_button {color :' . $wp_user_appearance['button']['text_color'] . ' !important;}' : '';
            $appearance_style .= (isset($wp_user_appearance['form_header']['background_image']) && !empty($wp_user_appearance['form_header']['background_image'])) ? '.bootstrap-wrapper .wpuser_form_header {background-image :url(' . $wp_user_appearance['form_header']['background_image'] . ') !important;}' : '';
            $appearance_style .= (isset($wp_user_appearance['form_header']['background_color']) && !empty($wp_user_appearance['form_header']['background_color'])) ? '.bootstrap-wrapper .wpuser_form_header {background-color :' . $wp_user_appearance['form_header']['background_color'] . ' !important;}' : '';
            $appearance_style .= (isset($wp_user_appearance['form_header']['text_color']) && !empty($wp_user_appearance['form_header']['text_color'])) ? '.bootstrap-wrapper .wpuser_form_header {color :' . $wp_user_appearance['form_header']['text_color'] . ' !important;}' : '';
            $appearance_style .= (isset($wp_user_appearance['link']['color']) && !empty($wp_user_appearance['link']['color'])) ? '.bootstrap-wrapper a {color :' . $wp_user_appearance['link']['color'] . ' !important;}' : '';
            $appearance_style .= (isset($wp_user_appearance['box']['border_color']) && !empty($wp_user_appearance['box']['border_color'])) ? '.bootstrap-wrapper .wpuser-custom-box , .bootstrap-wrapper .nav-tabs-custom > .nav-tabs > li.active {border-top-color :' . $wp_user_appearance['box']['border_color'] . ' !important;}' : '';

            if (!empty($appearance_style)) {
                echo "<style>$appearance_style</style>";
            }
        }
    }
    $obj = new WPUserLayoutApperence();
endif;

