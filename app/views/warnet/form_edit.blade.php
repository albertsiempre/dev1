<div id="popup-content" class="popup-content">
    <div class="row-fluid">
        <p class="block-heading">{{ $title }}</p>
        <div class="block-body">
            {{ Form::open(array('url' => $url_form, 'class' => 'form_warnet')) }}
                <div class="_wrap">
                    <div class="_left">
                        <!-- Old -->
                        <h2>Old</h2>
                        <?php
                            if(isset($data["can_set_image"]) && $data["can_set_image"])
                            {
                                ?>
                                    <label>Logo</label>
                                    <div>
                                        <div class="_preview_container">
                                            <img class="myImage" src="{{ isset($data['warnet']['image_path']) ? $data['warnet']['image_path'] . '?' . date('Ymdhis') : asset('/main/images/no_image.png'); }}" />
                                        </div>
                                        <div class="_status_image"></div>
                                    </div>
                                    <hr/>
                                    <input type="checkbox" name="is_new_image" value="true" /> Gunakan Logo Baru
                                    <hr/><br/>
                                <?php
                            }
                        ?>

                        <label>Kota</label>
                        <select name="kota" class="sel_city">
                            <?php
                                if(isset($data["city"]))
                                {
                                    foreach($data["city"] as $city)
                                    {
                                        ?>
                                            <option value="{{ $city['id'] }}" {{ isset($data['warnet']['city']['id']) && $city['id'] == $data['warnet']['city']['id'] ? 'selected' : '' }}>{{ $city['name'] }}</option>
                                        <?php
                                    }
                                }
                            ?>
                        </select>

                        <hr/>
                        <label>Nama</label>
                        <input type="text" value="{{ isset($data['warnet']['name']) ? $data['warnet']['name'] : '' }}" required name="nama" />

                        <label>Nama Owner</label>
                        <input type="text" name="owner_name" value="{{ isset($data['warnet']['owner_name']) ? $data['warnet']['owner_name'] : '' }}" />

                        <label>Email</label>
                        <input name="email" value="{{ isset($data['warnet']['email']) ? $data['warnet']['email'] : '' }}" type="Email" />

                        <label>Phone</label>
                        <input name="phone" value="{{ isset($data['warnet']['phone']) ? $data['warnet']['phone'] : '' }}" type="text" />

                        <label>Alamat</label>
                        <textarea name="alamat">{{ isset($data['warnet']['address']) ? $data['warnet']['address'] : '' }}</textarea>

                        <?php
                            if(isset($data["can_set_warnet_type"]) && $data["can_set_warnet_type"])
                            {
                                ?>
                                    <label>Type Warnet</label>
                                    <select name="type_id">
                                        <?php
                                            if(isset($data["type"]))
                                            {
                                                foreach($data["type"] as $type)
                                                {
                                                    ?>
                                                        <option {{ isset($data['warnet']['type_id']) && $type['id'] == $data['warnet']['type_id'] ? 'selected' : '' }} value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                <?php
                            }
                        ?>

                        <?php
                            if(isset($data["can_add_flag_qash"]) && $data["can_add_flag_qash"])
                            {
                                ?>
                                    <br/><hr/>
                                    <label>Qash</label>
                                    <input type="checkbox" {{ isset($data['warnet']['is_qash']) && $data['warnet']['is_qash'] ? 'checked' : '' }} name="is_qash" value="1" /> Ya
                                <?php
                            }
                        ?>

                        <?php
                            if(isset($data["can_add_flag_dvd"]) && $data["can_add_flag_dvd"])
                            {
                                ?>
                                    <br/><hr/>
                                    <label>Free DVD</label>
                                <?php
                                if(isset($data["games"]))
                                {
                                    $data_warnet_dvd = array();
                                    if(isset($data['warnet']['dvd']))
                                    {
                                        foreach($data['warnet']['dvd'] as $game_dvd)
                                        {
                                            $data_warnet_dvd[] = $game_dvd['game_id'];
                                        }
                                    }

                                    foreach($data["games"] as $games)
                                    {
                                        ?>
                                            <input {{ in_array($games['id'], $data_warnet_dvd) ? 'checked' : '' }} type="checkbox" name="is_dvd[]" value="{{ $games['id'] }}" /> {{ $games["name"] }} <br/>
                                        <?php
                                    }
                                }
                            }
                        ?>

                        <?php
                            if(isset($data["can_add_flag_play_bonus"]) && $data["can_add_flag_play_bonus"])
                            {
                                ?>
                                    <br/><hr/>
                                    <label>Play Bonus</label>
                                <?php
                                if(isset($data["games"]))
                                {
                                    $data_warnet_play = array();
                                    if(isset($data['warnet']['play_bonus']))
                                    {
                                        foreach($data['warnet']['play_bonus'] as $game_dvd)
                                        {
                                            $data_warnet_play[] = $game_dvd['game_id'];
                                        }
                                    }

                                    foreach($data["games"] as $games)
                                    {
                                        ?>
                                            <input {{ in_array($games['id'], $data_warnet_play) ? 'checked' : '' }} type="checkbox" name="is_game[]" value="{{ $games['id'] }}" /> {{ $games["name"] }} <br/>
                                        <?php
                                    }
                                }
                            }
                        ?>
                    </div>
                    <div class="_right">
                        <!-- New -->
                            <h2>New</h2>
                                <?php
                                if(isset($data["can_set_image"]) && $data["can_set_image"])
                                {
                                    ?>
                                        <label>Logo</label>
                                        <div>
                                            <div class="_preview_container">
                                                <img class="myImage" src="{{ isset($data['warnet']['warnet_edit']['image_path']) ? $data['warnet']['warnet_edit']['image_path'] . '?' . date('Ymdhis') : asset('/main/images/no_image.png'); }}" />
                                            </div>
                                        </div>
                                        <hr/><br/>
                                    <?php
                                }
                            ?>

                            <label>Kota</label>
                            {{ isset($data['warnet']['warnet_edit']['city']['name']) ? $data['warnet']['warnet_edit']['city']['name'] : '' }}

                            <hr/>
                            <label>Nama</label>
                            {{ isset($data['warnet']['warnet_edit']['name']) ? $data['warnet']['warnet_edit']['name'] : '' }}

                            <hr/>
                            <label>Nama Owner</label>
                            {{ isset($data['warnet']['warnet_edit']['owner_name']) ? $data['warnet']['warnet_edit']['owner_name'] : '' }}

                            <hr/>
                            <label>Email</label>
                            {{ isset($data['warnet']['warnet_edit']['email']) ? $data['warnet']['warnet_edit']['email'] : '' }}

                            <hr/>
                            <label>Phone</label>
                            {{ isset($data['warnet']['warnet_edit']['phone']) ? $data['warnet']['warnet_edit']['phone'] : '' }}

                            <hr/>
                            <label>Alamat</label>
                            {{ isset($data['warnet']['warnet_edit']['address']) ? $data['warnet']['warnet_edit']['address'] : '' }}

                            <hr/>
                            <label>Type Warnet</label>
                            {{ isset($data['warnet']['warnet_edit']['type']['name']) ? $data['warnet']['warnet_edit']['type']['name'] : '' }}

                            <hr/>
                            <label>Qash</label>
                            {{ isset($data['warnet']['warnet_edit']['is_qash']) && $data['warnet']['warnet_edit']['is_qash'] ? 'Ya' : 'Tidak' }}

                            <hr/>
                            <label>Free DVD</label>
                            <ul>
                                <?php
                                    if(isset($data["games"]))
                                    {
                                        $data_warnet_dvd = array();
                                        if(isset($data['warnet']['warnet_edit']['dvd']))
                                        {
                                            foreach($data['warnet']['warnet_edit']['dvd'] as $game_dvd)
                                            {
                                                $data_warnet_dvd[] = $game_dvd['game_id'];
                                            }
                                        }

                                        foreach($data["games"] as $games)
                                        {
                                            if(in_array($games['id'], $data_warnet_dvd))
                                            {
                                                ?>
                                                    <li>{{ $games["name"] }}</li>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </ul>

                            <hr/>
                            <label>Play Bonus</label>
                            <ul>
                                <?php
                                    if(isset($data["games"]))
                                    {
                                        $data_warnet_play = array();
                                        if(isset($data['warnet']['warnet_edit']['play_bonus']))
                                        {
                                            foreach($data['warnet']['warnet_edit']['play_bonus'] as $game_dvd)
                                            {
                                                $data_warnet_play[] = $game_dvd['game_id'];
                                            }
                                        }

                                        foreach($data["games"] as $games)
                                        {
                                            if(in_array($games['id'], $data_warnet_play))
                                            {
                                                ?>
                                                    <li>{{ $games["name"] }}</li>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                <br><hr>
                <div class="_error_container"></div>
                <input type="hidden" name="warnet_id" value="{{ isset($data['warnet']['id']) ? $data['warnet']['id'] : '' }}" />
                <input type='hidden' name='status_id' />
                <div class="_btn_wrap">
                    <button type="button" class="btn btn-success" data-statusid="2" id="qproccess_warnet">Approve</button>
                    <button type="button" class="btn btn-danger" data-statusid="3" id="qproccess_warnet">Reject</button>
                    <a class="btn" id="form_close" style="margin-right:10px;">Batal</a>
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        $("body").ready(function(){
            var magnificPopup = $.magnificPopup.instance;
            var needRefresh = false;
            $("body").on("click", "#form_close", function(){
                magnificPopup.close();
                if(needRefresh)
                {
                    location.reload();
                }
            });
            var _class_success = 'span12 success';
            var _class_error = 'span12 error';

            function _clean_input_background_(form_object){
                $(form_object).each(function(){
                    $(this).attr('class','span12');
                });
            }
        });
    </script>
</div>

<script type="text/qscript" id="tmp_error_msg">
    <div id="error_message" class="alert alert-error"></div>
</script>

<script type="text/qscript" id="tmp_success_msg">
    <div id="error_message" class="alert alert-success"></div>
</script>

<style>
    ._btn_wrap {
        overflow: hidden;
        position: relative;
        margin: auto;
        text-align: center;
    }

    .popup-content h2 {
        text-align: center;
        border-bottom: 1px solid #cccccc;
    }

    ._wrap {
        width: 80%;
        margin: 0px auto;
        overflow: hidden;
        position: relative;
    }

    ._wrap ._left {
        float: left;
    }

    ._wrap ._right {
        float: right;
    }

    .popup-content {
        width: 50%;
    }

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

{{ HTML::script('/main/scripts/page/warnet/form.js') }}