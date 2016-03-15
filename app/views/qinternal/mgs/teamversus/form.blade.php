<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading"><?php isset($data['teamversus']) ? "Edit" : "Add" ?> Match</p>
        <form action="<?= isset($act_form) ? $act_form : '#'; ?>" class="form-teamversus" method="POST" enctype="multipart/form-data">
            <div class="block-body">
                    
                    <label>Team</label>
                    <select name="team_id" class="team_id" id="team_id" style="width: 350px;">
                        <?php
                            if(isset($data["team"]))
                            {
                                foreach($data["team"] as $team)
                                {
                                    ?>
                                    <option value="{{ isset($team['id']) ? $team['id'] : '' }}" <?php echo isset($data['teamversus']['team_id']) && $team['id'] == $data['teamversus']['team_id'] ? 'selected' : '' ?> data-event="{{ isset($team['event']['id']) ? $team['event']['id'] : '' }}">{{ isset($team['name']) ? $team['name'] : '' }} ({{ isset($team['event']['name']) ? $team['event']['name'] : '' }})</option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    
                    <label><br />Opponent</label>
                    <select name="opponent_id" class="opponent_id" id="opponent_id" style="width: 350px;">
                        <?php
                            if(isset($data["team"]))
                            {
                                foreach($data["team"] as $team)
                                {
                                    ?>
                                    <option value="{{ isset($team['id']) ? $team['id'] : '' }}" <?php echo isset($data['teamversus']['opponent_id']) && $team['id'] == $data['teamversus']['opponent_id'] ? 'selected' : '' ?> data-event="{{ isset($team['event']['id']) ? $team['event']['id'] : '' }}">{{ isset($team['name']) ? $team['name'] : '' }} ({{ isset($team['event']['name']) ? $team['event']['name'] : '' }})</option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    <hr />
                    <label>Link</label>
                    <input id="link" class="input-xlarge" type="text" value="{{ isset($data['teamversus']['link']) ?  $data['teamversus']['link'] : '' }}" name="link">    
                    
                    <label>Start Date</label>
                    <input type="text" name="start_date" id="start_date" class="start_date" value="{{ isset($data['teamversus']['start_date']) ?  date('Y-m-d', strtotime($data['teamversus']['start_date'])) : date('Y-m-d') }}">
                    
                    <label>End Date</label>
                    <input type="text" name="end_date" id="end_date" class="end_date" value="{{ isset($data['teamversus']['end_date']) ?  date('Y-m-d', strtotime($data['teamversus']['end_date'])) : date('Y-m-d') }}">                   

            </div>

            <div class="block-body  __my_footer">
                <div class="btn-toolbar">
                    <?php isset($data['teamversus']) ? $capt = "Edit" : $capt = "Save" ?>
                    <button class="btn btn-primary" type="submit"><?php echo $capt?></button>
                    <a class="btn in-closePopup">Cancel</a>
                    <input id="team_versus_id" class="input-xlarge _my_textarea" type="hidden" value="{{ isset($data['teamversus']['id']) ?  $data['teamversus']['id'] : 0 }}" name="team_versus_id">
                    <span id="loading-form"></span>
                </div>
            </div>
        </form>
    </div>
</div>

    
<script type="text/javascript">
    $(document).ready(function(){
        $(".team_id").chosen();
        $(".opponent_id").chosen();
        setDatePicker();
    });
</script>
<style>
    .popup-content {
        width: 30%;
    }
    
    .block-body {
        overflow: hidden;
    }

    .__my_footer {
        margin: 0px;
        padding: 1em;
        background: #ededed;
    }
</style>