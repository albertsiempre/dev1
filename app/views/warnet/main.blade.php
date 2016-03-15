@section('actual_content')
    <div class="btn-toolbar">
        <?php 
            if(isset($add) && $add)
            { 
                ?>
                    <a class="btn btn-primary ajax-popup" href="{{ isset($url_add) ? $url_add : "" }}" title="Add Warnet"><i class="icon-plus"></i> Add Warnet</a>
                <?php 
            } 
        ?>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <?php
                        if(isset($isSales) && $isSales)
                        {
                            ?>
                                <th>Nama Warnet</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Provinsi</th>
                                <th>Action</th>
                            <?php
                        } else {
                            ?>
                                <th>ID</th>
                                <th>Nama Warnet</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Provinsi</th>
                                <th>Join Date</th>
                                <th>Action</th>
                            <?php
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div name="paging"></div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}
    {{ HTML::script('/main/scripts/page/warnet/main.js') }}
    {{ HTML::style('/main/libs/jquery.validate.min.js') }}
@stop