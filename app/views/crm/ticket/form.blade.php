@section('form_popup')
    {{ Form::open(array('url' => $url_form, 'class' => 'form_ticket')) }}
        <input type="hidden" name="id" value="" />
        <?php
            if(isset($sources) && $sources != null)
            {
                ?>
                    <label>Ticket Source</label>
                    <select name="ticket_source_id">
                        <option value="0">Pilih Ticket Source ..</option>
                        <?php
                            foreach($sources as $source)
                            {
                                ?>
                                    <option value="{{ $source['id'] }}">{{ $source['source'] }}</option>
                                <?php
                            }
                        ?>
                    </select>
                <?php
            }
        ?>

        <label>Phone</label>
        <input type="text" name="phone" />

        <label>Username</label>
        <input type="text" name="username" />

        <label>Email</label>
        <input type="text" name="email" />

        <?php
            if(isset($services) && $services != null)
            {
                ?>
                    <label>Service</label>
                    <select name="service_id">
                        <option value="0">Pilih Service</option>
                        <?php
                            foreach($services as $service)
                            {
                                ?>
                                    <option value="{{ $service['id'] }}">{{ $service['name'] }}</option>
                                <?php
                            }
                        ?>
                    </select>
                <?php
            }

            if(isset($subservices) && $subservices != null)
            {
                ?>
                    <label>Subservice</label>
                    <select name="subservice_id">
                        <option value="0">Pilih Sub Service</option>
                        <?php
                            foreach($subservices as $subservice)
                            {
                                ?>
                                    <option value="{{ $subservice['id'] }}">{{ $subservice['name'] }}</option>
                                <?php
                            }
                        ?>
                    </select>
                <?php
            }
        ?>

        <label>Ticket Description</label>
        <textarea id="txtDescription" name="description_fake"></textarea>
        <textarea name="description" id='_txtDescription' style="display: none;"></textarea>
        <hr/>
        <a class="btn" id="form_close" style="margin-right:10px;">Batal</a>
        <button type="submit" class="btn btn-primary _submit_tickets">Simpan</button>
        <div class="clearfix"></div>
        <div class="_error_container"></div>
    </form>

    <script type="text/qscript" id="tmp_error_msg">
        <div id="error_message" class="alert alert-error"></div>
    </script>

    <script type="text/qscript" id="tmp_success_msg">
        <div id="error_message" class="alert alert-success"></div>
    </script>

    <script type="text/javascript">
        $(".form_ticket").submit(function(){
            var form = $(this);
            var form_button = form.find("button[type='submit']");
            var form_loading = $('._error_container');
            var popupClose = $(".mfp-close");
            $("#_txtDescription").val(CKEDITOR.instances.txtDescription.getData());
            form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');
            form_button.attr('disabled',true);
            popupClose.attr("disabled", true);
            form.ajaxSubmit(function(res){
                var obj = $.parseJSON(res);
                if(obj.status == true)
                {
                    needRefresh = true;
                    form_loading.html('<b style="color:green; display: block; margin-top: 10px;">' + obj.message + '</b>');
                    form_button.attr('disabled', false);
                    popupClose.attr("disabled", false);
                    form[0].reset();
                    CKEDITOR.instances.txtDescription.setData("");
                    form_button.remove();
                    $("#form_close").html("close");
                } else {
                    form_loading.html('<b style="color:red; display: block; margin-top: 10px;">' + obj.message + '</b>');
                    form_button.attr('disabled', false);
                    popupClose.attr("disabled", false);
                }
            });
            return false;
        });
    </script>

    <style>
        input[type='radio'] {
            margin-top: -3px;
        }

        .myImage {
            margin: auto;
            max-width: 120px;
            max-height: 120px;
        }

        #_preview_canvas {
            display: none;
        }

        ._preview_container {
            width: 100%;
            overflow: hidden;
            height: auto;
            position: relative;
            display: block;
            text-align: center;
            background: none repeat scroll 0% 0% #F4F4F4;
            margin: 10px auto;
            padding: 10px 0px;
            border: 1px solid #cccccc;
        }

        ._status_image {
            width: 100%;
            text-align: center;
        }
    </style>
@stop