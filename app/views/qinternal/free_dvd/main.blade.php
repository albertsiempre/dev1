@section('actual_content')
    <div class="btn-toolbar">
        <a class="btn btn-primary pull-right btn_checkout" disabled="disabled" href="{{ isset($url_checkout) ? $url_checkout : '#' }}">Checkout</a>
        <div class="pull-right __right_info">Total yang harus dikirim <span class="total_order">-</span></div>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Total Request<br/>(Finish/Total)</th>
                <th>Request Terakhir</th>
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