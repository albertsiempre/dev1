<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading"><?php isset($data['event']) ? "Edit" : "Add" ?> Event</p>
        <form action="<?= isset($act_form) ? $act_form : '#'; ?>" class="form-event" method="POST" enctype="multipart/form-data">
            <div class="block-body">
                
                    <label>Logo</label>
                    <div>
                        <input type="file" id="imgInput" name="event_image"/> <br/>
                        <textarea style="display: none;" name="image"></textarea>
                        <div class="_preview_container">
                            <img class="myImage" src="{{ isset($data['event']['image_url']) && $data['event']['image_url'] != null  ?  $data['event']['image_url'] . '?' . date('Ymdhis') : asset('/main/images/no_image.png'); }}" />
                        </div>
                        <div class="_status_image"></div>
                    </div>
                    <hr />
                    
                    <label>Bracket</label>
                    <div>
                        <input type="file" id="imgInputBracket" name="bracket_image"/> <br/>
                        <textarea style="display: none;" name="image_bracket"></textarea>
                        <div class="_preview_container_bracket">
                            <img class="myImageBracket" src="{{ isset($data['event']['bracket_url']) && $data['event']['bracket_url'] != null  ?  $data['event']['bracket_url'] . '?' . date('Ymdhis') : asset('/main/images/no_image.png'); }}" />
                        </div>
                        <div class="_status_image_bracket"></div>
                    </div>
                    <hr />
                    
                    <label>Participating</label>
                    <div>
                        <input type="file" id="imgInputParticipating" name="participating_image"/> <br/>
                        <textarea style="display: none;" name="image_participating"></textarea>
                        <div class="_preview_container_participating">
                            <img class="myImageParticipating" src="{{ isset($data['event']['participating_url']) && $data['event']['participating_url'] != null  ?  $data['event']['participating_url'] . '?' . date('Ymdhis') : asset('/main/images/no_image.png'); }}" />
                        </div>
                        <div class="_status_image_participating"></div>
                    </div>
                    <hr />
                    
                    <label>Event Name</label>
                    <input id="event_name" class="input-xlarge" type="text" value="{{ isset($data['event']['name']) ?  $data['event']['name'] : '' }}" name="event_name">                    

                    <label>Subtitle</label>
                    <textarea class="form-control _my_textarea" rows="3" name="subtitle" id="subtitle">{{ isset($data['event']['subtitle']) ?  $data['event']['subtitle'] : '' }}</textarea>
                    
                    <label>Link</label>
                    <input id="link" class="input-xlarge" type="text" value="{{ isset($data['event']['link']) ?  $data['event']['link'] : '' }}" name="link">                    
                    
                    <label>Start Date</label>
                    <input type="text" name="start_date" id="start_date" class="start_date" value="{{ isset($data['event']['start_date']) ?  date('Y-m-d', strtotime($data['event']['start_date'])) : date('Y-m-d') }}">
                    
                    <label>End Date</label>
                    <input type="text" name="end_date" id="end_date" class="end_date" value="{{ isset($data['event']['end_date']) ?  date('Y-m-d', strtotime($data['event']['end_date'])) : date('Y-m-d') }}">
                    
                    <hr />
                    <label>Rules</label>
                    <textarea id="rule" name="rule"><?php echo isset($data['event']['rule']) ?  $data['event']['rule'] : ''; ?></textarea>   
                    
                    <hr />
                    <label>
                        <input type="checkbox" value="true" name="is_finished" id="is_finished" <?php echo isset($data['event']['is_finished']) && $data['event']['is_finished'] != 0 ?  'checked' : ''?>> Is Finished
                    </label>

            </div>

            <div class="block-body  __my_footer">
                <div class="btn-toolbar">
                    <?php isset($data['event']) ? $capt = "Edit" : $capt = "Save" ?>
                    <button class="btn btn-primary" type="submit"><?php echo $capt?></button>
                    <a class="btn in-closePopup">Cancel</a>
                    <input id="event_id" class="input-xlarge _my_textarea" type="hidden" value="{{ isset($data['event']['id']) ?  $data['event']['id'] : 0 }}" name="event_id">
                    <span id="loading-form"></span>
                </div>
            </div>
        </form>
    </div>
</div>

