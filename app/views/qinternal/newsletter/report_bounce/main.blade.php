@section('actual_content')

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 100px;">No</th>
                <th>Date</th>
                <th style="text-align: center;">Total Bounce</th>
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