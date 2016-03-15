<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading">Details</p>
        <div class="block-body">
            {{ Form::open(array('url' => isset($url_action) ? $url_action : '', 'id' => '_send_dvd_request', 'class' => 'check')) }}
                <input type="hidden" name="user_id" value="" id="_frm_user_id" />
                <div class="check__wrapper">
                    <?php
                        if(isset($result))
                        {
                            foreach($result as $res)
                            {
                                ?>
                                    <div class="check__col">
                                        <input type="checkbox" id="check<?= $res['id']; ?>" name="detail_id[<?= $res['id']; ?>]">
                                        <label for="check<?= $res['id']; ?>">
                                            <?php
                                                echo date("d M Y, H:i:s", strtotime($res["date"])) . "<br/>";
                                                $address = $res["address"] . " RT/RW " . $res["rt_rw"] . "<br/>";
                                                $address .= $res["kelurahan_name"] . " - " . $res["kecamatan_name"] . "<br/>";
                                                $address .= $res["city_name"] . " - " . $res["province_name"] . "<br/>";
                                                $address .= $res["postal_code"];
                                                echo $address;
                                            ?>
                                            <input type="text" name="note[<?= $res['id']; ?>]" placeholder="No. Resi/Keterangan">
                                        </label>
                                    </div>
                                <?php
                            }
                        }
                    ?>
                </div>
                
                <div class="btn-toolbar">
                    <button class="btn" type="submit">Send</button>
                    <span style="margin-left: 10px;" id="loading-send-form"></span>
                </div>
            </form>
        </div>
    </div>
</div>

{{ HTML::script('/main/scripts/ajaxform.js') }}
{{ HTML::script('/main/scripts/page/qinternal/free_dvd/main.js') }}

<style>
    .popup-content {
        width: 80%;
    }
</style>