<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading">Ticket Response</p>
        <div class="block-body">
            <div class="_conv_container">
                <?php
                    $params = array(
                        "type"      => $tickets['admin_id'] == "0" ? 'user' : 'admin',
                        'message'   => $tickets['description'] != null ? $tickets['description'] : '-',
                        'username'  => $tickets['name'] != null ? $tickets['name'] : '-',
                        'date'      => $tickets['created_date'] != null ? $tickets['created_date'] : date("Y-m-d h:i:s"),
                        'image'     => $tickets['photo'] != null ? $tickets['photo'] : null,
                        'attachment'=> $tickets['file_url'] != null ? $tickets['file_url'] : null
                    );

                    if($params['type'] == "user")
                    {
                        ?>
                            <div class="_msg_container">
                                <div class="_msg_ava _user">
                                    <img src="<?= $params['image']; ?>" />
                                </div>

                                <div class="_msg_box _user">
                                    <strong><?= $params['username']; ?></strong><br/>
                                    <span class="_mini_date"><?= date("d M Y H:i:s", strtotime($params['date'])); ?></span><br/>
                                    <?= $params['message']; ?>
                                    <?php
                                        if($params['attachment'] != null)
                                        {
                                            ?>
                                                <br/>
                                                <i class="icon-file"></i> Attachment :<br/>
                                                <a href="<?= $params['attachment']; ?>" target="_blank"><?= $params['attachment']; ?></a>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php
                    } else {
                        ?>
                            <div class="_msg_container">
                                <div class="_msg_ava _admin">
                                    <img src="<?= $params['image']; ?>" />
                                </div>

                                <div class="_msg_box _admin">
                                    <strong><?= $params['username']; ?></strong><br/>
                                    <span class="_mini_date"><?= date("d M Y H:i:s", strtotime($params['date'])); ?></span><br/>
                                    <?= $params['message']; ?>
                                    <?php
                                        if($params['attachment'] != null)
                                        {
                                            ?>
                                                <br/>
                                                <i class="icon-file"></i> Attachment :<br/>
                                                <a href="<?= $params['attachment']; ?>" target="_blank"><?= $params['attachment']; ?></a>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        <?php
                    }

                    if(isset($tickets['message']) && !empty($tickets['message']))
                    {
                        foreach($tickets['message'] as $msg)
                        {
                            $params = array(
                                "type"      => $msg['admin_id'] == "0" ? 'user' : 'admin',
                                'message'   => $msg['message'] != null ? $msg['message'] : '-',
                                'username'  => $msg['user_name'] == null ? $msg['admin_id'] == "0" ? "User" : "Admin" : $msg['user_name'],
                                'date'      => $msg['created_date'] != null ? $msg['created_date'] : date("Y-m-d h:i:s"),
                                'image'     => $msg['photo'] != null ? $msg['photo'] : null,
                                'attachment'=> $msg['file_url'] != null ? $msg['file_url'] : null,
                                'is_visible'=> $msg['is_visible']
                            );

                            if($params['type'] == "user")
                            {
                                ?>
                                    <div class="_msg_container">
                                        <div class="_msg_ava _user">
                                            <img src="<?= $params['image']; ?>" />
                                        </div>

                                        <div class="_msg_box _user">
                                            <strong><?= $params['username']; ?></strong><br/>
                                            <span class="_mini_date"><?= date("d M Y H:i:s", strtotime($params['date'])); ?></span><br/>
                                            <?= $params['message']; ?>
                                            <?php
                                                if($params['attachment'] != null)
                                                {
                                                    ?>
                                                        <br/>
                                                        <i class="icon-file"></i> Attachment :<br/>
                                                        <a href="<?= $params['attachment']; ?>" target="_blank"><?= $params['attachment']; ?></a>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                            } else {
                                ?>
                                    <div class="_msg_container">
                                        <div class="_msg_ava _admin">
                                            <img src="<?= $params['image']; ?>" />
                                        </div>

                                        <div class="_msg_box _admin">
                                            <strong <?= $params['is_visible'] == 0 ? "class='__is_visible_msg'" : ""; ?>><?= $params['username']; ?></strong><br/>
                                            <span class="_mini_date"><?= date("d M Y H:i:s", strtotime($params['date'])); ?></span><br/>
                                            <span <?= $params['is_visible'] == 0 ? "class='__is_visible_msg'" : ""; ?>><?= $params['message']; ?></span>
                                            <?php
                                                if($params['attachment'] != null)
                                                {
                                                    ?>
                                                        <br/>
                                                        <i class="icon-file"></i> Attachment :<br/>
                                                        <a href="<?= $params['attachment']; ?>" target="_blank"><?= $params['attachment']; ?></a>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                            }
                        }
                    }
                ?>
            </div>

            {{ Form::open(array('url' => $url_form, 'class' => 'form_answer_ticket','files'=> true)) }}
                <?php
                    if(isset($tickets['list_status']) && !empty($tickets['list_status']))
                    {
                        ?>
                            <select name="status_id">
                                <option value="-">Pilih status ..</option>
                                <?php
                                    foreach($tickets['list_status'] as $status)
                                    {
                                        ?>
                                            <option value="{{ $status['id'] }}">{{ $status['name'] }}</option>
                                        <?php
                                    }
                                ?>
                            </select>
                        <?php
                    }
                ?>
                <!-- <label>Ticket Description</label> -->
                <textarea id="txtDescription" name="description_fake"></textarea>
                <textarea name="description" id='_txtDescription' style="display: none;"></textarea>
                <hr/>
                <input type="checkbox" checked="checked" value="1" name="is_visible" style="margin-top: -2px;" /> <span style="margin-top: 3px;">Is Visible?</span>
                <hr/>
                <label>Attachment</label>
                <input type="file" name="rawAttachment" id="attachmentInput" /><br/><br/>
                <span id="_attachment_process"></span>
                <textarea style="display: none;" name="attachment" id="txtAttachment"></textarea>
                <input type="hidden" name="attachment_ext" id="attachment_ext"/>
                <hr/>
                <input type="hidden" name="ticket_id" value="<?= $tickets['id']; ?>" />
                <a class="btn" id="form_close" style="margin-right:10px;">Batal</a>
                <button type="submit" class="btn btn-primary _submit_answer_tickets">Simpan</button>
                <div class="clearfix"></div>
                <div class="_error_container"></div>
            </form>
        </div>
    </div>
</div>

<script type="text/qscript" id="tmp_error_msg">
    <div id="error_message" class="alert alert-error"></div>
</script>

<script type="text/qscript" id="tmp_success_msg">
    <div id="error_message" class="alert alert-success"></div>
</script>

<script type="text/javascript">
    $(".form_answer_ticket").submit(function(){
        var form = $(this);
        var form_button = form.find("button[type='submit']");
        var form_loading = $('._error_container');
        var popupClose = $(".mfp-close");
        var dsc = CKEDITOR.instances.txtDescription.getData();
        console.log("description = " + dsc);
        console.log("Desc = " + dsc.length);
        if(dsc.length > 0)
        {
            $("#_txtDescription").val(CKEDITOR.instances.txtDescription.getData());
            form_loading.html('<img src="../main/images/ajax-loader-small.gif"> Sending..');
            form_button.attr('disabled',true);
            popupClose.attr("disabled", true);
            form.ajaxSubmit(function(res){
                console.log(res);
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
        } else {
            form_loading.html('<b style="color:red; display: block; margin-top: 10px;">Description Required.</b>');
            form_button.attr('disabled', false);
            popupClose.attr("disabled", false);
            return false;
        }
        return false;
    });

    $("body").on("click", "#form_close", function(){
        var magnificPopup = $.magnificPopup.instance;
        magnificPopup.close();
        if(needRefresh)
        {
            $("._doSearch").click();
        }
    });

    $("#attachmentInput").change(function(){
        var inputFile = this;
        var fileEl = $(this);
        fileEl.attr("disabled", "disabled");
        $("._submit_answer_tickets").attr("disabled", "disabled");
        if(inputFile && inputFile.files[0])
        {
            var file = inputFile.files[0];
            var array = new Array();
            array = file.name.split(".");

            var reader = new FileReader();
            reader.readAsDataURL(file);
            var filenames = array[array.length-1];
            var filename = filenames.toLowerCase();
            if(filename == "zip"
                || filename == "rar"
                || filename == "jpeg"
                || filename == "png"
                || filename == "jpg")
            {
                if(file.size <= 7340032)
                {
                    $("#_attachment_process").html("Processing " + (file.size / 1048576).toFixed(2) + " MB <strong>" + filenames + "</strong> File ...");
                    reader.onload = function(){
                        var base64 = reader.result;
                        var timeout = setTimeout(function(){
                            if(base64 != null)
                            {
                                $("#_attachment_process").html("");
                                console.log("Your Base64 = " + base64);
                                $("#txtAttachment").val(base64);
                                $("#attachment_ext").val(filename)
                                $("._submit_answer_tickets").removeAttr("disabled");
                                fileEl.removeAttr("disabled");
                                clearTimeout(timeout);
                            }
                        }, 500);
                    };

                    $("#_attachment_process").html("");
                    $("._submit_answer_tickets").removeAttr("disabled");
                    fileEl.removeAttr("disabled");
                } else {
                    alert("File Anda sebesar : " + file.size + " terlalu besar.");
                    $("#attachmentInput").val("");
                    fileEl.removeAttr("disabled");
                }
            } else {
                alert("File Anda : " + filenames + " tidak support.");
                $("#attachmentInput").val("");
                fileEl.removeAttr("disabled");
            }
        } else {
            alert("File tidak dipilih");
            $("#attachmentInput").val("");
            fileEl.removeAttr("disabled");
        }
    });
</script>

<style>
    #_attachment_process {
        color: #193B5D;
    }

    .popup-content {
        width: 25%;
    }

    .__is_visible_msg {
        color: #e74c3c;
    }

    ._conv_container {
        width: 100%;
        min-height: 1px;
        height: auto;
        padding-bottom: 10px;
        border-bottom: 1px solid #cccccc;
        position: relative;
        display: block;
        overflow: hidden;
        margin-bottom: 20px;
        max-height: 400px;
        overflow-y: scroll;
    }

    ._mini_date {
        font-size: 12px;
        color: #999;
    }

    ._msg_container {
        overflow: hidden;
        padding-bottom: 10px;
        /*border-bottom: 1px solid #ccc;*/
        margin-bottom: 10px;
        position: relative;
        display: block;
        width: 99%;
    }

    ._msg_container:last-child {
        border-bottom: 0px solid transparent;
        padding-bottom: 0px;
    }

    ._msg_ava {
        width: 50px;
        height: 50px;
        border-radius: 4px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border: 1px solid #cccccc;
        overflow: hidden;
    }

    ._msg_ava img {
        width: 100%;
    }

    ._msg_box {
        border: 1px solid #ccc;
        padding: 5px;
        min-width: 200px;
    }

    ._msg_ava._user {
        float: left;
        margin-right: 5px;
    }

    ._msg_box._user {
        float: left;
        min-width: 0px;
        max-width: 80%;
        overflow: hidden;
        position: relative;
        display: block;
        border-bottom-right-radius: 5px;
    }

    ._msg_ava._admin {
        float: right;
        margin-left: 5px;
    }

    ._msg_box._admin {
        float: right;
        min-width: 0px;
        max-width: 80%;
        overflow: hidden;
        position: relative;
        display: block;
        border-bottom-left-radius: 5px;
        background: rgba(224, 246, 229, 1);
    }

</style>