<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading"><?php isset($data['winner']) ? "Edit" : "Add" ?> Winner</p>
        <form action="<?= isset($act_form) ? $act_form : '#'; ?>" class="form-winner" method="POST" enctype="multipart/form-data">
            <div class="block-body">
                    
                    <label>Team</label>
                    <select name="team_id" class="team_id" style="width: 350px;">
                        <option value="" >Pilih Team...</option>
                        <?php
                            if(isset($data["team"]))
                            {
                                foreach($data["team"] as $team)
                                {
                                    ?>
                                    <option value="{{ $team['id'] }}" <?php echo isset($data['winner']['team_id']) && $team['id'] == $data['winner']['team_id'] ? 'selected' : '' ?>>{{ $team['name'] }} ({{ $team['event']['name'] }})</option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    
                    <label><br />Event</label>
                    <select name="event_id" class="event_id" style="width: 350px;">
                        <option value="" >Pilih Event...</option>
                        <?php
                            if(isset($data["event"]))
                            {
                                foreach($data["event"] as $event)
                                {
                                    ?>
                                    <option value="{{ $event['id'] }}" <?php echo isset($data['winner']['event']['id']) && $event['id'] == $data['winner']['event']['id'] ? 'selected' : '' ?>>{{ $event['full_name'] }}</option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    <hr />
                    
                    <label>Name</label>
                    <input id="winner_name" class="input-xlarge" type="text" value="{{ isset($data['winner']['name']) ?  $data['winner']['name'] : '' }}" name="winner_name">                    

                    <label>Value</label>
                    <input id="winner_value" class="input-xlarge" type="text" value="{{ isset($data['winner']['value']) ?  $data['winner']['value'] : '' }}" name="winner_value">
                    
                    <label>Order</label>
                    <input id="order" class="input-xlarge" type="number" value="{{ isset($data['winner']['order']) ?  $data['winner']['order'] : '' }}" name="order">
                    
                    <label>Detail</label>
                    <textarea id="detail" name="detail"><?php echo isset($data['winner']['detail']) ?  $data['winner']['detail'] : ''; ?></textarea>                   

            </div>

            <div class="block-body  __my_footer">
                <div class="btn-toolbar">
                    <?php isset($data['winner']) ? $capt = "Edit" : $capt = "Save" ?>
                    <button class="btn btn-primary" type="submit"><?php echo $capt?></button>
                    <a class="btn in-closePopup">Cancel</a>
                    <input id="team_id" class="input-xlarge _my_textarea" type="hidden" value="{{ isset($data['winner']['id']) ?  $data['winner']['id'] : 0 }}" name="winner_id">
                    <span id="loading-form"></span>
                </div>
            </div>
        </form>
    </div>
</div>
    
<script type="text/javascript">
    $(document).ready(function(){
        $(".event_id").chosen();
        $(".team_id").chosen();
        $( '#detail' ).ckeditor();
        
//        $('#winner_value').priceFormat({
//            prefix: '',
//            centsSeparator: ',',
//            thousandsSeparator: '.',
//            centsLimit: 0
//        });      

        $("#winner_value").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                 // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) || 
                 // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        $(".team_id").chosen({width: "95%"}); 
        $(".event_id").chosen({width: "95%"}); 
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