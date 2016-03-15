@section('form_popup')
    {{ Form::open(array('url' => $url_form, 'class' => 'form_warnet')) }}

        <?php
            if(isset($data["can_set_image"]) && $data["can_set_image"])
            {
                ?>
                    <label>Logo</label>
                    <div>
                        <input type="file" style="display: none;" disabled="disabled" id="imgInput" /> <br/>
                        <textarea style="display: none;" name="image"></textarea>
                        <div class="_preview_container">
                            <img class="myImage" {{ isset($data['warnet']['image_path']) ? "src='" . $data['warnet']['image_path'] . "'" : "" }} />
                        </div>
                        <div class="_status_image"></div>
                    </div>
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
        <input name="email" value="{{ isset($data['warnet']['email']) ? $data['warnet']['email'] : '' }}" type="Email" required />

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

        <br><hr>
        <div class="_error_container"></div>
        <input type="hidden" name="warnet_id" value="{{ $data['warnet']['id'] }}" />
        <input type='hidden' name='status_id' />
        <button type="button" class="btn btn-success" data-statusid="2" id="qproccess_warnet">Approve</button>
        <button type="button" class="btn btn-danger" data-statusid="3" id="qproccess_warnet">Reject</button>
        <a class="btn" id="form_close" style="margin-right:10px;">Batal</a>
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

    {{ HTML::script('/main/scripts/page/warnet/form.js') }}
@stop