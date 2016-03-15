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
        <a class="btn btn-primary btn-add-ticketsource" href="{{ isset($url_add) ? $url_add : "" }}" title="Add Ticket Source"><i class="icon-plus"></i> Add Ticket Source</a>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Source</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($ticketsources) && !empty($ticketsources))
                    {
                        $num = 1;
                        foreach($ticketsources as $ticketsource)
                        {
                            ?>
                                <tr>
                                    <td><?= $num; ?></td>
                                    <td><?= $ticketsource['source']; ?></td>
                                    <td>
                                        <a href="{{ isset($url_add) ? $url_add : "" }}" title="Edit" class="_btn_edit_ticketsource" data-id="<?= $ticketsource['id']; ?>" role="button">
                                            <i class="icon-pencil"></i> Edit
                                        </a>
                                        <a href="javascript:void(0);" style="margin-left: 5px;" data-url="{{ isset($url_del) ? $url_del : '' }}" data-status="0" title="Delete" class="_btn_delete_ticketsource" data-id="<?= $ticketsource['id']; ?>" role="button">
                                            <i class="icon-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            $num++;
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div name="paging"></div>
    {{ HTML::script('/main/scripts/ajaxform.js') }}
    {{ HTML::script('/main/scripts/page/crm/ticketsource/main.js') }}
@stop