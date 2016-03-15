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
        <a class="btn btn-primary btn-add-category" href="{{ isset($url_add) ? $url_add : "" }}" title="Add Service"><i class="icon-plus"></i> Add Service</a>
    </div>

    <div name="paging"></div>
    <div class="well">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Service Name</th>
                    <th>Group Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if(isset($services) && !empty($services))
                    {
                        $num = 1;
                        foreach($services as $service)
                        {
                            ?>
                                <tr>
                                    <td><?= $num; ?></td>
                                    <td><?= $service['name']; ?></td>
                                    <td><?= $service['group']['name']; ?></td>
                                    <td>
                                        <a href="{{ isset($url_add) ? $url_add : "" }}" title="Edit" class="_btn_edit_service" data-id="<?= $service['id']; ?>" data-groupid="<?= $service['service_group_id']; ?>" data-gameid="<?= $service['category_id']; ?>" role="button">
                                            <i class="icon-pencil"></i> Edit
                                        </a>
                                        <a href="javascript:void(0);" style="margin-left: 5px;" data-url="{{ isset($url_del) ? $url_del : '' }}" data-status="0" title="Delete" class="_btn_delete_service" data-id="<?= $service['id']; ?>" role="button">
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
    {{ HTML::script('/main/scripts/page/crm/service/main.js') }}
@stop