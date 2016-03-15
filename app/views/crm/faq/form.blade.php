@section('form_popup')
    {{ Form::open(array('url' => $url_form, 'class' => 'form_faq')) }}
        <label>Service</label>
        <select name="category_id" id="__category_id">
            <?php
                if(isset($services) && !empty($services))
                {
                    foreach($services as $srv)
                    {
                        ?>
                            <option value="<?= $srv['id']; ?>"><?= $srv['name']; ?></option>
                        <?php
                    }
                }
            ?>
        </select>

        <label>Sub Service</label>
        <select name="subcategory_id" id="__subcategory_id">
            <?php
                if(isset($subservices) && !empty($subservices))
                {
                    foreach($subservices as $srv)
                    {
                        ?>
                            <option value="<?= $srv['id']; ?>"><?= $srv['name']; ?></option>
                        <?php
                    }
                }
            ?>
        </select>
        
        <label>Question</label>
        <input type="text" id="__question" required name="question" />

        <hr/>
        <label>Is Public</label>
        <input type="checkbox" name="is_public" id="__is_public" value="1" /> Yes
        
        <hr/>
        <label>Answer</label>
        <textarea name="description_fake" id="txtDescription"></textarea>
        <textarea name="description" id='_txtAnswer' style="display: none;"></textarea>

        <hr/>
        <label>Order</label>
        <input type="number" name='order' id="__order" />

        <br><hr>
        <div class="_error_container"></div>
        <input type="hidden" name="faq_id" />
        <a class="btn" id="form_close" style="margin-right:10px;">Batal</a>
        <button type="submit" class="btn btn-primary" id="qsubmit_faq">Simpan</button>
        <div class="clearfix"></div>
    </form>

    <script type="text/qscript" id="tmp_error_msg">
        <div id="error_message" class="alert alert-error"></div>
    </script>

    <script type="text/qscript" id="tmp_success_msg">
        <div id="error_message" class="alert alert-success"></div>
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

    {{ HTML::script('/main/scripts/page/crm/faq/form.js') }}
    <script>
        $(document).ready(function(){
            $("#imgInput").removeAttr("disabled");
            var MAX_SIZE = 120;
            var img = new Image();
            $(".sel_city").chosen();

            $("#imgInput").change(function(){
                $("textarea[name='image']").html("");
                if($("#_preview_canvas").length > 0)
                {
                    $("#_preview_canvas").remove();
                }

                $("._preview_container").after('<canvas id="_preview_canvas" height="0" width="0"></canvas>');
                
                $("#qsubmit_warnet").attr("disabled", true);

                readImage(this);
            });

            function readImage(input) {
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

                                    console.log(width, height, MAX_SIZE);
                                    canvas.height = height;
                                    canvas.width = width;
                                    context.drawImage(img, 0, 0, width, height);
                                    var pngUrl = canvas.toDataURL();
                                    if(pngUrl != null)
                                    {
                                        $("textarea[name='image']").html(pngUrl);
                                        $(".myImage").css("opacity", "1.0");
                                        $("._status_image").empty();
                                        $("#qsubmit_warnet").attr("disabled", false);
                                        clearTimeout(timeout);
                                    }
                                } else {
                                    $("textarea[name='image']").html("");
                                    $(".myImage").removeAttr("src");
                                    $("._status_image").html("Image Size harus lebih dari 120px");
                                    $("#qsubmit_warnet").attr("disabled", false);
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
@stop