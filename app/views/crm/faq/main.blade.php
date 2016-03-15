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
        <a class="btn btn-primary btn-add-faq" href="{{ isset($url_add) ? $url_add : "" }}" title="Add FAQ"><i class="icon-plus"></i> Add FAQ</a>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 5%;">Name</th>
                    <th style="width: 10%;">Subservice</th>
                    <th style="width: 18%;">Question</th>
                    <th>Answer</th>
                    <th style="width: 80px;">Helpful</th>
                    <th style="width: 5%;">Public</th>
                    <th style="width: 5%;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div name="paging"></div>
    {{ HTML::style('/main/libs/jquery.chosen/chosen.css') }}
    {{ HTML::script('/main/libs/jquery.chosen/chosen.jquery.min.js') }}
    {{ HTML::script('/main/libs/ckeditor/ckeditor.js') }}
    {{ HTML::script('/main/libs/ckfinder/ckfinder.js') }}
    {{ HTML::script('/main/scripts/page/crm/faq/main.js') }}
    {{ HTML::script('/main/libs/jquery.validate.min.js') }}

    <script id="urlCKFinder" type="text/qscript">{{ asset('/main/libs/ckfinder/') }}</script>
@stop