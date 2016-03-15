@section('actual_content')
    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 10%;">Periode</th>
                <th style="width: 25%;">Lokasi</th>
                <th>Total Request</th>
                <th>New</th>
                <th>Checkout</th>
                <th>Canceled</th>
                <th>Finished</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}
    {{ HTML::script('/main/scripts/page/qinternal/free_dvd/main.js') }}
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