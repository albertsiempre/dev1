<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading">Details</p>
        <?php
            if($result != null)
            {
                ?>
                    <div class="block-body">
                        <div class="span9" style="padding: 5px 0px;">
                            User ID : <span id="_user_id"></span><br/>
                            <span id="_user_email"></span>
                        </div>
                        <div class="span3">
                            <div class="__my_box">
                                Request
                                <div class="__my_number"><span id="_user_total_request"></span></div>
                            </div>
                            <div class="__my_box">
                                Terkirim
                                <div class="__my_number"><span id="_user_current_request"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="block-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Penerima</th>
                                    <th>No. Telp</th>
                                    <th>Alamat</th>
                                    <th>Request DVD</th>
                                    <th>Tanggal Request</th>
                                    <th>No. Resi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($result) && !empty($result))
                                    {
                                        foreach($result as $res)
                                        {
                                            ?>
                                                <tr>
                                                    <td><?= $res["name"]; ?></td>
                                                    <td><?= $res["phone"]; ?></td>
                                                    <td>
                                                        <?php
                                                            $address = $res["address"] . " RT/RW " . $res["rt_rw"] . "<br/>";
                                                            $address .= $res["kelurahan_name"] . " - " . $res["kecamatan_name"] . "<br/>";
                                                            $address .= $res["city_name"] . " - " . $res["province_name"] . "<br/>";
                                                            $address .= $res["postal_code"];
                                                        ?>
                                                        <button class="btn" data-address="<?= $address; ?>" id="_show_address">Show</button>
                                                    </td>
                                                    <td><?= $res["category_name"]; ?></td>
                                                    <td>
                                                        <span class="pull-left">
                                                            <?= date("d M Y", strtotime($res["date"])); ?>
                                                        </span>
                                                        <?php
                                                            if($res["dvd_request_status_id"] == "2")
                                                            {
                                                                ?>
                                                                    <div class="__success_box_small">
                                                                        <i class="fa fa-check"></i>
                                                                    </div>
                                                                <?php
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?= $res["resi"]; ?></td>
                                                    <td>
                                                        <?php
                                                            if(isset($res['is_finished']))
                                                            {
                                                                if($res['is_finished'] == 0)
                                                                {
                                                                    // Nay
                                                                    ?>
                                                                        <i class="fa fa-times" style="font-size: 18px; color: #c0392b;"></i>
                                                                    <?php
                                                                } else {
                                                                    // Yay
                                                                    ?>
                                                                        <i class="fa fa-check" style="font-size: 18px; color: #27ae60;"></i>
                                                                    <?php
                                                                }
                                                            }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="block-body  __my_footer">
                        {{ Form::open(array('url' => $url, 'id' => 'update_note_form')) }}
                            Note<br/>
                            <textarea name="note" class="_my_textarea"></textarea>
                            <input type="hidden" name="req_id" value="" />
                            <div class="btn-toolbar">
                                <button class="btn" type="submit">Update</button>
                                <span id="loading-form"></span>
                            </div>
                        </form>
                    </div>
                <?php
            } else {
                ?>
                    <div class="block-body">
                        <div class="span12" style="padding: 5px 0px;">
                            Data not found.
                        </div>
                    </div>
                <?php
            }
        ?>
        
    </div>
</div>

{{ HTML::script('/main/scripts/page/qinternal/free_dvd/main.js') }}

<style>
    .popup-content {
        width: 80%;
    }

    ._my_textarea {
        width: 98%;
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
</style>