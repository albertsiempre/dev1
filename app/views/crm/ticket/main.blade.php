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
        <a class="btn btn-primary btn-add-ticket" href="{{ isset($url_add) ? $url_add : "" }}" title="Create Ticket"><i class="icon-plus"></i> Create Ticket</a>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10%;">Ticket ID</th>
                    <th style="width: 10%;">Issued By</th>
                    <th style="width: 15%;">Email</th>
                    <th style="width: 7%;">Service</th>
                    <th>Question</th>
                    <th style="width: 5%;">Date</th>
                    <th style="width: 10%; text-align: center;">Ticket Status</th>
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
    {{ HTML::script('/main/scripts/page/crm/ticket/main.js') }}
    {{ HTML::script('/main/libs/jquery.validate.min.js') }}

    <script id="urlCKFinder" type="text/qscript">{{ asset('/main/libs/ckfinder/') }}</script>
@stop