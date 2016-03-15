@section('actual_content')
    <div class="btn-toolbar">
        <?php 
            if(isset($add) && $add)
            { 
                ?>
                    <a class="btn btn-primary ajax-popup" href="{{ isset($url_add) ? $url_add : "" }}" title="Add Widget"><i class="icon-plus"></i> Add Widget</a>
                <?php 
            } 
        ?>
    </div>

    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 2%;">ID</th>
                <th>Title</th>
                <th>Start - End Date</th>
                <th>Target</th>
                <th>Type</th>
                <th style="width: 3%; text-align: center;">Survey<br/>ID</th>
                <th>Desc</th>
                <th>Label</th>
                <th>URL</th>
                <th style="width: 3%; text-align: center;">Priority</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}
    {{ HTML::script('/main/scripts/page/warnet/main.js') }}
    {{ HTML::style('/main/libs/jquery.validate.min.js') }}

@stop