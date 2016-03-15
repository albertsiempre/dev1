@section('actual_content')
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">Periode</th>
                    <th style="width: 7%;">Service</th>
                    <th style="width: 7%;">Sub Service</th>
                    <th style="width: 7%;">Tgl Input</th>
                    <th style="width: 7%;">Tgl Closing</th>
                    <th style="width: 5%;">User</th>
                    <th style="width: 18%;">Pertanyaan</th>
                    <th style="width: 5%; text-align: right;">Durasi</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}
    {{ HTML::script('/main/libs/ckeditor/ckeditor.js') }}
    {{ HTML::script('/main/libs/ckfinder/ckfinder.js') }}
    {{ HTML::script('/main/scripts/page/crm/report/faq/main.js') }}
    {{ HTML::script('/main/libs/jquery.validate.min.js') }}

    <script id="urlCKFinder" type="text/qscript">{{ asset('/main/libs/ckfinder/') }}</script>
@stop