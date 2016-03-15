@section('actual_content')
    <div class="btn-toolbar">
        <a class="btn btn-primary btn_new" href="{{ isset($url_new_winner) ? $url_new_winner : '#' }}"><i class="icon-plus"></i> New Winner</a>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 100px;">No</th>
                <th>Winner</th>
                <th style="width: 500px;">Event Name</th>
                <th>Team Name</th>
                <th>Value</th>
                <!--<th>Detail</th>-->
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div name="paging"></div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}  
    <!--{{ HTML::script('/main/libs/jquery.price.format/jquery.price_format.2.0.js') }}-->  
    {{ HTML::script('/main/libs/ckeditor/ckeditor.js') }}
    {{ HTML::script('/main/libs/ckeditor/adapters/jquery.js') }}
    {{ HTML::script('/main/scripts/page/qinternal/mgs/main.js') }}
    {{ HTML::style('/main/libs/jquery.validate.min.js') }}

    <style>
        .btn-toolbar {
            display: block;
            position: relative;
            overflow: hidden;
            font-size: 14px;
        }

        .__right_info {
            margin-top: 5px;
            margin-right: 5px;
        }

        span.total_order {
            font-weight: bold;
        }
    </style>

@stop