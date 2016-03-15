var get_privileges = function()
{
    $('#grant_privilege_form button').attr("disabled", "disabled");
    $('#list_privilege_container').html('<div style="width=100%;text-align:center;"><img src="'+src_loading_image+'" alt="loading" /></div>');
    $.get(url_get_privileges + filter_id + '/' + $('#system_id').val() + '/' + $('#privilege_group_id').val(), function(data){
            $('#list_privilege_container').html(data);
            $('#grant_privilege_form button').removeAttr("disabled");
            rebind_toggle_check_privilege();
        });
}

var rebind_get_privileges = function()
{
    $('#privilege_group_id').unbind('change').change(get_privileges);
}

var rebind_toggle_check_privilege = function()
{
    $('.toggle-check-privilege').click(function(){
        var icon = $(this).children('i');
        var new_class = 'icon-check';
        var new_title = privilege_mode == 'role' ? 'check all' : 'allow all';
        var list_check = $(this).parents('div.block').find(privilege_mode == 'role' ? 'input[type="checkbox"]' : 'input[type="radio"][value="1"]');
        var list_uncheck = privilege_mode == 'administrator' ? $(this).parents('div.block').find('input[type="radio"][value="0"]') : null;
        if(icon.attr('class') == 'icon-check-empty')
        {
            list_check.attr('checked','checked');
            new_title = (privilege_mode == 'role' ? 'un' : 'dis') + new_title;
            if(privilege_mode == 'administrator') list_uncheck.removeAttr('checked');
        }
        else
        {
            new_class += '-empty';
            list_check.removeAttr('checked');
            if(privilege_mode == 'administrator') list_uncheck.attr('checked','checked');
        }
        
        icon.attr('class',new_class);
        $(this).attr('title',new_title).attr('data-original-title',new_title);
        return false;
    });
}

$(function(){
    rebind_get_privileges();
    $('#system_id').change(function(){
        $.get(url_get_privilege_group + $(this).val(), function(data){
            $('#privilege_group_id').replaceWith(data);
            rebind_get_privileges();
            get_privileges();
        });
    });
    rebind_toggle_check_privilege();
});