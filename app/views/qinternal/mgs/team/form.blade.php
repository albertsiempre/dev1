<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading"><?php isset($data['team']) ? "Edit" : "Add" ?> Team</p>
        <form action="<?= isset($act_form) ? $act_form : '#'; ?>" class="form-team" method="POST" enctype="multipart/form-data">
            <div class="block-body">
                
                    <label>Logo</label>
                    <div>
                        <input type="file" id="imgInput" name="team_logo"/> <br/>
                        <textarea style="display: none;" name="image"></textarea>
                        <div class="_preview_container">
                            <img class="myImage" src="{{ isset($data['team']['image_url']) && $data['team']['image_url'] != null  ?  $data['team']['image_url'] . '?' . date('Ymdhis') : asset('/main/images/no_image.png'); }}" />
                        </div>
                        <div class="_status_image"></div>
                    </div>
                    <hr />
                    
                    <label>Event</label>
                    <select name="event_id" class="event_id" style="width: 350px;">
                        <?php
                            if(isset($data["event"]))
                            {
                                foreach($data["event"] as $event)
                                {
                                    ?>
                                    <option value="{{ $event['id'] }}" <?php echo isset($data['team']['event']['id']) && $event['id'] == $data['team']['event']['id'] ? 'selected' : '' ?>>{{ $event['full_name'] }}</option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    
                    <label>Team Name</label>
                    <input id="team_name" class="input-xlarge" type="text" value="{{ isset($data['team']['name']) ?  $data['team']['name'] : '' }}" name="team_name">                    

                    <label>Order</label>
                    <input id="order" class="input-xlarge" type="number" value="{{ isset($data['team']['order']) ?  $data['team']['order'] : '' }}" name="order">   

            </div>

            <div class="block-body  __my_footer">
                <div class="btn-toolbar">
                    <?php isset($data['team']) ? $capt = "Edit" : $capt = "Save" ?>
                    <button class="btn btn-primary" type="submit"><?php echo $capt?></button>
                    <a class="btn in-closePopup">Cancel</a>
                    <input id="team_id" class="input-xlarge _my_textarea" type="hidden" value="{{ isset($data['team']['id']) ?  $data['team']['id'] : 0 }}" name="team_id">
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
        $(".event_id").chosen();
        
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

        function readImage(input) {
            var MAX_SIZE = 120;
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

                        if(width != 0 && height != 0)
                        {
                            if(width >= MAX_SIZE || height >= MAX_SIZE)
                            {
                                if(width == height)
                                {
                                    height = MAX_SIZE;
                                    width = MAX_SIZE;
                                } else if (width > height) {
                                    height = Math.ceil(height / width * MAX_SIZE);
                                    width = MAX_SIZE;
                                } else {
                                    height = MAX_SIZE;
                                    width = Math.ceil(width / height * MAX_SIZE);
                                }

        //                                    console.log(width, height, MAX_SIZE);
                                canvas.height = height;
                                canvas.width = width;
                                context.drawImage(img, 0, 0, width, height);
                                var pngUrl = canvas.toDataURL();
                                if(pngUrl != null)
                                {
                                    $("textarea[name='image']").html(base64);
                                    $(".myImage").css("opacity", "1.0");
                                    $("._status_image").empty();
                                    $(".btn-primary").attr("disabled", false);
                                    clearTimeout(timeout);
                                }
                            } else {
                                $("textarea[name='image']").html("");
                                $(".myImage").removeAttr("src");
                                $("._status_image").html("Image Size harus lebih dari 120px");
                                $(".btn-primary").attr("disabled", false);
                                clearTimeout(timeout);
                            }
                        }
                    }, 500);
                };       
                FR.readAsDataURL( input.files[0] );
            }
        }        
    });
</script>
<style>
    .popup-content {
        width: 30%;
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