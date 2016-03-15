@section('form_popup')
    {{ Form::open(array('url' => $url_form, 'class' => 'form_widget')) }}

        <label>Microsite Category</label>
        <?php
            if(isset($data["games"]))
            {
                $widget = null;
                $selected_category = array();

                if(isset($data['widget']) && !empty($data['widget']))
                {
                    $widget = isset($data['widget']['result'][0]) ? $data['widget']['result'][0] : null;
                    if($widget != null && isset($widget['categories']))
                    {
                        foreach($widget['categories'] as $category)
                        {
                            $selected_category[] = $category['category_id'];
                        }
                    }
                }

                foreach($data["games"] as $id => $val)
                {
                    ?>
                         <input type="checkbox" {{ !empty($selected_category) && in_array($id, $selected_category) ? 'checked' : '' }} name="category_id[]" value="{{ $id }}" /> {{ $val }}<br/>
                    <?php
                }
            }
        ?>
        <hr/>
        <label>Widget Type</label>
        <select name="widget_type_id" class="widget_type">
            <?php
                if(isset($data["type"]))
                {
                    $selected_type = isset($data['widget']['result'][0]['type']['id']) ? $data['widget']['result'][0]['type']['id'] : null; 
                    foreach($data["type"] as $id => $val)
                    {
                        $selected = $selected_type == $id ? true : false;
                        ?>
                            <option value="{{ $id }}" {{ $selected ? 'selected' : '' }}>{{ $val }}</option>
                        <?php
                    }
                }
            ?>
        </select>
        <hr/>
        <label>Start Date</label>
        <div style="display: flex;">
            <?php
                $start_date = null;
                $start_hour = null;
                $start_minute = null;
                if(isset($data['widget']['result'][0]['start_date']))
                {
                    $start_date = date("Y-m-d", strtotime($data['widget']['result'][0]['start_date']));
                    $start_hour = date("H", strtotime($data['widget']['result'][0]['start_date']));
                    $start_minute = date("i", strtotime($data['widget']['result'][0]['start_date']));
                }
            ?>
            <input type="text" class="insert_start_date" style="margin-right: 3px;"  required value="{{ $start_date != null ? $start_date : date('Y-m-d h:i:s') }}" name="start_date" />
            <select name="start_hour" style="width:100px; margin-right: 3px;">
                <?php
                    for($i = 0; $i < 10; $i++)
                    {
                        ?>
                            <option value="0<?= $i; ?>" {{ $start_hour != null && $start_hour == $i ? 'selected' : '' }}>0<?= $i; ?></option>
                        <?php
                    }

                    for($i = 10; $i < 24; $i++)
                    {
                        ?>
                            <option value="<?= $i; ?>" {{ $start_hour != null && $start_hour == $i ? 'selected' : '' }}><?= $i; ?></option>
                        <?php
                    }
                ?>
            </select>
            :
            <select name="start_minute" style="width:100px; margin-right: 3px;">
                <?php
                    for($i = 0; $i < 10; $i++)
                    {
                        ?>
                            <option value="0<?= $i; ?>" {{ $start_minute != null && $start_minute == $i ? 'selected' : '' }}>0<?= $i; ?></option>
                        <?php
                    }

                    for($i = 10; $i < 60; $i++)
                    {
                        ?>
                            <option value="<?= $i; ?>" {{ $start_minute != null && $start_minute == $i ? 'selected' : '' }}><?= $i; ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>

        <label>End Date</label>
        <div style="display: flex;">
            <?php
                $end_date = null;
                $end_hour = null;
                $end_minute = null;
                if(isset($data['widget']['result'][0]['end_date']))
                {
                    $end_date = date("Y-m-d", strtotime($data['widget']['result'][0]['end_date']));
                    $end_hour = date("H", strtotime($data['widget']['result'][0]['end_date']));
                    $end_minute = date("i", strtotime($data['widget']['result'][0]['end_date']));
                }
            ?>
            <input type="text" class="insert_end_date" style="margin-right: 3px;" required value="{{ $end_date != null ? $end_date : date('Y-m-d H:i:s', strtotime('tomorrow')) }}" name="end_date" />
            <select name="end_hour" style="width:100px; margin-right: 3px;">
                <?php
                    for($i = 0; $i < 10; $i++)
                    {
                        ?>
                            <option value="0<?= $i; ?>" {{ $end_hour != null && $end_hour == $i ? 'selected' : '' }}>0<?= $i; ?></option>
                        <?php
                    }

                    for($i = 10; $i < 24; $i++)
                    {
                        ?>
                            <option value="<?= $i; ?>" {{ $end_hour != null && $end_hour == $i ? 'selected' : '' }}><?= $i; ?></option>
                        <?php
                    }
                ?>
            </select>
            :
            <select name="end_minute" style="width:100px; margin-right: 3px;">
                <?php
                    for($i = 0; $i < 10; $i++)
                    {
                        ?>
                            <option value="0<?= $i; ?>" {{ $end_minute != null && $end_minute == $i ? 'selected' : '' }}>0<?= $i; ?></option>
                        <?php
                    }

                    for($i = 10; $i < 60; $i++)
                    {
                        ?>
                            <option value="<?= $i; ?>" {{ $end_minute != null && $end_minute == $i ? 'selected' : '' }}><?= $i; ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
        <hr/>

        <label>Title</label>
        <input type="text" value="{{ isset($data['widget']['result'][0]['title']) ? $data['widget']['result'][0]['title'] : '' }}" required name="title" />
        
        <label>Description</label>
        <textarea name="description">{{ isset($data['widget']['result'][0]['description']) ? $data['widget']['result'][0]['description'] : '' }}</textarea>

        <label>Button Label</label>
        <input type="text" value="{{ isset($data['widget']['result'][0]['label']) ? $data['widget']['result'][0]['label'] : '' }}" name="button_label" />

        <label>Target URL</label>
        <input type="text" value="{{ isset($data['widget']['result'][0]['link']) ? $data['widget']['result'][0]['link'] : '' }}" name="target_url" /> 

        <label>Priority Level</label>
        <input type="number" value="{{ isset($data['widget']['result'][0]['priority_level']) ? $data['widget']['result'][0]['priority_level'] : '' }}" name="priority_level" />

        <label>Survey ID</label>
        <select name="survey_id" class="survey_box" disabled="disabled">
            <?php
                if(isset($data["surveys"]))
                {
                    $selected_type = isset($data['widget']['result'][0]['survey_id']) ? $data['widget']['result'][0]['survey_id'] : null; 
                    foreach($data["surveys"] as $id => $val)
                    {
                        $selected = $selected_type == $id ? true : false;
                        ?>
                            <option value="{{ $id }}" {{ $selected ? 'selected' : '' }}>{{ $val }}</option>
                        <?php
                    }
                }
            ?>
        </select>

        <label>Is Default</label>
        <input type="checkbox" {{ isset($data['widget']['result'][0]['is_default']) && $data['widget']['result'][0]['is_default'] ? 'checked' : '' }} name="is_default" value="1" /> Ya

        <br><hr>
        <div class="_error_container"></div>
        <input type="hidden" name="widget_id" value="{{ isset($data['widget']['result'][0]['id']) ? $data['widget']['result'][0]['id'] : '' }}" />
        <a class="btn" id="form_close" style="margin-right:10px;">Batal</a>
        <button type="submit" class="btn btn-primary" id="qsubmit_widget">Simpan</button>
        <div class="clearfix"></div>
    </form>

    <script type="text/qscript" id="tmp_error_msg">
        <div id="error_message" class="alert alert-error"></div>
    </script>

    <script type="text/qscript" id="tmp_success_msg">
        <div id="error_message" class="alert alert-success"></div>
    </script>

    <script type="text/javascript">
        $(".insert_start_date").datepicker({
            "format"    : "yyyy-mm-dd",
            "autoclose" : true
        });

        $(".widget_type").change(function(){
            var value = $(this).val();
            var box = $(".survey_box");
            $(".survey_box option:first").attr("selected", true);
            if(value == "2")
            {
                box.removeAttr("disabled");
            } else {
                box.attr("disabled", "disabled");
            }
        });

        $(".insert_end_date").datepicker({
            "format"    : "yyyy-mm-dd",
            "autoclose" : true
        });

        $("body").ready(function()
        {
            $(".form_widget").submit(function() {
                var form = $(this);
                var ser = form.serialize();
                $("button[id='qsubmit_widget']").attr("disabled", true);
                $("#form_close").attr("disabled", true);
                form.ajaxSubmit(function(res){
                    var obj = $.parseJSON(res);
                    show_message(obj.result.data, obj.result.status);
                    $("button[id='qsubmit_widget']").attr("disabled", false);
                    $("#form_close").attr("disabled", false);
                    if(obj.result.status == true)
                    {
                        needRefresh = true;
                        form[0].reset();
                        $(".form_widget").find("input[type=text], input[type=number], select, textarea").val("");
                        $(".form_widget").find("input[type=checkbox]").each(function(){ $(this).attr("checked", false); });
                        $("#form_close").html("close");
                    }
                });

                return false;
            });

            function show_message(msg, status)
            {
                var error_template;

                if(status == true)
                {
                    error_template = $("#tmp_success_msg").html();
                } else {
                    error_template = $("#tmp_error_msg").html();
                }

                $("._error_container").html(error_template);
                $("#error_message").empty().append(msg);
            }
        });
    </script>
@stop