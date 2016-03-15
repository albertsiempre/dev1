@section('actual_content')
    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th style="width: 25%;">Lokasi</th>
                <th>Username</th>
                <th>Total Qash</th>
                <th>Total Bonus Qash</th>
                <th>Total Purchase Item</th>
                <th>Total Playtime</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='5' style="color: red; font-size: 12px;">*) Bonus cash tidak termasuk bonus cash yang diinject oleh payletter.</td>
                </tr>
            </tfoot>
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