<!--{{ HTML::script('/main/scripts/page/qinternal/mgs/main.js') }}-->
<script>
    $(document).ready(function(){
        setDatePicker();
        $( '#rule' ).ckeditor();
        
        $("#imgInput").removeAttr("disabled");
        $("#imgInput").change(function(){
            $("textarea[name='image']").html("");
            if($("#_preview_canvas").length > 0)
            {
                $("#_preview_canvas").remove();
            }

            $("._preview_container").after('<canvas id="_preview_canvas" height="0" width="0"></canvas>');

            $(".btn-primary").attr("disabled", true);

            readImage(this);
        }); 
        
        $("#imgInputBracket").removeAttr("disabled");
        $("#imgInputBracket").change(function(){
            $("textarea[name='image_bracket']").html("");
            if($("#_preview_canvas_bracket").length > 0)
            {
                $("#_preview_canvas_bracket").remove();
            }

            $("._preview_container_bracket").after('<canvas id="_preview_canvas_bracket" height="0" width="0"></canvas>');

            $(".btn-primary").attr("disabled", true);

            readImageBracket(this);
        });  
        
        $("#imgInputParticipating").removeAttr("disabled");
        $("#imgInputParticipating").change(function(){
            $("textarea[name='image_participating']").html("");
            if($("#_preview_canvas_participating").length > 0)
            {
                $("#_preview_canvas_participating").remove();
            }

            $("._preview_container_participating").after('<canvas id="_preview_canvas_participating" height="0" width="0"></canvas>');

            $(".btn-primary").attr("disabled", true);

            readImageParticipating(this);
        });        

        function readImage(input) {
            var img = new Image();

            if ( input.files && input.files[0] ) {
                var FR= new FileReader();
                FR.onload = function(e) {              
                    var base64 = e.target.result;               
                    $('.myImage').attr( "src", base64);
                    $(".myImage").css("opacity", "0.5");
                    $("._status_image").html("Proccessing Image ..");
                    var timeout = setTimeout(function(){
                        img.src = base64;
                        var width = img.width;
                        var height = img.height;
                        var canvas = $("#_preview_canvas")[0];
                        var context = canvas.getContext("2d");

                        canvas.height = height;
                        canvas.width = width;
                        context.drawImage(img, 0, 0, width, height);
                        var pngUrl = canvas.toDataURL();
                        if(pngUrl != null)
                        {
//                                    $("#base_64_image").text(base64);
//                                    $("textarea[name='image']").html(pngUrl); //only png
                            $("textarea[name='image']").html(base64);
                            $(".myImage").css("opacity", "1.0");
                            $("._status_image").empty();
                            $(".btn-primary").attr("disabled", false);
                            clearTimeout(timeout);
                        }
                        
                    }, 500);
                };       
                FR.readAsDataURL( input.files[0] );
            }
        }
        
        function readImageBracket(input) {
            var img = new Image();

            if ( input.files && input.files[0] ) {
                var FR= new FileReader();
                FR.onload = function(e) {              
                    var base64 = e.target.result;               
                    $('.myImageBracket').attr( "src", base64);
                    $(".myImageBracket").css("opacity", "0.5");
                    $("._status_image_bracket").html("Proccessing Image ..");
                    var timeout = setTimeout(function(){
                        img.src = base64;
                        var width = img.width;
                        var height = img.height;
                        var canvas = $("#_preview_canvas_bracket")[0];
                        var context = canvas.getContext("2d");

                        canvas.height = height;
                        canvas.width = width;
                        context.drawImage(img, 0, 0, width, height);
                        var pngUrl = canvas.toDataURL();
                        if(pngUrl != null)
                        {
                            $("textarea[name='image_bracket']").html(base64);
                            $(".myImageBracket").css("opacity", "1.0");
                            $("._status_image_bracket").empty();
                            $(".btn-primary").attr("disabled", false);
                            clearTimeout(timeout);
                        }
                        
                    }, 500);
                };       
                FR.readAsDataURL( input.files[0] );
            }
        }
        
        function readImageParticipating(input) {
            var img = new Image();

            if ( input.files && input.files[0] ) {
                var FR= new FileReader();
                FR.onload = function(e) {              
                    var base64 = e.target.result;               
                    $('.myImageParticipating').attr( "src", base64);
                    $(".myImageParticipating").css("opacity", "0.5");
                    $("._status_image_participating").html("Proccessing Image ..");
                    var timeout = setTimeout(function(){
                        img.src = base64;
                        var width = img.width;
                        var height = img.height;
                        var canvas = $("#_preview_canvas_participating")[0];
                        var context = canvas.getContext("2d");

                        canvas.height = height;
                        canvas.width = width;
                        context.drawImage(img, 0, 0, width, height);
                        var pngUrl = canvas.toDataURL();
                        if(pngUrl != null)
                        {
                            $("textarea[name='image_participating']").html(base64);
                            $(".myImageParticipating").css("opacity", "1.0");
                            $("._status_image_participating").empty();
                            $(".btn-primary").attr("disabled", false);
                            clearTimeout(timeout);
                        }
                        
                    }, 500);
                };       
                FR.readAsDataURL( input.files[0] );
            }
        }

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
    });
</script>
<style>
    .popup-content {
        width: 35%;
    }

    ._my_textarea {
        width: 50%;
        resize: vertical;
    }
    .__my_box {
        border: 1px solid #cccccc;
        padding: 4px;
        text-align: center;
        float: right;
        margin-right: 10px;
    }

    .__my_number {
        display: block;
        width: 100%;
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        margin-top: 5px;
    }

    .block-body {
        overflow: hidden;
    }

    .__my_footer {
        margin: 0px;
        padding: 1em;
        background: #ededed;
    }
    
    .myImage, .myImageBracket, .myImageParticipating {
        margin: auto;
        max-width: 120px;
        max-height: 120px;
    }

    #_preview_canvas, #_preview_canvas_bracket, #_preview_canvas_participating {
        display: none;
    }

    ._preview_container, ._preview_container_bracket, ._preview_container_participating {
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

    ._status_image, ._status_image_bracket, ._status_image_participating {
        width: 100%;
        text-align: center;
    }
</style>