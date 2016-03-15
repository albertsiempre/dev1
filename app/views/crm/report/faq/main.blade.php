@section('actual_content')
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="block span12" style="border: none !important;">
                <div class="stats">
                    <p class="stat"><span class="number _tq">0</span> Total Question</p>
                    <p class="stat"><span class="number _tuv">0</span> Total User Vote</p>
                    <p class="stat"><span class="number _tiv">0</span> Total Internal Vote</p>
                    <p class="stat"><span class="number _rup">0</span> Rata-Rata User (+)</p>
                    <p class="stat"><span class="number _run">0</span> Rata-Rata User (-)</p>
                    <p class="stat"><span class="number _rip">0</span> Rata-Rata Internal (+)</p>
                    <p class="stat"><span class="number _rin">0</span> Rata-Rata Internal (-)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">Periode</th>
                    <th style="width: 5%;">Service</th>
                    <th style="width: 5%;">Sub Service</th>
                    <th style="width: 20%;">Question</th>
                    <th style="width: 5%; text-align: right;">User (+)</th>
                    <th style="width: 5%; text-align: right;">User (-)</th>
                    <th style="width: 5%; text-align: right;">Internal (+)</th>
                    <th style="width: 5%; text-align: right;">Internal (-)</th>
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